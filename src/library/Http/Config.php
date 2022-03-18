<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Admin\Model\Config as ModelConfig;
use DigPHP\Form\Builder;
use DigPHP\Form\Component\Col;
use DigPHP\Form\Component\Row;
use DigPHP\Form\Field\Input;
use DigPHP\Form\Field\Radio;
use DigPHP\Framework\Config as EbcmsConfig;
use DigPHP\Request\Request;

class Config extends Common
{
    public function get(
        EbcmsConfig $config
    ) {
        $form = new Builder('网页登录设置');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Radio('是否允许登陆', 'ebcms[ucenter-web][auth][allow_login]', $config->get('auth.allow_login@ebcms.ucenter-web'), [
                        '1' => '允许登陆',
                        '2' => '暂停登陆',
                    ])),
                    (new Input('自动登陆有效期', 'ebcms[ucenter-web][auth][expire_time]', $config->get('auth.expire_time@ebcms.ucenter-web', 0)))->set('help', '单位秒，在该时间内会自动登陆，推荐15天(1296000秒)')
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
