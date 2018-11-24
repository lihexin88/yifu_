<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:75:"D:\phpStudy\WWW\yifu\admin\public/../application/index\view\home\index.html";i:1529890418;s:66:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\meta.html";i:1529999324;s:66:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\link.html";i:1529999332;s:68:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\header.html";i:1529999348;s:66:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\left.html";i:1529999338;s:66:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\foot.html";i:1529999360;}*/ ?>
﻿<!DOCTYPE html>
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
        <div class="row-content am-cf">
            <!--  <div class="row am-cf">
                 <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                     <div class="widget widget-primary am-cf"
                          style=" background: #6ccac9;border: 1px solid #6ccac9; border-top: 2px solid #6ccac9;">
                         <div class="widget-statistic-header">平台收益</div>
                         <div class="widget-statistic-body">
                             <div class="widget-statistic-value">
                                <?php echo $list['money']; ?>
                             </div>
                             <span class="widget-statistic-icon am-icon-users" style="color: #87d9e0;"></span>
                         </div>
                     </div>
                 </div>
                 <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                     <div class="widget widget-primary am-cf"
                          style=" background: #6ccac9;border: 1px solid #6ccac9; border-top: 2px solid #6ccac9;">
                         <div class="widget-statistic-header">累计成交金额</div>
                         <div class="widget-statistic-body">
                             <div class="widget-statistic-value">
                                 <?php echo $list['sum_deal']; ?>
                             </div>
                             <span class="widget-statistic-icon am-icon-users" style="color: #87d9e0;"></span>
                         </div>
                     </div>
                 </div>
                 <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                     <div class="widget widget-primary am-cf"
                          style="background: #6ccac9;border: 1px solid #6ccac9;border-top: 2px solid #6ccac9;">
                         <div class="widget-statistic-header">普通会员</div>
                         <div class="widget-statistic-body">
                             <div class="widget-statistic-value"><i class="am-icon-user" style="font-size: 35px"></i>
                                 50
                             </div>
                             <span class="widget-statistic-icon am-icon-users" style="color: #87d9e0"></span>
                         </div>
                     </div>
                 </div>
                 <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                     <div class="widget widget-purple am-cf"
                          style=" background: #6ccac9;border: 1px solid #6ccac9;border-top: 2px solid #6ccac9;">
                         <div class="widget-statistic-header">已冻结会员</div>
                         <div class="widget-statistic-body">
                             <div class="widget-statistic-value"><i class="am-icon-user" style="font-size: 35px"></i>
                                 0
                             </div>
                             <span class="widget-statistic-icon am-icon-users" style="color: #87d9e0"></span>
                         </div>
                     </div>
                 </div>
             </div> -->
            <div class="row am-cf">
                <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                    <div class="widget widget-purple am-cf"
                         style=" background: #57c8f2;border: 1px solid #57c8f2;border-top: 2px solid #57c8f2;">
                        <div class="widget-statistic-header">平台收益</div>
                        <div class="widget-statistic-body">
                            <div class="widget-statistic-value">¥<?php echo $list['money']; ?></div>
                            <span class="widget-statistic-icon am-icon-credit-card-alt"
                                  style="color: #5fbfe2"></span>
                        </div>
                    </div>
                </div>
                <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                    <div class="widget widget-purple am-cf"
                         style=" background: #57c8f2;border: 1px solid #57c8f2;border-top: 2px solid #57c8f2;">
                        <div class="widget-statistic-header">累计成交金额</div>
                        <div class="widget-statistic-body">
                            <div class="widget-statistic-value">¥<?php echo $list['sum_deal']; ?></div>
                            <span class="widget-statistic-icon am-icon-credit-card-alt"
                                  style="color: #5fbfe2"></span>
                        </div>
                    </div>
                </div>
                <!-- <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                    <div class="widget widget-primary am-cf"
                         style="background: #ff6c60;border: 1px solid #ff6c60;border-top: 2px solid #ff6c60;">
                        <div class="widget-statistic-header">商品总数</div>
                        <div class="widget-statistic-body">
                            <div class="widget-statistic-value">15件</div>
                            <span class="widget-statistic-icon am-icon-credit-card-alt"
                                  style="color: #f9a6a5"></span>
                        </div>
                    </div>
                </div>
                <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                    <div class="widget widget-primary am-cf"
                         style="background: #ff6c60;border: 1px solid #ff6c60;border-top: 2px solid #ff6c60;">
                        <div class="widget-statistic-header">上架商品</div>
                        <div class="widget-statistic-body">
                            <div class="widget-statistic-value">154件</div>
                            <span class="widget-statistic-icon am-icon-credit-card-alt" style="color: #f9a6a5"></span>
                        </div>
                    </div>
                </div> -->
                <div class="am-u-sm-12 am-u-md-12 am-u-lg-4">
                </div>
            </div>
            <div class="row am-cf">
                <table class="am-table am-table-border am-table-bordered am-table-bg">
                    <thead>
                    <tr>
                        <th colspan="2" scope="col">服务器信息</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th width="30%">服务器计算机名</th>
                        <td><span id="lbServerName">http://127.0.0.1/</span></td>
                    </tr>
                    <tr>
                        <td>服务器IP地址</td>
                        <td>192.168.1.1</td>
                    </tr>
                    <tr>
                        <td>服务器域名</td>
                        <td>www.h-ui.net</td>
                    </tr>
                    <tr>
                        <td>服务器端口</td>
                        <td>80</td>
                    </tr>
                    <tr>
                        <td>服务器IIS版本</td>
                        <td>Microsoft-IIS/6.0</td>
                    </tr>
                    <tr>
                        <td>服务器操作系统</td>
                        <td>Microsoft Windows NT 5.2.3790 Service Pack 2</td>
                    </tr>
                    <tr>
                        <td>服务器的语言种类</td>
                        <td>Chinese (People's Republic of China)</td>
                    </tr>
                    <tr>
                        <td>.NET Framework 版本</td>
                        <td>2.050727.3655</td>
                    </tr>
                    <tr>
                        <td>服务器当前时间</td>
                        <td>2014-6-14 12:06:23</td>
                    </tr>
                    <tr>
                        <td>服务器IE版本</td>
                        <td>6.0000</td>
                    </tr>
                    <tr>
                        <td>服务器上次启动到现在已运行</td>
                        <td>7210分钟</td>
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

<script type="text/javascript" src="/static/js/echarts.min.js"></script>

<script type="text/javascript">

    $(function () {

        var nav = $('.left-sidebar li.sidebar-nav-link');

        nav.removeClass("active");

        nav.eq(0).find('a').addClass("active");

    });

    function frozen() {

        $.ajax({

            url: "<?php echo url('Auto/index'); ?>",

            success: function (data) {

                alert_open('操作成功');

            }

        });

    }

</script>
</body>
</html>