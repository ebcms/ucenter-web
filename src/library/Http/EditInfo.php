<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use App\Ebcms\UcenterAdmin\Model\User;
use DigPHP\Router\Router;
use Psr\Http\Message\ResponseInterface;
use DigPHP\Form\Builder;
use DigPHP\Form\Component\Col;
use DigPHP\Form\Field\Cover;
use DigPHP\Form\Field\Input;
use DigPHP\Form\Field\Textarea;
use DigPHP\Form\Component\Row;
use DigPHP\Request\Request;

class EditInfo extends Common
{

    public function get(
        Router $router,
        User $userModel
    ) {
        $my = $userModel->get('*', [
            'id' => $userModel->getLoginId(),
        ]);
        $form = new Builder('修改个人信息');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-3'))->addItem(
                    (new Cover('头像', 'avatar', $my['avatar'], $router->build('/ebcms/ucenter-web/upload')))
                ),
                (new Col('col-md-9'))->addItem(
                    (new Input('昵称', 'nickname', $my['nickname'])),
                    (new Textarea('个人简介', 'introduction', $my['introduction']))
                )
            )
        );
        return $form;
    }

    public function post(
        User $userModel,
        Request $request
    ): ResponseInterface {
        $update = [];
        if ($request->has('post.introduction')) {
            $update['introduction'] = $request->post('introduction');
        }
        if ($request->has('post.avatar')) {
            $update['avatar'] = $request->post('avatar');
        }
        if (trim($request->post('nickname'))) {
            $update['nickname'] = mb_substr(trim($request->post('nickname')), 0, 8);
        }
        if ($update) {
            $userModel->update($update, [
                'id' => $userModel->getLoginId(),
            ]);
        }

        return $this->success('操作成功！', 'javascript:history.go(-2);');
    }
}
