<?php

namespace app\controllers;

use app\models\ajax\Ajax;
use system\core\Controller;
use system\core\Errors;

class AjaxController extends Controller
{
  public function indexAction()
  {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')) {
      $ajax = new Ajax();
      if ($this->isPost() && $this->issetPost('action')) {
        $method = 'ajax' . ucfirst($this->post('action'));
        $ajax->$method($this->post());
      } elseif ($this->isGet() && $this->issetGet('action')) {
        $method = 'ajax' . ucfirst($this->get('action'));
        $ajax->$method($this->post());
      } else {
        Errors::code(400, false);
      }
    } else {
      Errors::code(404);
    }
  }
}
