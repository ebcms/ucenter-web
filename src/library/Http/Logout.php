<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use App\Ebcms\UcenterAdmin\Model\User;
use DigPHP\Router\Router;

class Logout extends Common
{

    public function get(
        Router $router,
        User $userModel
    ) {
        $userModel->logout();
        return $this->success('已退出！', $router->build('/ebcms/ucenter-web/login'));
    }
}
