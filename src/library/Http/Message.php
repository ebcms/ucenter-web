<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use App\Ebcms\UcenterWeb\Model\User;
use DigPHP\Database\Db;
use DigPHP\Pagination\Pagination;
use DigPHP\Request\Request;
use DigPHP\Template\Template;

class Message extends Common
{
    public function get(
        Db $db,
        User $userModel,
        Request $request,
        Template $template,
        Pagination $pagination
    ) {
        $where = [];
        $where['user_id'] = $userModel->getUserId();
        if (strlen($request->get('q', ''))) {
            $where['OR'] = [
                'title[~]' => $request->get('q'),
                'body[~]' => $request->get('q'),
            ];
        }
        if (strlen($request->get('is_read', ''))) {
            $where['is_read'] = $request->get('is_read');
        }
        $total = $db->count('ebcms_user_message', $where);

        $page = $request->get('page') ?: 1;
        $pagenum = 20;
        $where['LIMIT'] = [($page - 1) * $pagenum, $pagenum];
        $where['ORDER'] = [
            'id' => 'DESC',
        ];

        $messages = $db->select('ebcms_user_message', '*', $where);

        return $template->renderFromFile('message@ebcms/ucenter-web', [
            'messages' => $messages,
            'total' => $total,
            'pages' => $pagination->render($page, $total, $pagenum),
        ]);
    }

    public function post(
        Db $db,
        User $userModel,
        Request $request
    ) {
        $db->update('ebcms_user_message', [
            'is_read' => 1,
            'read_time' => time(),
        ], [
            'user_id' => $userModel->getUserId(),
            'id' => $request->post('id'),
        ]);
        return $this->success('操作成功~');
    }

    public function delete(
        Db $db,
        User $userModel,
        Request $request
    ) {
        $db->delete('ebcms_user_message', [
            'user_id' => $userModel->getUserId(),
            'id' => explode(',', $request->get('id')),
        ]);
        return $this->success('操作成功~');
    }
}
