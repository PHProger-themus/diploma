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
      'name' => ['required'],
      'to_client' => ['required' => ['if' => function () {
        return true;
      }]],
    ];
  }

  // Названия полей (для ошибок валидации)
  public function fields()
  {
    return [
      'name' => 'Название',
      'to_client' => 'Заказчику',
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
      $row->products = $this->all(['products.*', 'products_to_order.quantity'], 'products_to_order')
        ->join('products', ['products_to_order.product_id' => 'products.ID'])
        ->where('order_id', $row->ID)
        ->getRows();
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
      $products = $this->cleanForm->products;
      $this->cleanOutForm('products');
      // Заказ
      $this->insert($this->cleanForm);
      // Товары
      $orderId = $this->getLastInsertId();
      foreach ($products as $product) {
        $this->insert(array_merge(['order_id' => $orderId], $product), 'products_to_order');
      }
      LinkBuilder::redirect('orders');
    } else {
      $this->backWithError(implode(', ', $this->getErrors()));
    }
  }

  public function edit(int $id)
  {
    if ($this->correct()) {
      $this->processPost();
      $products = $this->cleanForm->products;
      $this->cleanOutForm('products');
      // Заказ
      $this->update($this->cleanForm)->where('ID', $id)->execute();
      // Товары
      $this->delete('products_to_order')->where('order_id', $id)->execute();
      foreach ($products as $product) {
        $this->insert(array_merge(['order_id' => $id], $product), 'products_to_order');
      }
      LinkBuilder::redirect('orders');
    } else {
      $this->backWithError(implode(', ', $this->getErrors()));
    }
  }

  private function processPost()
  {
    // Товары
    if (!count($this->cleanForm->products)) {
      $this->backWithError('Поле "Товары" является обязательным');
    }
    if (count($this->cleanForm->products) == count($this->cleanForm->quantity)) {
      $products = [];
      for ($i = 0; $i < count($this->cleanForm->products); ++$i) {
        if ((int)$this->cleanForm->products[$i] < 1 || (int)$this->cleanForm->quantity[$i] < 1) {
          $this->backWithError('Ошибка при составлении списка товаров. Идентификатор товара или его количество не могут быть меньше 1');
        } elseif (!$this->all(['ID'], 'products')->where('ID', $this->cleanForm->products[$i])->exists()) {
          $this->backWithError('Ошибка при составлении списка товаров. Указан несуществующий идентификатор товара');
        } else {
          $products[] = [
            'product_id' => (int)$this->cleanForm->products[$i],
            'quantity' => (int)$this->cleanForm->quantity[$i]
          ];
        }
      }
      $this->cleanForm->products = $products;
    } else {
      $this->backWithError('Ошибка при составлении списка товаров. Списки идентификаторов и количества различны');
    }

    // Упакован
    $this->cleanForm->packed = $this->cleanForm->packed ?: null;

    // Заказчик / склад
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
      $this->cleanForm->client_id = null;
    }

    $this->cleanOutForm('quantity', 'to_warehouse', 'to_client');
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

  private function cleanOutForm(...$fields) {
    foreach($fields as $field) {
      unset($this->cleanForm->$field);
    }
  }
}