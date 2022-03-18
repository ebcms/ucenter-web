<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use App\Ebcms\UcenterWeb\Model\User;
use DigPHP\Database\Db;
use DigPHP\Template\Template;

class Home extends Common
{

    public function get(
        Db $db,
        User $userModel,
        Template $template
    ) {
        return $this->html($template->renderFromFile('home@ebcms/ucenter-web', [
            'my' => $db->get('ebcms_user_user', '*', [
                'id' => $userModel->getUserId()
            ]),
        ]));
    }
}
