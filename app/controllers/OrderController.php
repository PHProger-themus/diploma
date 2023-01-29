<?php

namespace app\controllers;

use app\models\Product;
use system\classes\ArrayHolder;
use system\classes\LinkBuilder;
use system\core\Controller;
use app\models\Order;
use View;

class OrderController extends Controller
{

  public function rules()
  {
    return [
      'MustBeLoggedIn' => ['*'],
    ];
  }

  public function indexAction()
  {
    $orders = new Order();
    $this->render([
      'orders' => $orders->get(),
    ]);
  }

  public function createAction()
  {
    $products = new Product();
    if ($this->isPost()) {
      $orders = new Order($this->post());
      $orders->create();
    } else {
      $this->render([
        'products' => [
          ArrayHolder::new(['ID' => 0, 'name' => 'Не выбран']),
          ...$products->get()
        ]
      ]);
    }
  }

  public function removeAction($vars)
  {
    (new Order())->remove($vars->id);
    View::setPopupMessage('Заказ удален');
    LinkBuilder::redirect('orders');
  }

}
