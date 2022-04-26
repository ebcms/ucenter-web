<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use App\Ebcms\UcenterWeb\Model\User;
use DiggPHP\Router\Router;

class Logout extends Common
{

    public function get(
        Router $router,
        User $userModel
    ) {
        $userModel->logout();
        return $this->success('已退出！', null, $router->build('/ebcms/ucenter-web/index'));
    }
}
