<?php if (!defined('THINK_PATH')) exit(); /*a:7:{s:80:"D:\phpStudy\WWW\yifu\admin\public/../application/index\view\apply\recharges.html";i:1539858903;s:66:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\meta.html";i:1529999324;s:66:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\link.html";i:1529999332;s:68:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\header.html";i:1540460820;s:66:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\left.html";i:1529999338;s:73:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\content_top.html";i:1529999370;s:66:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\foot.html";i:1529999360;}*/ ?>
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
    <ol class="am-breadcrumb">
        <li><a href="../home/index.html" class="am-icon-home">首页</a></li>
        <li><a href="#">申请管理</a></li>
        <li class="am-active">充值申请</li>
    </ol>
    <div class="widget am-cf">
        <div class="am-form-inline" role="form">
            <span>日期范围：</span>
            <div class="am-form-group ">
                <input type="text" class="am-form-field am-input-sm" name="start" placeholder="请选择起始日期"
                       data-am-datepicker onfocus="this.blur()" value="<?php echo $arr['start_query']; ?>"/>
            </div>
            <div class="am-form-group ">
                <input type="text" class="am-form-field am-input-sm" name="end" placeholder="请选择结束日期" data-am-datepicker
                       onfocus="this.blur()" value="<?php echo $arr['end_query']; ?>"/>
            </div>
            <div class="am-form-group ">
                <input type="text" id="name" class="am-form-field am-input-sm" placeholder="请输入手机号或用户名称"
                       value="<?php echo $arr['name']; ?>"/>
            </div>
            <button type="button" class="am-btn am-btn-primary am-btn-sm" onclick="info_query()">查询</button>
            <button type="button" class="am-btn am-btn-warning am-btn-sm" onclick="clear_query()">清除查询</button>
            <a href="<?php echo url('recharges'); ?>?excel=1&name=<?php echo $name; ?>&start_query=<?php echo $start_query; ?>&end_query=<?php echo $end_query; ?>">导出Excel表格</a>
        </div>
        <hr data-am-widget="divider" class="am-divider am-divider-default"/>
        <div class="widget-body widget-body-lg am-fr">
            <div class="am-scrollable-horizontal">
                <table class="<?php echo $table; ?>">
                    <thead>
                    <tr>
                        <th>选择</th>

                        <th>订单号</th>
                        <th>申请用户</th>
                        <!--<th>资金类型</th>-->
                        <th>支付方式</th>
                        <th>RMB金额</th>
                        <th>美金</th>
                        <th>手续费</th>
                        
                        <th>开户名</th>
                        <th>开户银行</th>
                        <!--<th>开户支行</th>-->
                        <th>银行卡号</th>

                        <th>申请时间</th>
                        <th>处理时间</th>
                        <th>处理状态</th>

                        <th>备注</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id="list">
                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <td><input id="rptList_chkId_3" type="checkbox" name="rptList$ctl04$chkId"></td>
                        <td><?php echo $vo['order']; ?></td>
                        <td><?php echo $vo['user']['phone']; ?></td>

                        <?php if($vo['pay_type'] == 1): ?>
                        <td>支付宝</td>
                        <?php elseif($vo['pay_type'] == 2): ?>
                        <td>网银</td>
                        <?php else: ?>
                        <td>线下转账</td>
                        <?php endif; ?>
                        <td><?php echo $vo['number']; ?></td>
                        <td><?php echo $vo['usd']; ?></td>
                        <td><?php echo $vo['fee']; ?></td>
                        <td><?php echo $vo['bank']['name']; ?></td>
                        <td><?php echo $vo['bank']['bank_name']; ?></td>

                        <td><?php echo $vo['bank']['bank_card']; ?></td>

                        <td><?php echo $vo['time']; ?></td>
                        <?php if($vo['pay_time'] == 0): ?>
                        <td>暂未处理</td>
                        <?php else: ?>
                        <td><?php echo $vo['pay_time']; ?></td>
                        <?php endif; if($vo['status'] == 0): ?>
                        <td>未处理</td>
                        <?php elseif($vo['status'] == 1): ?>
                        <td>已处理</td>
                        <?php elseif($vo['status'] == 2): ?>
                        <td>已拒绝</td>
                        <?php endif; ?>

                        <td><?php echo $vo['remark']; ?></td>
                        <td>
                            <?php if($vo['status'] != 0): ?>
                            <a href="#" class="am-btn am-btn-warning am-btn-xs">已处理</a>
                            <?php else: ?>
                            <a href="javascript:" onClick="pass(this, <?php echo $vo['id']; ?>)"
                               class="am-btn am-btn-success am-btn-xs">通过</a>
                            <a href="javascript:" onClick="del(this, <?php echo $vo['id']; ?>)" class="am-btn am-btn-warning am-btn-xs">驳回</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; endif; else: echo "$empty" ;endif; ?>
                    <tr>
                        <th colspan="4"></th>
                        <th><?php echo $sum['recharge_num']; ?></th>
                        <th><?php echo $sum['usd_num']; ?></th>
                        <th><?php echo $sum['fee_num']; ?></th>
                        <th colspan="5"></th>
                    </tr>
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
    function pass(obj, id) {
        layer.confirm('确认要通过审核吗？', function (index) {
            $.ajax({
                type: 'POST',
                url: "<?php echo url('index/Apply/modify_recharges'); ?>",
                data: {id: id, edit_type: 1},
                dataType: 'json',
                success: function (r) {
                	console.log(r);
                    layer.msg(r.msg);
                    setTimeout(function (){
                        window.location.reload();
                    },1500);
                },
                error: function (r) {
                	console.log(r);
                    layer.msg('审核失败!', {icon: 0, time: 1000});
                },
            });
        });
    }

    function del(obj, id) {
        layer.confirm('确认要驳回吗？', function (index) {
            $.ajax({
                type: 'POST',
                url: "<?php echo url('index/Apply/modify_recharges'); ?>",
                data: {id: id, edit_type: 0},
                dataType: 'json',
                success: function (r) {
                    layer.msg(r.msg);
                    setTimeout(function (){
                        window.location.reload();
                    },1500);
                },
                error: function (r) {
                    layer.msg('审核失败!', {icon: 0, time: 1000});
                },
            });
        });
    }

    $(function () {
        var nav = $('.left-sidebar li.sidebar-nav-link');
        nav.removeClass("active");
        nav.eq(<?php echo $a; ?>).find('ul').show();
        nav.eq(<?php echo $a; ?>).find('ul li').eq(<?php echo $b; ?>).find('a').addClass('active');
    });
</script>
</body>

</html>