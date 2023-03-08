<?php

namespace app\models;

use system\core\QueryBuilder;
use system\classes\ArrayHolder;
use system\core\Errors;
use system\core\View;

class Reserve extends QueryBuilder // Модель для работы с пользователем
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
      'company_name' => ['required'],
      'quantity' => ['required', 'isNumber'],
    ];
  }

  // Названия полей (для ошибок валидации)
  public function fields()
  {
    return [
      'product' => 'Товар',
      'quantity' => 'Наименование компании',
      'packed' => 'Упаковано',
    ];
  }

  // Таблица, с которой работает данная модель
  public function table()
  {
    return 'reserve';
  }

  /**
   * Получить список резервов
   * @param array $where [[field, value, sign?], [field, value, sign?], ...]
   * @return \app\dto\Reserve[]
   */
  public function get(array $where = []): array
  {
    $query = $this->all();
    foreach ($where as $condition) {
      $query->where($condition[0], $condition[1], $condition[2] ?? '=');
    }

    /** @var \app\dto\Reserve[] $rows */
    $rows = $query->getRows();

    foreach ($rows as &$row) {
      $productID = $row->product_id;
      $row->product = $this->all([], 'products')->where('ID', $productID)->getRow();
    }
    return $rows;
  }

  public function remove($id)
  {
    if (!empty($this->get([['ID', $id]]))) {
      $this->delete()->where('ID', $id)->execute();
      View::setPopupMessage("Запись удалена из резерва");
    } else {
      View::setPopupMessage("Записи с данным ID нет в резерве", Errors::ERROR);
    }
  }
}