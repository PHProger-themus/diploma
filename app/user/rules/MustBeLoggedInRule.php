<?php

namespace app\user\rules;

use Errors;
use system\classes\LinkBuilder;
use system\classes\Server;
use View;

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