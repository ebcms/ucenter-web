<!doctype html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>登录 - Powered by {$config->get('copyright.name@ebcms.admin')}</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha256-djO3wMl9GeaC/u6K+ic4Uj/LKhRUSlUFcsruzS7v5ms=" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha256-fh8VA992XMpeCZiRuU4xii75UIG6KvHrbUF8yIS/2/4=" crossorigin="anonymous"></script>
    <script>
        var M = {};
        M.login = function() {
            var code = prompt('校验码已发送到手机，请输入手机收到的校验码：');
            if (code !== null) {
                $.ajax({
                    type: "POST",
                    url: "{echo $router->build('/ebcms/ucenter-web/login')}",
                    data: {
                        code: code,
                    },
                    dataType: "JSON",
                    success: function(response) {
                        alert(response.message);
                        if (!response.code) {
                            if (response.data) {
                                M.login();
                            }
                        } else {
                            location.href = response.url;
                        }
                    }
                });
            }
        }
        $(function() {
            $("#captcha").trigger('click');
            $("#form").bind('submit', function() {
                $.ajax({
                    type: "POST",
                    url: "{echo $router->build('/ebcms/ucenter-web/send-code')}",
                    data: $("#form").serialize(),
                    dataType: "JSON",
                    success: function(response) {
                        $("#captcha").trigger('click');
                        if (!response.code) {
                            alert(response.message);
                        } else {
                            M.login();
                        }
                    }
                });
                return false;
            });
        });
    </script>
</head>

<body>
    <div class="my-3" style="max-width: 400px; margin:0 auto;">
        <div class="text-center">
            <img src="{$config->get('site.logo@ebcms/web')}" class="my-3" alt="{$config->get('site.title@ebcms/web')}">
        </div>
        <div class="p-4 border-top border-3 border-dark bg-light border-bottom">
            <div class="display-6 mb-3">用户登录</div>
            <form id="form">
                <div class="mb-3">
                    <label class="form-label" for="phone">电话号码</label>
                    <input type="tel" name="phone" class="form-control form-control-lg" id="phone" autocomplete="off" required>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="captchav">验证码</label>
                    <div class="input-group mb-2 me-sm-2">
                        <input type="text" name="captcha" id="captchav" class="form-control form-control-lg" autocomplete="off" required>
                        <div class="input-group-append">
                            <img id="captcha" style="vertical-align: middle;cursor: pointer;height: 48px;" class="rounded-right" onclick="this.src = '<?php echo $router->build('/ebcms/ucenter-web/captcha'); ?>?time=' + (new Date()).getTime();">
                        </div>
                    </div>
                </div>
                <div class="form-text text-center">无需注册，直接登陆，有疑问请联系</div>
                <button type="submit" class="btn btn-primary btn-lg mb-3 mt-4 w-100">登录</button>
            </form>
        </div>
    </div>
</body>

</html>