<?php

namespace app\models;

use Errors;
use QueryBuilder;
use system\classes\ArrayHolder;
use system\classes\LinkBuilder;
use system\classes\SafetyManager;
use system\classes\Server;
use View;

class User extends QueryBuilder // Модель для работы с пользователем
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
      'login' => ['required', 'rangeFrom' => ['values' => 3]],
      'password' => ['required', 'rangeFrom' => ['values' => 3]],
    ];
  }

  public function fields() // Названия полей (для ошибок валидации)
  {
    return [
      'login' => 'Логин',
      'password' => 'Пароль',
      'name' => 'Имя',
      'lastname' => 'Фамилия',
      'middlename' => 'Отчество',
      'last_active' => 'Последняя активность',
    ];
  }

  public function table() // Таблица, с которой работает данная модель
  {
    return 'users';
  }

  public function login() // Когда пользователь нажимает "Войти"
  {
    if ($this->correct()) { // Если формат ввода соблюден
      $user = $this->all()->where('login', $this->getField('login'))->getRow();
      if (!empty($user) && SafetyManager::checkPassword($this->getField('password'), $user->password)) {
        Server::setSession(['loggedIn' => true, 'user' => $user]); // Иначе установим сессию "Авторизован" и поместим туда все данные о пользователе
        LinkBuilder::redirect('dashboard'); // Отправим пользователя на "Главную"
      } else {
        $this->saveForm(); // Сохраняем данные формы
        $this->backWithError("Неверное имя пользователя или пароль"); // Возвращаемся к форме и выдаем ошибку
      }
    } else {
      $this->backWithError(implode(', ', $this->getErrors())); // Если формат ввода не соблюден, выдаем ошибки валидации
    }
  }

  public function logout() // Когда пользователь нажимает "Выход"
  {
    if (Server::issetSession('loggedIn')) { // Если он авторизован
      $this->update(['last_active' => date('Y-m-d H:i:s')])->where('id', Server::getSession('user')->id)->execute(); // Запишем в бд дату последней активности пользователя
      Server::unsetSession(['loggedIn', 'user']);
      LinkBuilder::redirect(''); // Выход и перенаправление на форму
    }
  }

}