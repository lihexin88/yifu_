<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:74:"/www/web/test/yifu/admin/public/../application/agent/view/index/index.html";i:1540522592;s:64:"/www/web/test/yifu/admin/application/agent/view/public/meta.html";i:1540522592;s:64:"/www/web/test/yifu/admin/application/agent/view/public/link.html";i:1540522592;s:66:"/www/web/test/yifu/admin/application/agent/view/public/header.html";i:1540522592;s:64:"/www/web/test/yifu/admin/application/agent/view/public/left.html";i:1540522592;s:64:"/www/web/test/yifu/admin/application/agent/view/public/foot.html";i:1540522592;}*/ ?>
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
                    <a href="../login/loginout.html">
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
            <a href="/agent/index/index.html">
                <i class="am-icon-home sidebar-nav-link-logo"></i> 首页
            </a>
        </li>
        <li class="sidebar-nav-link">
            <a href="#" class="sidebar-nav-sub-title">
                <i class="am-icon-users sidebar-nav-link-logo"></i> 内容管理
                <span class="am-icon-chevron-down am-fr am-margin-right-sm sidebar-nav-sub-ico"></span>
            </a>
            <ul class="sidebar-nav sidebar-nav-sub">
                <li>
                    <a href="/agent/index/index.html">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 个人信息
                    </a>
                    <a href="/agent/Index/user_agent">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 下级信息
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-nav-link">
            <a href="#" class="sidebar-nav-sub-title">
                <i class="am-icon-first-order sidebar-nav-link-logo"></i> 交易统计
                <span class="am-icon-chevron-down am-fr am-margin-right-sm sidebar-nav-sub-ico"></span>
            </a>
            <ul class="sidebar-nav sidebar-nav-sub">
                <li>
                    <a href="/agent/Count/depot.html">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 持仓列表
                    </a>
                </li>
                <li>
                    <a href="/agent/Count/entrust.html">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 委托记录
                    </a>
                </li>
                <li>
                    <a href="/agent/Count/deal.html">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 交易记录
                    </a>
                </li>
                <li>
                    <a href="/agent/Count/withdraw.html">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 提现列表
                    </a>
                </li>
                <li>
                    <a href="/agent/Count/recharge.html">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 充值列表
                    </a>
                </li> 
               
                <!-- <li>
                    <a href="/agent/Count/withdraw.html">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> ćç°ĺčĄ¨
                    </a>
                </li> -->
            </ul>
        </li>
        <!-- <li class="sidebar-nav-link">
            <a href="#" class="sidebar-nav-sub-title">
                <i class="am-icon-first-order sidebar-nav-link-logo"></i> ĺşćŹäżĄćŻ
                <span class="am-icon-chevron-down am-fr am-margin-right-sm sidebar-nav-sub-ico"></span>
            </a>
            <ul class="sidebar-nav sidebar-nav-sub">
                <li>
                    <a href="/agent/System/index.html" >
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> äżĄćŻäżŽćš
                    </a>
                </li>
                <li>
                    <a href="/agent/System/bank.html">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> éśčĄĺĄäżĄćŻ
                    </a>
                </li>
            </ul>
        </li> -->

</div>
            <div class="tpl-content-wrapper">
                <div class="row-content am-cf">
                    <div class="row am-cf">
                        <table class="am-table am-table-border am-table-bordered am-table-bg">
                            <thead>
                                <tr>
                                    <th colspan="2" scope="col">基本信息</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>网站域名</td>
                                    <td><?php echo $name; ?></td>
                                </tr>
                                <tr>
                                    <td>IP地址</td>
                                    <td><?php echo $ip; ?></td>
                                </tr>
                                <tr>
                                    <td>代理编号</td>
                                    <td><?php echo $user['number']; ?></td>
                                </tr>
                                <tr>
                                    <td>联系人姓名 </td>
                                    <td><?php echo $user['real_name']; ?></td>
                                </tr>
                                <tr>
                                    <td>联系人手机 </td>
                                    <td><?php echo $user['phone']; ?></td>
                                </tr>
                                <tr>
                                    <td>推广链接 </td>
                                    <td><?php echo $recom; ?></td>
                                </tr>
                                <tr>
                                    <td>WAP版推广链接 </td>
                                    <td>Chinese (People's Republic of China)</td>
                                </tr>
                                <tr>
                                    <td>推广二维码 </td>
                                    <td><img style="max-width:200px;" src="<?php echo $img; ?>" alt=""></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
    </body>
<script type="text/javascript" src="/static/js/echarts.min.js"></script>
<script type="text/javascript">
    $(function () {

        var nav = $('.left-sidebar li.sidebar-nav-link');

        nav.removeClass("active");

        nav.eq(1).find('ul').show();

        nav.eq(1).find('ul li').eq(0).find('a').addClass('active');

    });
</script>
</html>