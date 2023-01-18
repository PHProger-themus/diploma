<?php

namespace app\user\rules;

use system\classes\LinkBuilder;
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