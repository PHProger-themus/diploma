<?php

namespace app\controllers;

use system\core\Controller;
use app\models\Product;

class ProductController extends Controller
{

  public function rules()
  {
    return [
      'MustBeLoggedIn' => ['*'],
    ];
  }

  public function indexAction()
  {
    $products = new Product();
    $this->render([
      'products' => $products->get(),
    ]);
  }

  public function createAction()
  {
    $products = new Product();
    $this->render([
      'products' => $products->get(),
    ]);
  }

}
