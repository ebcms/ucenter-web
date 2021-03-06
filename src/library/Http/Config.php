<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Admin\Model\Config as ModelConfig;
use DiggPHP\Form\Builder;
use DiggPHP\Form\Component\Col;
use DiggPHP\Form\Component\Row;
use DiggPHP\Form\Field\Input;
use DiggPHP\Form\Field\Radio;
use DiggPHP\Form\Field\Summernote;
use DiggPHP\Request\Request;
use DiggPHP\Router\Router;
use DiggPHP\Framework\Config as EbcmsConfig;

class Config extends Common
{
    public function get(
        Router $router,
        EbcmsConfig $config
    ) {
        $form = new Builder('网页登录设置');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    new Radio('是否允许登陆', 'ebcms[ucenter-web][auth][allow_login]', $config->get('auth.allow_login@ebcms.ucenter-web'), [
                        '1' => '允许登陆',
                        '2' => '暂停登陆',
                    ]),
                    new Input('自动登陆有效期', 'ebcms[ucenter-web][auth][expire_time]', $config->get('auth.expire_time@ebcms.ucenter-web', 0), [
                        'help' => '单位秒，在该时间内会自动登陆，推荐15天(1296000秒)'
                    ]),
                    new Summernote('用户协议', 'ebcms[ucenter-web][auth][policy]', $config->get('auth.policy@ebcms.ucenter-web'), $router->build('/ebcms/admin/upload'))
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
