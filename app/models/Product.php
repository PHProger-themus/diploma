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
   * @return \app\dto\Product[]
   */
  public function get(array $where = []): array
  {
    $query = $this->all();
    foreach ($where as $condition) {
      $query->where($condition[0], $condition[1], $condition[2] ?? '=');
    }

    /** @var \app\dto\Product[] $rows */
    $rows = $query->getRows();

    foreach ($rows as &$row) {
      $productID = $row->ID;
      $row->quantityColor = $this->getQuantityColor($row->quantity);
      $row->reserve = $this->all([], 'reserve')->where('product_id', $productID)->getRows();
      $row->reserveTotal = $this->getTotal($row->reserve);
      $row->orders = $this->all([], 'orders')->where('product_id', $productID)->getRows();
      $row->ordersTotal = $this->getTotal($row->orders);
    }
    return $rows;
  }

  public function reserve(int $productId, string $company, int $quantity)
  {
    $this->insert([
      'product_id' => $productId,
      'company_name' => $company,
      'quantity' => $quantity
    ], 'reserve');
  }

  public function getQuantityColor(int $quantity): string
  {
    if (!$quantity) {
      return 'g-color-red';
    }
    return 'g-color-green';
  }

  /**
   * Количество сущностей. Массив должен иметь сущности со свойством quantity.
   * @param array $array
   * @return int
   */
  private function getTotal(array $array): int
  {
    return !empty($array) ? array_reduce($array, static fn($sum, $item) => $sum + $item->quantity) : 0;
  }

}