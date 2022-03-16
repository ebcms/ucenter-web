<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Admin\Model\Config as ModelConfig;
use DigPHP\Framework\Config as EbcmsConfig;
use DigPHP\Form\Builder;
use DigPHP\Form\Component\Col;
use DigPHP\Form\Field\Input;
use DigPHP\Form\Field\Radio;
use DigPHP\Form\Component\Switchs;
use DigPHP\Form\Component\Tab;
use DigPHP\Form\Component\Row;
use DigPHP\Form\Component\SwitchItem;
use DigPHP\Form\Component\TabItem;
use DigPHP\Request\Request;

class Config extends Common
{
    public function get(
        EbcmsConfig $config
    ) {
        $form = new Builder('用户设置 - WEB端');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Tab())->addTab(
                        (new TabItem('基本设置'))->addItem(
                            (new Radio('是否允许登陆', 'ebcms[ucenter-web][auth][allow_login]', $config->get('auth.allow_login@ebcms.ucenter-web'), [
                                '1' => '允许登陆',
                                '2' => '暂停登陆',
                            ])),
                            (new Input('自动登陆有效期', 'ebcms[ucenter-web][auth][expire_time]', $config->get('auth.expire_time@ebcms.ucenter-web', 0), 'number'))->set('help', '单位秒，在该时间内会自动登陆，推荐15天(1296000秒)')
                        ),
                        (new TabItem('短信设置'))->addItem(
                            (new Switchs('短信通道', 'ebcms[ucenter-web][sms][type]', $config->get('sms.type@ebcms.ucenter-web')))->addSwitch(
                                (new SwitchItem('腾讯通道', 'tencent'))->addItem(
                                    (new Input('secretId', 'ebcms[ucenter-web][tentcent][secretId]', $config->get('tentcent.secretId@ebcms.ucenter-web'))),
                                    (new Input('secretKey', 'ebcms[ucenter-web][tentcent][secretKey]', $config->get('tentcent.secretKey@ebcms.ucenter-web'))),
                                    (new Input('token', 'ebcms[ucenter-web][tentcent][token]', $config->get('tentcent.token@ebcms.ucenter-web'))),
                                    (new Input('TemplateID', 'ebcms[ucenter-web][sms][TemplateID]', $config->get('sms.TemplateID@ebcms.ucenter-web'))),
                                    (new Input('SmsSdkAppid', 'ebcms[ucenter-web][sms][SmsSdkAppid]', $config->get('sms.SmsSdkAppid@ebcms.ucenter-web'))),
                                    (new Input('Sign', 'ebcms[ucenter-web][sms][Sign]', $config->get('sms.Sign@ebcms.ucenter-web')))
                                )
                            )
                        )
                    )
                ),
                (new Col('col-md-3'))->addItem()
            )
        );
        return $form;
    }

    public function post(
        Request $request,
        ModelConfig $configModel
    ) {
        $configModel->save($request->post());
        return $this->success('更新成功！');
    }
}
