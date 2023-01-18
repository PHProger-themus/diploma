<?php

namespace app\user\rules;

use Cfg;
use system\classes\LinkBuilder;
use View;
use system\classes\Server;
use Errors;

class MustBeAdminRule
{
    public function apply() {
        if (Server::getSession('user')->status != 'admin') {
			View::setPopupMessage("Доступ запрещен", Errors::ERROR);
            LinkBuilder::redirect('dashboard');
        }
    }
}