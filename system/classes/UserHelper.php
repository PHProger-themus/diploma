<?php

namespace system\classes;

use QueryBuilder;
use system\interfaces\ModelInterface;

class UserHelper extends QueryBuilder
{

  public static function signupUser(ModelInterface $model, array $columns, string $table = NULL)
  {
    $insertData = [];
    foreach ($columns as $column) {
      if ($column == 'password') $model->data->post->$column = SafetyManager::encryptPassword($model->data->post->$column);
      $insertData[$column] = $model->data->post->$column;
    }
    if (is_null($table)) $table = $model->table();
    $model->insert($insertData, $table);
  }

  public function table()
  {
    return 'users';
  }

  public static function signinUser(ModelInterface $model, array $columns, string $table = NULL)
  {
    $model->get($table);
    $started = false;
    foreach ($columns as $column) {
      if ($column != 'password') {
        if (!$started) {
          $started = true;
        } else {
          $model->add(QueryBuilder::DB_AND);
        }
        $model->where($column, '=', $model->data->post->$column);
      }
    }
    $rows = $model->execute(true);
    if (count($rows) == 0 || !SafetyManager::checkPassword($model->data->post->password, $rows[0]->password)) {
      return "Данная запись не найдена";
    } else {
      $userInfo = [];
      foreach ($rows[0] as $row => $value) {
        $userInfo[$row] = $value;
      }
      Server::setSession(['userSigned' => true, 'userInfo' => $userInfo]);
    }
  }

  public static function getUserInfo()
  {
    $userSigned = Server::issetSession('userSigned');
    $userInfo = ['userSigned' => $userSigned ? true : false];
    if ($userSigned) {
      $userInfo['userInfo'] = Server::getSession('userInfo');
    }
    return ArrayHolder::new($userInfo);
  }

  public static function logout(string $redirectUrl = '/')
  {
    if (Server::issetSession('userSigned')) {
      Server::unsetSession(['userSigned', 'userInfo']);
    }
    LinkBuilder::redirect($redirectUrl);
  }

}