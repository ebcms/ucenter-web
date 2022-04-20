<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Middleware;

use App\Ebcms\Admin\Traits\ResponseTrait;
use App\Ebcms\UcenterWeb\Model\User;
use DigPHP\Router\Router;
use Ebcms\Framework\Framework;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Auth implements MiddlewareInterface
{
    use ResponseTrait;

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        return Framework::execute(function (
            User $userModel,
            Router $router
        ) use ($request, $handler): ResponseInterface {
            if (!$userModel->getUserId()) {
                return $this->error('请登陆！', $router->build('/ebcms/ucenter-web/login', [
                    'redirect_uri' => $this->getRedirectUri()
                ]));
            }
            return $handler->handle($request);
        });
    }

    private function getRedirectUri(): string
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            return $_SERVER['REQUEST_URI'];
        } else {
            return $_SERVER['HTTP_REFERER'] ?? '';
        }
    }
}
