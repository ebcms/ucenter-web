<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use App\Ebcms\Admin\Traits\ResponseTrait;
use App\Ebcms\Admin\Traits\RestfulTrait;
use DigPHP\Request\Request;
use DigPHP\Session\Session;
use Ebcms\Framework\Config;
use Exception;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Sms\V20190711\SmsClient;
use TencentCloud\Sms\V20190711\Models\SendSmsRequest;
use Throwable;

class SendCode
{

    use RestfulTrait;
    use ResponseTrait;

    public function post(
        Config $config,
        Request $request,
        Session $session
    ) {
        $captcha = $request->post('captcha');
        if (!$captcha || $captcha != $session->get('ucenter_auth_captcha')) {
            return $this->error('验证码不正确！');
        }
        $session->delete('ucenter_auth_captcha');

        if ($config->get('auth.allow_login@ebcms.ucenter-web') != 1) {
            return $this->error('暂时关闭登陆！');
        }

        $code = (string)random_int(100000, 999999);
        $phone = $request->post('phone');

        try {
            switch ($config->get('sms.type@ebcms/ucenter-web')) {
                case 'tencent':
                    $cred = new Credential($config->get('tentcent.secretId@ebcms/ucenter-web', ''), $config->get('tentcent.secretKey@ebcms/ucenter-web', ''), $config->get('tentcent.token@ebcms/ucenter-web', null));
                    $httpProfile = new HttpProfile();
                    $httpProfile->setEndpoint('sms.tencentcloudapi.com');

                    $clientProfile = new ClientProfile();
                    $clientProfile->setHttpProfile($httpProfile);
                    $client = new SmsClient($cred, "", $clientProfile);

                    $req = new SendSmsRequest();

                    $params = array(
                        'PhoneNumberSet' => ['+86' . $phone],
                        'TemplateID' => $config->get('sms.TemplateID@ebcms/ucenter-web'),
                        'SmsSdkAppid' => $config->get('sms.SmsSdkAppid@ebcms/ucenter-web'),
                        'TemplateParamSet' => [$code],
                        'Sign' => $config->get('sms.Sign@ebcms/ucenter-web')
                    );
                    $req->fromJsonString(json_encode($params));

                    $resp = json_decode($client->SendSms($req)->toJsonString(), true);

                    if ($resp['SendStatusSet'][0]['Code'] == 'Ok') {
                        $session->set('verify_count', 5);
                        $session->set('verify_phone', $phone);
                        $session->set('verify_code', $code);
                        return $this->success('校验码发送成功！');
                    } else {
                        throw new Exception($resp['SendStatusSet'][0]['Code']);
                    }
                    break;

                default:
                    throw new Exception('未配置短信通道或者通道配置不正确~');
                    break;
            }
        } catch (Throwable $e) {
            return $this->error('发送失败:' . $e->getMessage());
        }
    }
}
