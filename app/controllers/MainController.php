<?php

namespace app\controllers;

use app\models\Product;
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
    $products = new Product();
    $this->view->render([
      'products' => $products->get(),
    ]);
  }

  public function logoutAction()
  {
    (new User())->logout();
  }

}
