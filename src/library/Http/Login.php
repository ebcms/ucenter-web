<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use App\Ebcms\Admin\Traits\ResponseTrait;
use App\Ebcms\Admin\Traits\RestfulTrait;
use App\Ebcms\UcenterAdmin\Model\Log;
use App\Ebcms\UcenterAdmin\Model\User;
use DigPHP\Framework\Config;
use DigPHP\Router\Router;
use DigPHP\Request\Request;
use DigPHP\Session\Session;
use DigPHP\Template\Template;

class Login
{

    use RestfulTrait;
    use ResponseTrait;

    public function get(
        Router $router,
        Request $request,
        Session $session,
        Config $config,
        Template $template
    ) {

        if ($config->get('auth.allow_login@ebcms.ucenter-web') != 1) {
            return $this->error('暂时关闭登陆！');
        }

        if ($request->get('redirect_uri')) {
            $session->set('login_redirect_uri', $request->get('redirect_uri'));
        }

        return $this->html($template->renderFromFile('login@ebcms/ucenter-web', [
            'router' => $router,
        ]));
    }

    public function post(
        Router $router,
        Request $request,
        User $userModel,
        Config $config,
        Log $log,
        Session $session
    ) {

        if ($config->get('auth.allow_login@ebcms.ucenter-web') != 1) {
            return $this->error('暂时关闭登陆！');
        }

        if (!$code = $request->post('code')) {
            return $this->error('请输入短信校验码！', '', 5);
        }

        if (!$verify_count = $session->get('verify_count')) {
            $session->delete('verify_count');
            $session->delete('verify_phone');
            $session->delete('verify_code');
            return $this->error('请重新获取校验码！', '', 0);
        }
        $session->set('verify_count', $verify_count - 1);

        if ($code != $session->get('verify_code')) {
            if ($verify_count - 1 <= 0) {
                $session->delete('verify_count');
                $session->delete('verify_phone');
                $session->delete('verify_code');
                return $this->error('短信校验码不正确！', '', 0);
            } else {
                return $this->error('短信校验码不正确！剩余' . ($verify_count - 1) . '次校验机会！', '', $verify_count - 1);
            }
        }

        if (!$phone = $session->get('verify_phone')) {
            $session->delete('verify_count');
            $session->delete('verify_phone');
            $session->delete('verify_code');
            return $this->error('非法操作！', '', 0);
        }

        if (!$user = $userModel->get('*', [
            'phone' => $phone,
        ])) {
            $userModel->insert([
                'phone' => $phone,
                'nickname' => '用户' . ($userModel->get('id', [
                    'ORDER' => [
                        'id' => 'DESC'
                    ]
                ]) + 1),
                'state' => $config->get('reg.default_state@ebcms.ucenter-web', 1),
                'salt' => md5(uniqid() . $phone),
            ]);
            $user = $userModel->get('*', [
                'phone' => $phone,
            ]);

            $log->record($user['id'], 'reg');
        }

        if ($user['state'] != 1) {
            $session->delete('verify_count');
            $session->delete('verify_phone');
            $session->delete('verify_code');
            return $this->error('该账户无法登陆！', '', 0);
        }

        $session->delete('verify_count');
        $session->delete('verify_phone');
        $session->delete('verify_code');

        $userModel->login($user['id']);

        if ($url = $session->get('login_redirect_uri')) {
            $session->delete('login_redirect_uri');
        } else {
            $url = $router->build('/ebcms/ucenter-web/index');
        }

        return $this->success('登陆成功！', $url);
    }
}
