<?php

namespace app\content;

use system\core\Content;

class ReserveModal extends Content
{
  public function init()
  {

    $this->render(['productId' => $this->productId]);
  }
}
