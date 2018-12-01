<?php if (!defined('THINK_PATH')) exit(); /*a:7:{s:76:"/www/web/test/yifu/admin/public/../application/agent/view/count/entrust.html";i:1540522592;s:64:"/www/web/test/yifu/admin/application/agent/view/public/meta.html";i:1540522592;s:64:"/www/web/test/yifu/admin/application/agent/view/public/link.html";i:1540522592;s:66:"/www/web/test/yifu/admin/application/agent/view/public/header.html";i:1540522592;s:64:"/www/web/test/yifu/admin/application/agent/view/public/left.html";i:1540522592;s:71:"/www/web/test/yifu/admin/application/agent/view/public/content_top.html";i:1540522592;s:64:"/www/web/test/yifu/admin/application/agent/view/public/foot.html";i:1540522592;}*/ ?>
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
    <div class="am-cf">
        <div class="row">
            <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">

    <ol class="am-breadcrumb">

        <li><a href="../home/index.html" class="am-icon-home">首页</a></li>

        <li><a href="#">交易统计</a></li>

        <li class="am-active">委托记录</li>
    </ol>
    <div class="widget am-cf">

            <div class="am-form-inline" role="form">

            <span>日期范围：</span>

            <div class="am-form-group ">

                <input type="text" class="am-form-field am-input-sm" name="start" placeholder="请选择起始日期"

                       data-am-datepicker onfocus="this.blur()" value="<?php echo $arr['start_query']; ?>"/>

            </div>

            <div class="am-form-group ">

                <input type="text" class="am-form-field am-input-sm" name="end" placeholder="请选择结束日期" data-am-datepicker  onfocus="this.blur()" value="<?php echo $arr['end_query']; ?>"/>

            </div>

            <div class="am-form-group ">

                <input type="text" id="name" class="am-form-field am-input-sm" placeholder="请输入会员名称"  value="<?php echo $arr['name']; ?>"/>

            </div>

            <button type="button" class="am-btn am-btn-primary am-btn-sm" onclick="info_query()">查询</button>

            <button type="button" class="am-btn am-btn-warning am-btn-sm" onclick="clear_query()">清除查询</button>

        </div>

    <hr data-am-widget="divider" class="am-divider am-divider-default"/>

    <div class="widget-body widget-body-lg am-fr">

        <div class="am-scrollable-horizontal">

            <table class="<?php echo $table; ?>">

                <thead>
                    <tr>
                        <th>用户id</th>
                        <th>用户名称</th>
                        <th>订单号</th>
                        <th>证券名称</th>
                        <th>数量</th>
                        <th>委托价格</th>
                        <th>交易佣金</th>
                        <th>保证金</th>
                        <th>类型</th>
                        <th>限价</th>
                        <th>委托方向</th>
                        <th>状态</th>
                        <th>投机</th>
                        <th>交易时间</th>
                        <th>完成交易时间</th>
                    </tr>
                </thead>
                <tbody id="list">
                    <?php foreach($list as $val): ?>
                    <tr class="text-c">
                        <td><?php echo $val['user']['id']; ?></td>
                        <td><?php echo $val['user']['name']; ?></td>
                        <td><?php echo $val['order']; ?></td>
                        <td><?php echo $val['name']; ?></td>
                        <td><?php echo $val['number']; ?></td>
                        <td><?php echo $val['price']; ?></td>
                        <td><?php echo $val['fee']; ?></td>
                        <td><?php echo $val['bond']; ?></td>
                        <td><?php echo $val['mold']; ?></td>
                        <td><?php echo $val['type']; ?></td>
                        <td><?php echo $val['direction']; ?></td>
                        <td><?php echo $val['status']; ?></td>
                        <td><?php echo $val['pattern']; ?></td>
                        <td><?php echo $val['time']; ?></td>
                        <td><?php echo $val['finish_time']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <!-- <tr>
                        <th colspan="5"></th>
                        <th>{bond}</th>
                        <th>{number}</th>
                        <th>{increase_bond}</th>
                        <th colspan="1"></th>
                        <th>{sum_fee}</th>
                        <th colspan="1"></th>
                        <th>{total}</th>
                        <th colspan="1"></th>
                    </tr>   -->
                </tbody>
            </table>

            <div class="am-pagination-left"><?php echo $page; ?></div>

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

<script type="text/javascript">
    
    $(function () {

        var nav = $('.left-sidebar li.sidebar-nav-link');

        nav.removeClass("active");

        nav.eq(2).find('ul').show();

        nav.eq(2).find('ul li').eq(1).find('a').addClass('active');

    });
    

</script>

</body>

</html>
