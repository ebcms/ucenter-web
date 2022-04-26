<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use App\Ebcms\Admin\Traits\ResponseTrait;
use App\Ebcms\Admin\Traits\RestfulTrait;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use DiggPHP\Session\Session;
use Gregwar\Captcha\CaptchaBuilder;

class Captcha
{
    use RestfulTrait;
    use ResponseTrait;

    public function get(
        Session $session,
        ResponseFactoryInterface $responseFactory,
        CaptchaBuilder $builder
    ): ResponseInterface {
        $response = $responseFactory->createResponse(200);

        $session->set('ucenter_auth_captcha', strtolower($builder->getPhrase()));
        $response->getBody()->write($builder->build()->get());

        return $response->withHeader('Content-Type', 'image/png');
    }
}
