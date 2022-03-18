<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Model;

use App\Ebcms\UcenterAdmin\Model\Log;
use DigPHP\Database\Db;
use DigPHP\Framework\Config;
use DigPHP\Session\Session;

class User
{

    private $db;
    private $session;
    private $config;
    private $log;

    public function __construct(
        Db $db,
        Session $session,
        Config $config,
        Log $log
    ) {
        $this->db = $db;
        $this->session = $session;
        $this->config = $config;
        $this->log = $log;
    }

    public function login(int $uid): bool
    {
        $this->log->record($uid, 'login');
        $this->session->set('ucenter_user_id', $uid);
        setcookie($this->getTokenKey(), $this->makeToken($uid), time() + (int)$this->config->get('auth.expire_time@ebcms.ucenter-web', 0), '/');
        return true;
    }

    public function logout(): bool
    {
        $this->log->record($this->getUserId(), 'logout');
        setcookie($this->getTokenKey(), '', time() - 3600, '/');
        $this->session->delete('ucenter_user_id');
        return true;
    }

    public function getUserId(): int
    {
        if ($uid = $this->session->get('ucenter_user_id')) {
            return (int)$uid;
        }

        if ($uid = $this->autoLogin()) {
            return (int)$uid;
        }

        return 0;
    }

    private function autoLogin(): int
    {
        if (isset($_COOKIE[$this->getTokenKey()])) {
            $token = $_COOKIE[$this->getTokenKey()];

            $tmp = array_filter(explode('_', $token));
            if (count($tmp) == 2) {
                $uid = (int)$tmp[0];
                $code = $tmp[1];
                if ($user = $this->db->get('ebcms_user_user', '*', [
                    'id' => $uid,
                ])) {
                    if (md5($user['salt'] . '_' . $uid) == $code) {
                        $this->login($uid);
                        return $uid;
                    }
                }
            }
        }
        return 0;
    }

    private function makeToken(int $uid): string
    {
        if ($user = $this->db->get('ebcms_user_user', '*', [
            'id' => $uid,
        ])) {
            return $uid . '_' . md5($user['salt'] . '_' . $uid);
        }
        return '';
    }

    private function getTokenKey(): string
    {
        return md5($_SERVER['HTTP_USER_AGENT']);
    }
}
