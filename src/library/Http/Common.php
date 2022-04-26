<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use App\Ebcms\Admin\Traits\ResponseTrait;
use App\Ebcms\Admin\Traits\RestfulTrait;
use App\Ebcms\UcenterWeb\Middleware\Auth;
use DiggPHP\Framework\Framework;

abstract class Common
{
    use RestfulTrait;
    use ResponseTrait;

    public function __construct()
    {
        Framework::bindMiddleware(Auth::class);
    }
}
