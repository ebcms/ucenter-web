<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use App\Ebcms\Admin\Traits\ResponseTrait;
use App\Ebcms\Admin\Traits\RestfulTrait;
use App\Ebcms\UcenterWeb\Model\User;
use DiggPHP\Database\Db;
use DiggPHP\Router\Router;
use DiggPHP\Request\Request;
use DiggPHP\Session\Session;
use DiggPHP\Template\Template;
use DiggPHP\Framework\Config;

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
        Db $db,
        Config $config,
        Session $session
    ) {

        if ($config->get('auth.allow_login@ebcms.ucenter-web') != 1) {
            return $this->error('暂时关闭登陆！');
        }

        if (!$code = $request->post('code')) {
            return $this->error('请输入短信校验码！');
        }

        if (!$verify_count = $session->get('verify_count')) {
            $session->delete('verify_count');
            $session->delete('verify_phone');
            $session->delete('verify_code');
            return $this->error('请重新获取校验码！');
        }
        $session->set('verify_count', $verify_count - 1);

        if ($code != $session->get('verify_code')) {
            if ($verify_count - 1 <= 0) {
                $session->delete('verify_count');
                $session->delete('verify_phone');
                $session->delete('verify_code');
                return $this->error('短信校验码不正确！');
            } else {
                return $this->error('短信校验码不正确！剩余' . ($verify_count - 1) . '次校验机会！', null, 2);
            }
        }

        if (!$phone = $session->get('verify_phone')) {
            $session->delete('verify_count');
            $session->delete('verify_phone');
            $session->delete('verify_code');
            return $this->error('非法操作！');
        }

        if (!$user = $db->get('ebcms_user_user', '*', [
            'phone' => $phone,
        ])) {
            $db->insert('ebcms_user_user', [
                'phone' => $phone,
                'nickname' => '用户' . ($db->get('ebcms_user_user', 'id', [
                    'ORDER' => [
                        'id' => 'DESC'
                    ]
                ]) + 1),
                'state' => $config->get('reg.default_state@ebcms.ucenter-web', 1),
                'salt' => md5(uniqid() . $phone),
            ]);
            $user = $db->get('ebcms_user_user', '*', [
                'phone' => $phone,
            ]);

            $db->insert('ebcms_user_message', [
                'user_id' => 1,
                'title' => '用户协议',
                'body' => $config->get('auth.policy@ebcms.ucenter-web'),
                'send_time' => time(),
            ]);
        }

        if ($user['state'] != 1) {
            $session->delete('verify_count');
            $session->delete('verify_phone');
            $session->delete('verify_code');
            return $this->error('该账户无法登陆！');
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

        $db->insert('ebcms_user_message', [
            'user_id' => $user['id'],
            'title' => '登录提醒',
            'body' => '您的账户刚刚登录了系统~',
        ]);

        return $this->success('登陆成功！', null, $url);
    }
}
