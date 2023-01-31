<?php

namespace app\models\ajax;

use app\models\Product;
use Model;
use system\classes\SafetyManager;

class Ajax extends Model
{
  public function ajaxReserveFor($data)
  {
    if (!empty($data->productId) && !empty($data->company) && !empty($data->quantity)) {
      $product = new Product();
      $product->reserve(
        (int)$data->productId,
        SafetyManager::filterString($data->company),
        (int)$data->quantity
      );
    }
  }
}
