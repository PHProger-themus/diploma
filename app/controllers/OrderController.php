<?php

namespace app\controllers;

use app\models\Product;
use system\classes\ArrayHolder;
use system\classes\LinkBuilder;
use system\core\Controller;
use app\models\Order;

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
        ],
        'getProduct' => $this->get('product')
      ]);
    }
  }

  public function updateAction($vars)
  {
    $products = new Product();
    if ($this->isPost()) {
      $orders = new Order($this->post());
      $orders->edit($vars->id);
    } else {
      $orders = new Order();
      $order = $orders->get([['ID', $vars->id]]);
      if (!empty($order)) {
        $order = $order[0];
        $this->render([
          'products' => [
            ArrayHolder::new(['ID' => 0, 'name' => 'Не выбран']),
            ...$products->get()
          ],
          'order' => $order
        ]);
      } else {
        $orders->backWithError('Заказа с данным ID не существует', LinkBuilder::url('order', 'index'));
      }
    }
  }

  public function removeAction($vars)
  {
    (new Order())->remove($vars->id);
    LinkBuilder::redirect('orders');
  }

}
