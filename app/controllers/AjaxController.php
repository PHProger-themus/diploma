<?php

namespace app\controllers;

use app\models\Ajax;
use system\core\Controller;

class AjaxController extends Controller
{
  private $model;

  public function __construct($COMMON)
  {
    parent::__construct($COMMON);
    $this->model = new Ajax();
  }

  public function clientsAction()
  {
    echo json_encode($this->model->getClients($this->get('keyword')));
  }

  public function productsAction()
  {
    echo json_encode($this->model->getProducts($this->get('keyword')));
  }
}
