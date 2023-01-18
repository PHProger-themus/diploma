<?php

namespace app\user\rules;

use Cfg;
use system\classes\LinkBuilder;
use View;
use system\classes\Server;

class MustBeLoggedOutRule
{
  public function apply()
  {
    if (Server::issetSession('loggedIn')) {
      LinkBuilder::redirect('dashboard');
    }
  }
}