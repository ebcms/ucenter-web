<?php

use App\Ebcms\UcenterWeb\Model\User;
use DigPHP\Database\Db;
use DigPHP\Framework\Framework;
use DigPHP\Router\Router;

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
        if ($db->get('ebcms_user_message', '*', [
            'user_id' => $userModel->getUserId(),
            'is_read' => 0,
        ])) {
            $res[] = [
                'title' => '消息',
                'tips' => '有未读消息',
                'url' => $router->build('/ebcms/ucenter-web/message'),
                'icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="1024" height="1024" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" xmlns:se="http://svg-edit.googlecode.com" class="icon"><g class="layer"><title>Layer 1</title><g id="svg_3"><path class="selected" d="m76.21947,230.9546c1.34507,1.5599 2.75222,3.05097 4.24213,4.51911l338.74956,333.77227a139.67987,154.8428 0 0 0 185.57766,0l338.74956,-333.77227c1.48992,-1.46814 2.89706,-2.95922 4.22143,-4.49618c0.49664,3.99151 0.74496,8.07477 0.74496,12.22685l0,537.56833a82.77326,91.75869 0 0 1 -82.77326,91.75869l-707.46305,0a82.77326,91.75869 0 0 1 -82.77326,-91.75869l0,-537.56833c0,-3.07391 0.14485,-6.10196 0.41387,-9.10704l0.31039,-3.14274z" data-spm-anchor-id="a313x.7781069.0.i0" fill="#279CFF" id="svg_1"/><path d="m566.98213,476.75321l330.16184,-325.28459l-770.28795,0l330.16184,325.28459a82.77326,91.75869 0 0 0 109.96427,0z" data-spm-anchor-id="a313x.7781069.0.i1" fill="#279CFF" fill-opacity="0.5" id="svg_2"/></g></g></svg>'),
                'priority' => 99,
            ];
        } else {
            $res[] = [
                'title' => '消息',
                'url' => $router->build('/ebcms/ucenter-web/message'),
                'icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="1024" height="1024" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" xmlns:se="http://svg-edit.googlecode.com" class="icon"><g class="layer"><title>Layer 1</title><g id="svg_3"><path class="selected" d="m76.21947,230.9546c1.34507,1.5599 2.75222,3.05097 4.24213,4.51911l338.74956,333.77227a139.67987,154.8428 0 0 0 185.57766,0l338.74956,-333.77227c1.48992,-1.46814 2.89706,-2.95922 4.22143,-4.49618c0.49664,3.99151 0.74496,8.07477 0.74496,12.22685l0,537.56833a82.77326,91.75869 0 0 1 -82.77326,91.75869l-707.46305,0a82.77326,91.75869 0 0 1 -82.77326,-91.75869l0,-537.56833c0,-3.07391 0.14485,-6.10196 0.41387,-9.10704l0.31039,-3.14274z" data-spm-anchor-id="a313x.7781069.0.i0" fill="#279CFF" id="svg_1"/><path d="m566.98213,476.75321l330.16184,-325.28459l-770.28795,0l330.16184,325.28459a82.77326,91.75869 0 0 0 109.96427,0z" data-spm-anchor-id="a313x.7781069.0.i1" fill="#279CFF" fill-opacity="0.5" id="svg_2"/></g></g></svg>'),
                'priority' => 99,
            ];
        }
        // $res[] = [
        //     'title' => '退出',
        //     'url' => $router->build('/ebcms/ucenter-web/logout'),
        //     'icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg class="icon" style="width: 1em;height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2183"><path d="M966.829213 466.944L762.989213 290.24a61.76 61.76 0 0 0-40.896-15.168 60.096 60.096 0 0 0-61.504 58.88 57.92 57.92 0 0 0 20.352 43.84l85.376 74.048H395.821213a58.944 58.944 0 1 0 0 117.824h371.2l-85.376 74.048a57.6 57.6 0 0 0-20.352 43.712 60.16 60.16 0 0 0 61.12 59.008 62.592 62.592 0 0 0 40.896-15.168l203.84-176.704a57.6 57.6 0 0 0 0-87.744z" fill="#8BAEF7" p-id="2184"></path><path d="M420.589213 903.808H162.541213V117.76h258.176c35.584 0 64.576-26.368 64.576-58.88S456.429213 0 420.717213 0H97.965213C62.317213 0 33.389213 26.368 33.389213 58.88v903.872c0 32.512 28.864 58.88 64.576 58.88H420.589213c35.584 0 64.576-26.368 64.576-58.88s-28.864-58.944-64.576-58.944z" fill="#467CFD" p-id="2185"></path></svg>'),
        //     'priority' => 0,
        // ];
        return $res;
    })
];
