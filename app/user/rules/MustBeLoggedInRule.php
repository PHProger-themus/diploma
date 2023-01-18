<?php

namespace app\user\rules;

use Cfg;
use system\classes\LinkBuilder;
use View;
use system\classes\Server;
use Errors;

class MustBeLoggedInRule
{
  public function apply()
  {
    if (!Server::issetSession('loggedIn')) {
      View::setPopupMessage("Для доступа к этой странице нужно авторизоваться", Errors::ERROR);
      LinkBuilder::redirect('');
    }
  }
}