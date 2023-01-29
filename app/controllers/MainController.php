<?php

namespace app\controllers;

use app\models\User;
use system\core\Controller;

class MainController extends Controller
{

  public function rules()
  {
    return [
      'MustBeLoggedIn' => [
        'dashboard',
        'logout',
      ],
      'MustBeLoggedOut' => [
        'index',
        'signup',
      ],
    ];
  }

  public function indexAction()
  {
    if ($this->isPost()) {
      $validator = new User($this->post());
      $validator->login();
    } else {
      $this->render();
    }
  }

  /*public function signupAction()
  {
  if ($this->isPost()) {
    $validator = new Client($this->post());
    $validator->signup();
  } else {
    $this->view->render();
  }
  }*/

  public function dashboardAction()
  {
    $this->render();
  }

  public function logoutAction()
  {
    (new User())->logout();
  }

}
