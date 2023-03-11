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
    return $this->all(['name'], 'clients')
      ->add(" WHERE name LIKE '%{$keyword}%'")
      ->limit(10)
      ->withEachRow(fn($row) => ['name' => htmlspecialchars_decode($row['name'])])
      ->getRows();
  }
}
