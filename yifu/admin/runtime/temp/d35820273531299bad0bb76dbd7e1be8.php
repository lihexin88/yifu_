<?php if (!defined('THINK_PATH')) exit(); /*a:8:{s:91:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\public/../application/index\view\user_bank\edit.html";i:1535010042;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\meta.html";i:1529999324;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\link.html";i:1529999332;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\form.html";i:1529999356;s:80:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\header.html";i:1529999348;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\left.html";i:1529999338;s:85:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\content_top.html";i:1529999370;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\foot.html";i:1529999360;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
<meta HTTP-EQUIV="X-UA-Compatible" content="IE=edge">
<title>后台管理系统</title>
<meta name="description" content="">
<meta name="keywords" content="index">
<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
<meta name="renderer" content="webkit">
<meta HTTP-EQUIV="Cache-Control" content="no-siteapp" />
<!--<link rel="icon" type="image/png" href="/static/i/favicon.png">-->
<!--<link rel="apple-touch-icon-precomposed" href="/static/i/app-icon72x72@2x.png">-->
<meta name="apple-mobile-web-app-title" content="Amaze UI" />
    <link rel="stylesheet" href="/static/css/amazeui.min.css"/>
<link rel="stylesheet" href="/static/css/amazeui.datatables.min.css"/>
<link rel="stylesheet" href="/static/css/app.css">
    <style type="text/css">
    .am-form-group .s1 {
        display: inline-block;
        width: 150px;
    }

    .am-form-group .s2 {
        margin-left: 20px;
        color: #F37B1D;
    }

    .am-form-group input {
        width: 300px;
        display: inline-block;
    }

    .am-form-group select {
        width: 300px;
        display: inline-block;
    }

    .am-form-label {
        font-weight: inherit;
    }

    .am-form-group {
        margin-bottom: 0;
    }

    .am-panel-bd {
        padding-bottom: 0;
    }
</style>
</head>
<body data-type="index" class="theme-white">
<div class="am-g tpl-g">
    <header>
    <div class="am-fl tpl-header-logo">
        <a href="#">
            <h1 style="margin: 0;line-height: 40px">后台管理系统</h1>
        </a>
    </div>
    <!-- 右侧内容 -->
    <div class="tpl-header-fluid">
        <!-- 侧边切换 -->
        <div class="am-fl tpl-header-switch-button am-icon-list">
            <span></span>
        </div>
        <!-- 其它功能-->
        <div class="am-fr tpl-header-navbar">
            <ul>
                <!-- 欢迎语 -->
                <li class="am-text-sm tpl-header-navbar-welcome">
                    <a href="#">欢迎你, <span>管理员</span> </a>
                </li>
                <!-- 欢迎语 -->
                <li class="am-text-sm tpl-header-navbar-welcome">
                    <a href="<?php echo url('home/password'); ?>">修改密码</a>
                </li>
                <!-- 退出 -->
                <li class="am-text-sm">
                    <a href="../index/index.html">
                        <span class="am-icon-sign-out"></span> 退出
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
    <div class="left-sidebar">
    <ul class="sidebar-nav">
        <li class="sidebar-nav-link">
            <a href="../home/index.html">
                <i class="am-icon-home sidebar-nav-link-logo"></i> 首页
            </a>
        </li>
        <?php if(is_array($left) || $left instanceof \think\Collection || $left instanceof \think\Paginator): $i = 0; $__LIST__ = $left;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <li class="sidebar-nav-link">
                <a href="#" class="sidebar-nav-sub-title">
                    <i class="<?php echo $vo['name']['class']; ?> sidebar-nav-link-logo"></i> <?php echo $vo['name']['name']; ?>
                    <span class="am-icon-chevron-down am-fr am-margin-right-sm sidebar-nav-sub-ico"></span>
                </a>
                <?php if(is_array($vo['rela']) || $vo['rela'] instanceof \think\Collection || $vo['rela'] instanceof \think\Paginator): $k = 0; $__LIST__ = $vo['rela'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>
                    <ul class="sidebar-nav sidebar-nav-sub">
                        <li>
                            <a href="<?php echo $v['url']; ?>?a=<?php echo $i; ?>&b=<?php echo $k-1; ?>">
                                <span class="am-icon-angle-right sidebar-nav-link-logo"></span> <?php echo $v['name']; ?>
                            </a>
                        </li>
                    </ul>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </li>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
    <div class="tpl-content-wrapper">
    <div class="am-cf">
        <div class="row">
            <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
    <ol class="am-breadcrumb" style="    margin-bottom: 0;">
        <li><a href="../home/index.html" class="am-icon-home">首页</a></li>
        <li><a href="#">银行卡管理</a></li>
    </ol>
    <div class="widget am-cf">
        <form class="am-cf" id="form" onsubmit="return false">
            <div class="am-panel am-panel-primary">
                <div class="am-panel-hd">基础信息</div>
                <div class="am-panel-bd">
                    <input type="hidden" name="id" value="<?php echo isset($list['id'])?$list['id']:''; ?>"/>
                    <div class="am-form-group">
                        <span class="s1">开户行：</span>
                        <label class="am-form-label">
                            <select name="bank">
                                <?php if(is_array($info) || $info instanceof \think\Collection || $info instanceof \think\Paginator): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if(($list['bank'])==($vo['id'])): ?>
                                <option selected value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                                <?php else: ?>
                                <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                                <?php endif; endforeach; endif; else: echo "$empty" ;endif; ?>
                            </select>
                        </label>
                    </div>
                    <div class="am-form-group">
                        <div display:flex>
                            <span class="s1">开户地区：</span>
                            <select style="flex:1" name="province" id="province" class="region" param='city'>
                                <option value="">请选择</option>
                                <?php if(is_array($province_lixt) || $province_lixt instanceof \think\Collection || $province_lixt instanceof \think\Paginator): if( count($province_lixt)==0 ) : echo "" ;else: foreach($province_lixt as $key=>$vo): if(($province['id'])==($vo['id'])): ?>
                                <option selected value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                                <?php else: ?>
                                <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                                <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                            <select name="city" style="flex:1" id="city" class="region" param='country'>
                                <?php if(is_array($city_lixt) || $city_lixt instanceof \think\Collection || $city_lixt instanceof \think\Paginator): if( count($city_lixt)==0 ) : echo "" ;else: foreach($city_lixt as $key=>$value): if($city['id']==$value['id']): ?>
                                <option selected value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                <?php else: ?>
                                <span id="city">
                                        </span>
                                <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                            <select name="country" id="country" style="flex:1" param='country'>
                                <?php if(is_array($country_lixt) || $country_lixt instanceof \think\Collection || $country_lixt instanceof \think\Paginator): if( count($country_lixt)==0 ) : echo "" ;else: foreach($country_lixt as $key=>$vo): if($country['id']==$vo['id']): ?>
                                <option selected value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                                <?php else: ?>
                                <span id="country">
                                        </span>
                                <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                        </label>
                    </div>
                    <div class="am-form-group">
                        <span class="s1">开户支行：</span>
                        <label class="am-form-label">
                            <input type="text" id="address" class="am-form-field am-input-sm" name="address"
                                   value="<?php echo isset($list['address'])?$list['address']:''; ?>" placeholder="请输入开户支行"/>
                        </label>
                    </div>
                    <div class="am-form-group">
                        <span class="s1">开户姓名：</span>
                        <label class="am-form-label">
                            <input type="text" id="username" class="am-form-field am-input-sm" name="username"
                                   value="<?php echo isset($list['username'])?$list['username']:''; ?>" placeholder="请输入开户姓名"/>
                        </label>
                    </div>
                    <div class="am-form-group">
                        <span class="s1">开户手机号码：</span>
                        <label class="am-form-label">
                            <input type="text" id="phone" class="am-form-field am-input-sm" name="phone"
                                   value="<?php echo isset($list['phone'])?$list['phone']:''; ?>" placeholder="请输入开户手机号码"/>
                        </label>
                    </div>
                    <div class="am-form-group">
                        <span class="s1">开户身份证：</span>
                        <label class="am-form-label">
                            <input type="text" id="number" class="am-form-field am-input-sm" name="number"
                                   value="<?php echo isset($list['number'])?$list['number']:''; ?>" placeholder="请输入开户身份证"/>
                        </label>
                    </div>
                    <div class="am-form-group">
                        <span class="s1">银行卡号：</span>
                        <label class="am-form-label">
                            <input type="text" id="card" class="am-form-field am-input-sm" name="card"
                                   value="<?php echo isset($list['card'])?$list['card']:''; ?>" placeholder="请输入银行卡号"/>
                        </label>
                    </div>
                    <div class="am-panel-bd" style="padding-bottom: 1rem;margin-left: 13rem">
                        <button type="submit" class="am-btn am-btn-primary am-btn-sm" onclick="sub()">确定</button>
                        <a href="index.html?a=<?php echo $a; ?>&b=<?php echo $b; ?>" class="am-btn am-btn-secondary am-btn-sm">返回</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="/static/js/jquery.min.js"></script>
<script type="text/javascript" src="/static/js/amazeui.min.js"></script>
<script type="text/javascript" src="/static/js/amazeui.datatables.min.js"></script>
<script type="text/javascript" src="/static/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="/static/js/app.js"></script>
<script type="text/javascript" src="/static/layer/layer.js"></script>
<form action="<?php echo $url; ?>" class="am-form-inline" role="form" id="form" method="get">
    <input type="hidden" name="name"/>
    <input type="hidden" name="start_query">
    <input type="hidden" name="end_query">
    <input type="hidden" name="status">
    <input type="hidden" name="page">
        <input type="hidden" name="number">
    <input type="hidden" name="phone">
</form>
<script type="text/javascript" src="/static/ajaxupload.js"></script>
<script>
    var a = $("#province option:selected").val();
    if (a) {
        $("#province").css("width", "97");
        $("#city").css("width", "97");
        $("#country").css("width", "97");
    } else {
        $("#city").hide();
        $("#country").hide();
    }

    function ifshow() {
        if ($("#province").val()) {
            $("#province").css("width", "148");
            $("#city").css("width", "148");
            $("#city").show();
            if ($("#city").val()) {
                $("#province").css("width", "97");
                $("#city").css("width", "97");
                $("#country").css("width", "97");
                $("#country").show();
                return false;
            } else {
                $("#country").hide();
                return false;
            }
        } else {
            $("#city").hide();
        }
    }

    $(function () {
        $("body").on('change', ".region", function () {
            parent_id = $(this).val();
            param = $(this).attr('param');
            $("#" + param).html("");
            $.post("<?php echo url('index/UserBank/getregion'); ?>", {'parent_id': parent_id}, function (data) {
                if (param == 'city') {
                    $("#country").html("");
                    html = "<select name=" + param + " class='region' param='country'>";
                    html += "<option value=''>请选择</option>";
                } else {
                    $("#country").html("");
                    html = "<select name=" + param + " class='region' param='country'>";
                    html += "<option value=''>请选择</option>";
                }
                if (data.status == 1) {
                    $.each(data.list, function (k, v) {
                        $("#country").html("");
                        html += "<option value='" + v.id + "'>" + v.name + "</option>";
                        a = ifshow();
                        a;
                    })
                }
                html += "</select>";
                $("#" + param).html(html);
            }, 'json')
        })
    })

</script>
<script type="text/javascript">
    $(function () {
        var nav = $('.left-sidebar li.sidebar-nav-link');
        nav.removeClass("active");
        nav.eq(<?php echo $a; ?>).find('ul').show();
        nav.eq(<?php echo $a; ?>).find('ul li').eq(<?php echo $b; ?>).find('a').addClass('active');

    })
    ;

    function sub() {
        var arr = parseFormJson("#form");
        $.ajax({
            url: "<?php echo url('UserBank/edit'); ?>",
            data: {arr: arr},
            type: "post",
            success: function (r) {
                if (r['code'] == 1) {
                    alert_open(r['msg'])
                } else {
                    alert_msg(r['msg']);
                }
            }
        });
    }

</script>

</body>

</html>