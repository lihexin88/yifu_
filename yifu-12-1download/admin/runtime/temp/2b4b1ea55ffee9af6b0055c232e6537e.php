<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"/www/web/test/yifu/admin/public/../application/agent/view/login/login.html";i:1540522592;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta HTTP-EQUIV="X-UA-Compatible" content="IE=edge">
    <title>管理系统</title>
    <meta name="description" content="">
    <meta name="keywords" content="index">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta HTTP-EQUIV="Cache-Control" content="no-siteapp"/>
    <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
    <link type="text/css" rel="stylesheet" href="/static/css/amazeui.min.css"/>
    <link type="text/css" rel="stylesheet" href="/static/css/amazeui.datatables.min.css"/>
    <link type="text/css" rel="stylesheet" href="/static/css/app.css">
    <style type="text/css">
        .am-left input {
            float: left;
            width: 50% !important;
            display: inline-block;
            height: 40px
        }

        .am-left img {
            width: 50% !important;
            display: inline-block;
            height: 40px
        }
    </style>
    <script type="text/javascript" src="/static/js/jquery.min.js"></script>
</head>
<body data-type="login">
<script type="text/javascript" src="/static/js/theme.js"></script>
<div class="am-g tpl-g">
    <div class="tpl-login">
        <div class="tpl-login-content">
            <div class="tpl-login-logo">
                <h1>代理管理系统</h1>
            </div>
            <form class="am-form tpl-form-line-form" onsubmit="return false">
                <div class="am-form-group">
                    <input type="text" class="tpl-form-input" value="" name="name" placeholder="请输入登录账号">
                </div>
                <div class="am-form-group">
                    <input type="password" class="tpl-form-input" value="" name="password" placeholder="请输入用户密码">
                </div>
                <div class="am-form-group am-left">
                    <input type="text" name="code" value="1234" class="tpl-form-input" placeholder="验证码"/>
                    <img id="img" src="<?php echo url('index/index/self_verify'); ?>" onclick="self_verify()">
                </div>
                <div class="am-form-group">
                    <!--<a href="<?php echo url('agent/index/index'); ?>">跳转</a>-->
                    <button onclick="sub()"
                            class="am-btn am-btn-primary  am-btn-block tpl-btn-bg-color-success tpl-login-btn">
                        安全登录
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="/static/js/amazeui.min.js"></script>
<script type="text/javascript" src="/static/js/app.js"></script>
<script type="text/javascript" src="/static/layer/layer.js"></script>
<script type="text/javascript">
    function sub() {
        var name = $("input[name='name']").val();
        var password = $("input[name='password']").val();
        var code = $("input[name='code']").val();
        if (name == "") {
            layer.msg('请输入会员名');
        } else if (password == "") {
            layer.msg('请输入密码');
        } else if (code == "") {
            layer.msg('请输入验证码');
        } else {
            $.ajax({
                url: "<?php echo url('agent/login/dologin'); ?>",
                type: 'post',
                data: {name: name, password: password, code: code},
                success: function (data) {
                    if (data['code'] == 1) {
                        window.location.href = "<?php echo url('agent/index/index'); ?>";
                    } else {
                        layer.msg(data['msg']);
                        self_verify();
                    }
                }
            });
        }
    }

    function self_verify() {
        var url = "<?php echo url('index/Index/self_verify'); ?>";
        $("#img").attr('src', url + '?&id=' + Math.random());
    }

    $(document).keypress(function (e) {
        if (e.which == 13) {
            submit();
        }
    });
    if (top.location !== self.location) {
        top.location = self.location;
    }
</script>
</body>
</html>