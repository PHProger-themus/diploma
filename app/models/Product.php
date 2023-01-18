<?php

namespace app\models;

use QueryBuilder;
use system\classes\ArrayHolder;

class Product extends QueryBuilder // Модель для работы с пользователем
{

  private $form;

  public function __construct(ArrayHolder $data = NULL)
  {
    if (!is_null($data)) {
      $this->form = $data;
    }
    parent::__construct($data);
  }

  public function rules() // Правила валидации. Логин и пароль - обязательные поля, минимум 3 символа
  {
    return [
      'name' => ['required'],
      'price' => ['required', 'isNumber'],
    ];
  }

  public function fields() // Названия полей (для ошибок валидации)
  {
    return [
      'name' => 'Наименование',
      'price' => 'Закупочная цена',
      'quantity' => 'Кол-во на складе',
    ];
  }

  public function table() // Таблица, с которой работает данная модель
  {
    return 'products';
  }

  private function backWithError(string $error)
  {
    View::setPopupMessage($error, Errors::ERROR); // Создаем окно с ошибкой и отправляем пользователя на форму
    LinkBuilder::redirect('');
  }

}