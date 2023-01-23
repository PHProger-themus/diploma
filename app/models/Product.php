<?php

namespace app\models;

use QueryBuilder;
use system\classes\ArrayHolder;

class Product extends QueryBuilder // Модель для работы с пользователем
{

  private ArrayHolder $form;

  public function __construct(ArrayHolder $data = NULL)
  {
    if (!is_null($data)) {
      $this->form = $data;
    }
    parent::__construct($data);
  }

  // Правила валидации. Логин и пароль - обязательные поля, минимум 3 символа
  public function rules()
  {
    return [
      'name' => ['required'],
      'price' => ['required', 'isNumber'],
    ];
  }

  // Названия полей (для ошибок валидации)
  public function fields()
  {
    return [
      'name' => 'Наименование',
      'price' => 'Закупочная цена',
      'quantity' => 'Кол-во на складе',
    ];
  }

  // Таблица, с которой работает данная модель
  public function table()
  {
    return 'products';
  }

  /**
   * Получить список товаров
   * @param array $where [[field, value, sign?], [field, value, sign?], ...]
   * @return array|false
   */
  public function get(array $where = [])
  {
    $query = $this->all();
    foreach ($where as $condition) {
      $query->where($condition[0], $condition[1], $condition[2] ?? '=');
    }
    $rows = $query->getRows();

    foreach ($rows as &$row) {
      $row->quantityColor = $this->getQuantityColor($row->quantity);
      $row->reserve = $this->all([], 'reserve')->where('product_id', $row->ID)->getRows();
      $row->reserveTotal = !empty($row->reserve) ? array_reduce($row->reserve, static fn($sum, $item) => $sum + $item->quantity) : 0;
    }
    return $rows;
  }

  public function getQuantityColor(int $quantity)
  {
    if (!$quantity) {
      return 'g-color-red';
    }
    return 'g-color-green';
  }

}