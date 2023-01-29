<?php

namespace app\models;

use QueryBuilder;
use system\classes\ArrayHolder;
use system\classes\FormHelper;
use system\classes\LinkBuilder;

class Order extends QueryBuilder // Модель для работы с пользователем
{

  private ArrayHolder $form;
  private ArrayHolder $cleanForm;

  public function __construct(ArrayHolder $data = null)
  {
    if (!is_null($data)) {
      $this->form = $data;
      $this->cleanForm = $this->clearForm($this->form);
    }
    parent::__construct($data);
  }

  // Правила валидации. Логин и пароль - обязательные поля, минимум 3 символа
  public function rules()
  {
    return [
      'product' => ['required', 'exists' => ['values' => [$this, 'products', 'ID']]],
      'quantity' => ['required', 'isNumber'],
    ];
  }

  // Названия полей (для ошибок валидации)
  public function fields()
  {
    return [
      'product' => 'Товар',
      'quantity' => 'Кол-во',
      'packed' => 'Упаковано',
    ];
  }

  // Таблица, с которой работает данная модель
  public function table()
  {
    return 'orders';
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

    /** @var \app\dto\Order[] $rows */
    $rows = $query->getRows();

    foreach ($rows as &$row) {
      $productID = $row->product_id;
      $row->product = $this->all([], 'products')->where('ID', $productID)->getRow();
    }
    return $rows;
  }

  public function create()
  {
    if ($this->correct()) {
      $this->cleanForm->product_id = (int)$this->cleanForm->product;
      unset($this->cleanForm->product);
      $this->cleanForm->packed = $this->cleanForm->packed ?: null;
      $this->insert($this->cleanForm);
      LinkBuilder::redirect('orders');
    } else {
      $this->backWithError(implode(', ', $this->getErrors()));
    }
  }

  public function remove($id)
  {
    $this->delete()->where('ID', $id)->execute();
  }
}