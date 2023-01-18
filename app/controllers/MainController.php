<?php

namespace app\controllers;

use system\core\Controller;
use app\models\User;

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
      $this->view->render();
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
    $this->view->render();
  }

  public function productsAction()
  {
    $this->view->render();
  }

  public function logoutAction()
  {
    (new User())->logout();
  }

}
