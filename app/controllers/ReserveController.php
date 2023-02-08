<?php

namespace app\controllers;

use app\models\Reserve;
use system\classes\LinkBuilder;
use system\core\Controller;

class ReserveController extends Controller
{

  public function rules()
  {
    return [
      'MustBeLoggedIn' => ['*'],
    ];
  }

  public function removeAction($vars)
  {
    (new Reserve())->remove($vars->id);
    LinkBuilder::redirect('products');
  }

}
