<?php if (!defined('THINK_PATH')) exit(); /*a:8:{s:97:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\public/../application/index\view\transaction\contract.html";i:1540464968;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\meta.html";i:1529999324;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\link.html";i:1529999332;s:80:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\header.html";i:1540460820;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\left.html";i:1529999338;s:85:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\content_top.html";i:1529999370;s:86:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\content_foot.html";i:1529999376;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\foot.html";i:1529999360;}*/ ?>
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
        <li><a href="#">参数管理</a></li>
        <li class="am-active">合约管理</li>
        <li><a href="../transaction/contract_edit.html">添加合约</a></li>
    </ol>

    <div class="am-form-inline" role="form">
            <a href="<?php echo url('contract'); ?>?excel=1" class="am-btn am-btn-warning am-btn-sm">导出Excel表格</a>
    </div>

    <div class="widget am-cf">
        
        <hr data-am-widget="divider" class="am-divider am-divider-default"/>
        <div class="widget-body widget-body-lg am-fr">
            <div class="am-scrollable-horizontal">
                <table class="<?php echo $table; ?>">
                    <thead>
                    <tr>
                        <th>品种名称</th>
                        <th>交易所代码</th>
                        <th>合约名称</th>
                        <th>合约代码</th>
                        <th>合约短码</th>
                        <th>合约类型</th>
                        <th>最近修改日期</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <td><?php echo $vo['variety']['name'].'('.$vo['variety']['bourse_name'].')'; ?></td>
                        <td><?php echo $vo['bourse_code']; ?></td>
                        <td><?php echo $vo['name']; ?></td>
                        <td><?php echo $vo['code']; ?></td>
                        <td><?php echo $vo['short']; ?></td>
                        <td><?php echo $vo['type']; ?></td>
                        <td><?php echo $vo['time']; ?></td>
                        <td>
                            <a href="#" onclick="del_contract(<?php echo $vo['id']; ?>)" class="am-btn am-btn-success am-btn-xs">删除合约</a>
                            <a href="contract_edit?id=<?php echo $vo['id']; ?>&a=<?php echo $a; ?>&b=<?php echo $b; ?>" class="am-btn am-btn-success am-btn-xs">合约修改</a>
                        </td>
                    </tr>
                    <?php endforeach; endif; else: echo "$empty" ;endif; ?>
                    </tbody>
                </table>
                <div class="dataTables_info" id="DataTables_Table_1_info">
                <div class="am-pagination-left"><?php echo $page; ?></div>
            </div>
        </div>
    </div>
    
            </div>
        </div>
    </div>
</div>
</div>
<form action="<?php echo url('Transaction/contract'); ?>" class="am-form-inline" role="form" id="form" method="get">
    <input type="hidden" name="name"/>
    <input type="hidden" name="start_query">
    <input type="hidden" name="end_query">
    <input type="hidden" name="status">
    <input type="hidden" name="page">
</form>
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
//        nav.eq(1).find('a').addClass("active");
        nav.eq(<?php echo $a; ?>).find('ul').show();
        nav.eq(<?php echo $a; ?>).find('ul li').eq(<?php echo $b; ?>).find('a').addClass('active');
    });
    function sub(id) {
        $.ajax({
            url: "<?php echo url('Users/edit'); ?>",
            type: "post",
            data: {id: id},
            success: function (r) {
                if (r['code'] == 1) {
                    layer.msg(r['msg']);
                    setTimeout(function (){
                        window.location.reload();
                    },1500);
                } else {
                    layer.msg(r['msg']);
                }
            }
        });
    }

    function del_contract(id){
        $.ajax({
            url: "<?php echo url('Transaction/del_contract'); ?>",
            type: "post",
            data: {id: id},
            success: function (r) {
                if (r['code'] == 1) {
                    layer.msg(r['msg']);
                    setTimeout(function (){
                        window.location.reload();
                    },1500);
                } else {
                    layer.msg(r['msg']);
                }
            }
        });
    }
    function mold(id) {
        $.ajax({
            url: "<?php echo url('Users/mold'); ?>",
            type: "post",
            data: {id: id},
            success: function (r) {
                if (r == 1) {
                    alert_open('操作成功');
                } else {
                    alert_msg('操作失败');
                }
            }
        });
    }

    function Derive_excel(){
        $.ajax({
            url: "<?php echo url('Users/index'); ?>",
            type: "post",
            data: {excel: 1},
            success: function (r) {
                
            }
        });
    }
</script>
</body>
</html>
