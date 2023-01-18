<?php

namespace system\core;

use PDO;
use system\classes\ArrayHolder;
use system\classes\SafetyManager;
use system\interfaces\QueryBuilderInterface;

abstract class QueryBuilder extends DB implements QueryBuilderInterface
{

  public function getRows(string $query = NULL)
  {
    $rows = $this->getRowsAsArray($query);
    foreach ($rows as &$data) {
      $data = ArrayHolder::new($data);
    }
    return $rows;
  }

  public function getRowsAsArray(string $query = NULL)
  {
    $sth = $this->execute($query);
    $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as &$data) {
      foreach ($data as &$value) {
        $value = (!is_null($value) ? SafetyManager::filterString($value) : $value);
      }
    }
    return $rows;
  }

  public function getRow(string $query = NULL)
  {
    $rows = $this->getRowsAsArray($query);
    if (!empty($rows)) {
      return ArrayHolder::new($this->getRowsAsArray($query)[0]);
    }
    return NULL;
  }

  public function all(array $columns = [], string $table = NULL, bool $distinct = false): self
  {
    $this->query = 'SELECT' . ($distinct ? ' DISTINCT' : '') . $this->columnsToString($columns) . ' FROM ' . $this->getTable($table);
    return $this;
  }

  protected function columnsToString(array $columns)
  {
    $columns_str = NULL;
    if ($columns != []) {
      foreach ($columns as $column => $alias) {
        $columns_str .= (!is_null($columns_str) ? ', ' : ' ') . (is_int($column) ? $this->addTablePrefix($alias) : ($column != self::COUNT ? $this->addTablePrefix($column) : 'COUNT(*)') . ' AS ' . $alias);
      }
    } else
      $columns_str = ' *';
    return $columns_str;
  }

  private function addTablePrefix($column): string // Добавляем префикс, если столбец записан в виде table.column
  {
    if (strpos($column, '.') !== false) {
      return $this->dbi->prefix . $column;
    }
    return $column;
  }

  private function getTable(?string $table)
  {
    if (is_null($table)) {
      $table = $this->dbi->prefix . $this->table();
    } else {
      $table = $this->dbi->prefix . $table;
    }
    foreach ($this->dbi->trusted_tables as $trusted) {
      if ($table == $this->dbi->prefix . $trusted) {
        return $table;
      }
    }
    throw new \Error("Таблица не обслуживается приложением");
  }

  abstract public function table();

  public function first(array $columns = [], string $table = NULL): self
  {
    $this->query = 'SELECT' . $this->columnsToString($columns) . ' FROM ' . $this->getTable($table) . ' LIMIT 1';
    return $this;
  }

  public function last(array $columns = [], string $table = NULL): self
  {
    $this->query = 'SELECT' . $this->columnsToString($columns) . ' FROM ' . $this->getTable($table) . ' LIMIT 1 DESC';
    return $this;
  }

  public function insert(array $values, string $table = NULL): void
  {
    $insert_data = "";
    foreach ($values as $column => $value) {
      $insert_data .= (!empty($insert_data) ? ", " : "") . "$column = '$value'";
    }
    $this->query = 'INSERT INTO ' . $this->getTable($table) . ' SET ' . $insert_data;
    $this->execute();
  }

  public function update(array $values, string $table = NULL): self
  {
    $update_data = "";
    foreach ($values as $column => $value) {
      $update_data .= (!empty($update_data) ? ", " : "") . "$column = '$value'";
    }
    $this->query = 'UPDATE ' . $this->getTable($table) . ' SET ' . $update_data;
    return $this;
  }

  public function delete(string $table = NULL): self
  {
    $this->query = 'DELETE FROM ' . $this->getTable($table);
    return $this;
  }

  public function join(string $table, array $on, string $mode = self::INNER): self
  {
    $on_key = array_key_first($on);
    $this->query .= ' ' . $mode . 'JOIN ' . $this->getTable($table) . ' ON ' . $this->dbi->prefix . $on_key . ' = ' . $this->dbi->prefix . $on[$on_key];
    return $this;
  }

  public function where(string $column, string $value, string $sign = '='): self
  {
    $this->query .= (strpos($this->query, 'WHERE') === false ? " WHERE " : " ") . $this->addTablePrefix($column) . " $sign '$value'";
    return $this;
  }

  public function add(string $add_str): self
  {
    $this->query .= ($add_str != self::RIGHT_QUOTE ? '' : ' ') . $add_str;
    return $this;
  }

  public function orderBy(string $column, string $mode = ""): self
  {
    $this->query .= " ORDER BY " . $this->addTablePrefix($column) . "$mode";
    return $this;
  }

  public function groupBy(array $columns): self
  {
    foreach ($columns as $key => $column) {
      $this->query .= (strpos($this->query, 'GROUP BY') === false ? " GROUP BY " : ", ") . $this->addTablePrefix($column);
    }
    return $this;
  }

  public function limit(int $limit): self
  {
    $this->query .= " LIMIT $limit";
    return $this;
  }

  public function offset(int $offset): void
  {
    $this->query .= " OFFSET $offset";
  }

  public function getLastInsertId(): string
  {
    return $this->db->lastInsertId();
  }

  public function checkData(array $params): bool
  {
    $query = "";
    foreach ($params as $column => $data) {
      if ($column == 'password') {
        $data = SafetyManager::encryptPassword($data);
      }
      $query .= (!empty($query) ? ' AND ' : ' WHERE ') . "$column = '$data''";
    }
    if ($this->rowsCount($query)) {
      return true;
    }
    return false;
  }

  public function rowsCount(string $query = NULL): int
  {
    return count($this->getRowsAsArray($query));
  }

}
