<?php if (!defined('THINK_PATH')) exit(); /*a:8:{s:97:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\public/../application/index\view\admins\functions_add.html";i:1529998646;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\meta.html";i:1529999324;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\link.html";i:1529999332;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\form.html";i:1529999356;s:80:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\header.html";i:1540460820;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\left.html";i:1529999338;s:85:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\content_top.html";i:1529999370;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\foot.html";i:1529999360;}*/ ?>
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
    <style>
        input.check_box{
            width:100px;
        }
        .p_checkbox{
            width: 25%;
            float: left;
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
                    <a href="../system/logout">
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
        <li><a href="#">权限功能</a></li>
        <li class="am-active">权限功能编辑</li>
    </ol>
    <div class="widget am-cf">
        <form class="am-cf" id="form" onsubmit="return false">
            <div class="am-panel am-panel-primary">
                <div class="am-panel-hd">基础信息</div>
                <div class="am-form-group">
                    <span class="s1">功能父类名称：</span>
                    <label class="am-form-label">
                        <select name="pid" title="" class="am-form-field am-input-sm">
                            <option value="0">--选择功能--</option>
                            <?php foreach($func as $val): if(($list['pid'])==($val['id'])): ?>
                            <option selected value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                            <?php else: ?>
                            <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                            <?php endif; endforeach; ?>
                        </select>
                    </label>
                </div>
                <div class="am-form-group">
                    <span class="s1">功能名称：</span>
                    <label class="am-form-label">
                        <input type="text" id="title" class="am-form-field am-input-sm" name="name" value="<?php echo $list['name']; ?>" required="required"/>
                    </label>
                </div>
                <input type="hidden" id="title" class="am-form-field am-input-sm" name="id" value="<?php echo $list['id']; ?>" />
                <div class="am-form-group">
                    <span class="s1">功能路径：</span>
                    <label class="am-form-label">
                        <input type="text" id="title" class="am-form-field am-input-sm" name="url" value="<?php echo $list['url']; ?>"  placeholder="例如:  ../type/index.html"/>
                        <span class="s2">*顶级功能不需路径*</span>
                    </label>
                </div>
                <div class="am-panel-bd" style="padding-bottom: 1rem;margin-left: 13rem">
                    <button type="submit" class="am-btn am-btn-primary am-btn-sm" onclick="sub() ">确定</button>
                    <a href="functions.html" class="am-btn am-btn-secondary am-btn-sm">返回</a>
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
<script type="text/javascript">
    $(function () {
        var nav = $('.left-sidebar li.sidebar-nav-link');
        nav.removeClass("active");
        nav.eq(<?php echo $a; ?>).find('ul').show();
        nav.eq(<?php echo $a; ?>).find('ul li').eq(<?php echo $b; ?>).find('a').addClass('active');
    });
    function sub() {
        var arr = parseFormJson("#form");
        $.ajax({
            url: "<?php echo url('Admins/functions_add_edit'); ?>",
            data: {arr: arr},
            type: "post",
            success: function (r) {
                // console.log(r);
                // return false;
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