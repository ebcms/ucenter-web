<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use App\Ebcms\UcenterAdmin\Model\User;
use DigPHP\Template\Template;

class Home extends Common
{

    public function get(
        User $userModel,
        Template $template
    ) {
        return $this->html($template->renderFromFile('home@ebcms/ucenter-web', [
            'my' => $userModel->get('*', [
                'id' => $userModel->getLoginId()
            ]),
        ]));
    }
}
