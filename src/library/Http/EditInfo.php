<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use App\Ebcms\UcenterWeb\Model\User;
use DigPHP\Database\Db;
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
        Db $db,
        User $userModel
    ) {
        $my = $db->get('ebcms_user_user', '*', [
            'id' => $userModel->getUserId(),
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
        Db $db,
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
            $db->update('ebcms_user_user', $update, [
                'id' => $userModel->getUserId(),
            ]);
        }

        return $this->success('操作成功！');
    }
}
