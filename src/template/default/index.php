<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户中心</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha256-djO3wMl9GeaC/u6K+ic4Uj/LKhRUSlUFcsruzS7v5ms=" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha256-fh8VA992XMpeCZiRuU4xii75UIG6KvHrbUF8yIS/2/4=" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom shadow-sm" style="z-index:2;">
        <div class="container-fluid">
            <a class="navbar-brand wb" href="{echo $router->build('/ebcms/ucenter-web/index')}">用户中心</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav d-block d-lg-none">
                    {foreach $menus as $v}
                    <li class="nav-item">
                        <a class="nav-link" href="{$v.url}" target="main">{$v.title}</a>
                    </li>
                    {/foreach}
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{echo $router->build('/')}" target="_blank">访问首页</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{echo $router->build('/ebcms/ucenter-web/logout')}">退出</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script>
        $(function() {
            $(".navbar-nav li a").on("click", function() {
                if (!$(this).hasClass('dropdown-toggle')) {
                    $('.navbar-toggler').trigger('click');
                }
            });
        });
    </script>
    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .main {
            overflow: hidden;
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            top: 57px;
        }

        .main .left {
            float: left;
            width: 200px;
            height: 100%;
            overflow: auto;
            border-right: 1px solid #eee;
        }

        .main .right {
            height: 100%;
            overflow: auto;
        }

        /*clear float.*/
        .main:after {
            content: "";
            height: 0;
            line-height: 0;
            display: block;
            visibility: hidden;
            clear: both;
        }
    </style>
    <div class="main">
        <div class="left d-none d-lg-block bg-light">
            <div>
                <ul class="nav flex-column" id="leftnav">
                    {foreach $menus as $v}
                    <li class="nav-item">
                        <a class="nav-link text-truncate py-3 px-4 font-weight-bold text-secondary position-relative" href="{$v.url}" target="{$v['target']??'main'}" data-bs-toggle="tooltip" data-bs-placement="right" title="{$v['tips']??''}">
                            <span class="me-2"><img src="{echo $v['icon']}" width="20" height="20" alt=""></span>
                            <span>{$v.title}</span>
                            {if isset($v['tips']) && $v['tips']}
                            <span class="position-absolute top-50 end-0 translate-middle-y me-2 p-2 bg-danger border border-light rounded-circle">
                                <span class="visually-hidden">New alerts</span>
                            </span>
                            {/if}
                        </a>
                    </li>
                    {/foreach}
                </ul>
            </div>
            <script>
                $("#leftnav > li").bind("click", function() {
                    $(this).addClass("cur").siblings().removeClass("cur");
                    $(this).find('span.position-absolute').remove();
                    $(this).find('a.nav-link').removeAttr('data-bs-original-title');
                });
            </script>
            <style>
                #leftnav>li:hover {
                    background-color: #eaeaea;
                }

                #leftnav>.cur {
                    background-color: #eaeaea;
                }

                /*滚动条整体样式*/
                ::-webkit-scrollbar {
                    width: 5px;
                    height: 1px;
                }

                /*滚动条滑块*/
                ::-webkit-scrollbar-thumb {
                    border-radius: 5px;
                    box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
                    background: #f5f5f5;
                }

                /*滚动条轨道*/
                ::-webkit-scrollbar-track {
                    box-shadow: inset 0 0 1px rgba(0, 0, 0, 0);
                    border-radius: 5px;
                    background: #fafafa;
                }
            </style>
        </div>
        <div class="right">
            <iframe src="{echo $router->build('/ebcms/ucenter-web/home')}" id="mainiframe" name="main" style="width:100%;height:100%;overflow:auto;display:block;" frameborder="0"></iframe>
        </div>
    </div>
    <script>
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        });
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
</body>

</html>