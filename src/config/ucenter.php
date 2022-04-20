<?php

use App\Ebcms\UcenterWeb\Model\User;
use DigPHP\Database\Db;
use DigPHP\Router\Router;
use Ebcms\Framework\Framework;

return [
    'menus' => Framework::execute(function (
        User $userModel,
        Db $db,
        Router $router
    ): array {
        $res = [];
        $res[] = [
            'title' => '主页',
            'url' => $router->build('/ebcms/ucenter-web/home'),
            'icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg t="1608973120330" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="46743" width="20" height="20"><path d="M460.8 344h299.2v64H460.8zM348.8 312c-35.2 0-64 28.8-64 64s28.8 64 64 64 64-28.8 64-64c0-36.8-28.8-64-64-64zM286.4 518.4h299.2v64H286.4zM697.6 486.4c-35.2 0-64 28.8-64 64s28.8 64 64 64 64-28.8 64-64c0-36.8-28.8-64-64-64z" fill="#FC7265" p-id="46744"></path><path d="M849.6 840H188.8c-72 0-131.2-52.8-131.2-118.4V203.2c0-65.6 59.2-118.4 131.2-118.4h660.8c72 0 131.2 52.8 131.2 118.4V720c0 65.6-59.2 120-131.2 120zM188.8 148.8c-36.8 0-67.2 25.6-67.2 54.4v518.4c0 30.4 30.4 54.4 67.2 54.4h660.8c36.8 0 67.2-25.6 67.2-54.4V203.2c0-30.4-30.4-54.4-67.2-54.4H188.8z" fill="#1F91F2" p-id="46745"></path><path d="M486.4 784h64v156.8h-64z" fill="#1F91F2" p-id="46746"></path><path d="M334.4 896H704v64H334.4z" fill="#1F91F2" p-id="46747"></path></svg>'),
            'priority' => 100,
        ];
        $res[] = [
            'title' => '消息',
            'url' => $router->build('/ebcms/ucenter-web/message'),
            'tips' => $db->get('ebcms_user_message', '*', [
                'user_id' => $userModel->getUserId(),
                'is_read' => 0,
            ]) ? '有未读消息' : '',
            'icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="1024" height="1024" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" xmlns:se="http://svg-edit.googlecode.com" class="icon"><g class="layer"><title>Layer 1</title><g id="svg_3"><path class="selected" d="m76.21947,230.9546c1.34507,1.5599 2.75222,3.05097 4.24213,4.51911l338.74956,333.77227a139.67987,154.8428 0 0 0 185.57766,0l338.74956,-333.77227c1.48992,-1.46814 2.89706,-2.95922 4.22143,-4.49618c0.49664,3.99151 0.74496,8.07477 0.74496,12.22685l0,537.56833a82.77326,91.75869 0 0 1 -82.77326,91.75869l-707.46305,0a82.77326,91.75869 0 0 1 -82.77326,-91.75869l0,-537.56833c0,-3.07391 0.14485,-6.10196 0.41387,-9.10704l0.31039,-3.14274z" data-spm-anchor-id="a313x.7781069.0.i0" fill="#279CFF" id="svg_1"/><path d="m566.98213,476.75321l330.16184,-325.28459l-770.28795,0l330.16184,325.28459a82.77326,91.75869 0 0 0 109.96427,0z" data-spm-anchor-id="a313x.7781069.0.i1" fill="#279CFF" fill-opacity="0.5" id="svg_2"/></g></g></svg>'),
            'priority' => 99,
        ];
        return $res;
    })
];
