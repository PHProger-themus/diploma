<?php

namespace app\models;

use system\core\QueryBuilder;

class Ajax extends QueryBuilder
{

  public function table() {
    return '';
  }

  public function getClients(string $keyword)
  {
    return $this->all(['ID', 'name'], 'clients')
      ->add(" WHERE name LIKE '%{$keyword}%'")
      ->limit(10)
      ->withEachRow(fn($row) => ['ID' => $row['ID'], 'name' => htmlspecialchars_decode($row['name'])])
      ->getRows();
  }

  public function getProducts(string $keyword)
  {
    return $this->all(['ID', 'name'], 'products')
      ->add(" WHERE name LIKE '%{$keyword}%'")
      ->limit(10)
      ->withEachRow(fn($row) => ['ID' => $row['ID'], 'name' => htmlspecialchars_decode($row['name'])])
      ->getRows();
  }
}
