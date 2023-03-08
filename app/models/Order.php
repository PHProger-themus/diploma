<?php

namespace app\models;

use system\core\QueryBuilder;
use system\classes\ArrayHolder;
use system\classes\LinkBuilder;
use system\core\Errors;
use system\core\View;
use app\dto\Client;

class Order extends QueryBuilder // Модель для работы с пользователем
{

  private ArrayHolder $form;
  /** @var mixed */
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
      'to_client' => ['required' => ['if' => function () {
        return true;
      }]],
      'product' => ['required', 'exists' => ['values' => [$this, 'products', 'ID']]],
      'quantity' => ['required', 'isNumber'],
    ];
  }

  // Названия полей (для ошибок валидации)
  public function fields()
  {
    return [
      'to_client' => 'Заказчику',
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
      $row->product = $this->all([], 'products')->where('ID', $row->product_id)->getRow();
      $row->client = $row->client_id ? $this->all([], 'clients')->where('ID', $row->client_id)->getRow() : NULL;
      if ($row->client) {
        $row->client->name = htmlspecialchars_decode(htmlspecialchars_decode($row->client->name));
      }
    }
    return $rows;
  }

  public function create()
  {
    if ($this->correct()) {
      $this->processPost();
      $this->insert($this->cleanForm);
      LinkBuilder::redirect('orders');
    } else {
      $this->backWithError(implode(', ', $this->getErrors()));
    }
  }

  public function edit(int $id)
  {
    if ($this->correct()) {
      $this->processPost();
      $this->update($this->cleanForm)->where('ID', $id)->execute();
      LinkBuilder::redirect('orders');
    } else {
      $this->backWithError(implode(', ', $this->getErrors()));
    }
  }

  private function processPost()
  {
    $this->cleanForm->product_id = (int)$this->cleanForm->product;
    unset($this->cleanForm->product);
    $this->cleanForm->packed = $this->cleanForm->packed ?: null;
    if (!$this->cleanForm->to_warehouse) {
      /** @var Client */
      $client = $this->all([], 'clients')->where('name', $this->cleanForm->to_client)->getRow();
      if (!$client) {
        $this->insert(['name' => $this->cleanForm->to_client], 'clients');
        $this->cleanForm->client_id = $this->getLastInsertId();
      } else {
        $this->cleanForm->client_id = $client->id;
      }
    } else {
      $this->cleanForm->client_id = NULL;
    }
    unset($this->cleanForm->to_warehouse);
    unset($this->cleanForm->to_client);
  }

  public function remove($id)
  {
    if (!empty($this->get([['ID', $id]]))) {
      $this->delete()->where('ID', $id)->execute();
      View::setPopupMessage("Заказ удален");
    } else {
      View::setPopupMessage("Заказа с данным ID не существует", Errors::ERROR);
    }
  }
}