<?php

namespace app\user\rules;

use Errors;
use system\classes\LinkBuilder;
use system\classes\Server;
use View;

class MustBeAdminRule
{
  public function apply()
  {
    if (Server::getSession('user')->status != 'admin') {
      View::setPopupMessage("Доступ запрещен", Errors::ERROR);
      LinkBuilder::redirect('dashboard');
    }
  }
}