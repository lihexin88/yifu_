<?php if (!defined('THINK_PATH')) exit(); /*a:7:{s:76:"/www/web/test/yifu/admin/public/../application/index/view/article/agree.html";i:1540463866;s:64:"/www/web/test/yifu/admin/application/index/view/public/meta.html";i:1529999324;s:64:"/www/web/test/yifu/admin/application/index/view/public/link.html";i:1529999332;s:66:"/www/web/test/yifu/admin/application/index/view/public/header.html";i:1540460820;s:64:"/www/web/test/yifu/admin/application/index/view/public/left.html";i:1529999338;s:71:"/www/web/test/yifu/admin/application/index/view/public/content_top.html";i:1529999370;s:64:"/www/web/test/yifu/admin/application/index/view/public/foot.html";i:1529999360;}*/ ?>
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
        <li><a href="#">内容管理</a></li>
        <li class="am-active">协议管理</li>
        <li><a href="agree_edit">新增协议</a></li>
    </ol>
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
        <!-- <div class="am-form-group ">
            <input type="text" id="name" class="am-form-field am-input-sm" placeholder="请输入功能名称"
                   value="<?php echo $arr['name']; ?>"/>
        </div>
-->
        <button type="button" class="am-btn am-btn-primary am-btn-sm" onclick="info_query()">查询</button>
        <button type="button" class="am-btn am-btn-warning am-btn-sm" onclick="clear_query()">清除查询</button>
    </div>
    <hr data-am-widget="divider" class="am-divider am-divider-default"/>
    <div class="widget-body widget-body-lg am-fr">
        <div class="am-scrollable-horizontal">
            <table class="<?php echo $table; ?>">
                <thead>
                <tr>
                    <th>标题</th>
                    <th>描述信息</th>
                    <th>添加时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody id="list">
                <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr class="text-c">
                    <td><a href="agree_edit.html?id=<?php echo $vo['id']; ?>"><?php echo $vo['title']; ?></a></td>
                    <td><?php echo $vo['desc']; ?></td>
                    <td><?php echo $vo['time']; ?></td>
                    <td>
                        <!--<a href="#" onclick='return del(id="<?php echo $vo['id']; ?>");' class="am-btn am-btn-warning am-btn-xs">删除</a>-->
                        <a href="agree_edit.html?id=<?php echo $vo['id']; ?>" class="am-btn am-btn-success am-btn-xs">修改</a>
                    </td>
                </tr>
                <?php endforeach; endif; else: echo "$empty" ;endif; ?>
                </tbody>
            </table>
            <div class="am-pagination-left"><?php echo $page; ?></div>
        </div>
    </div>
</div>
</div>
<form action="<?php echo url('Article/agree'); ?>" class="am-form-inline" role="form" id="form" method="get">
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
    function del(id) {
        if (confirm("确定要删除吗？")) {
            $.ajax({
                url: "<?php echo url('Article/agree_del'); ?>",
                data: {id: id},
                type: "post",
                success: function (r) {
                    if (r['code'] == 1) {
                        alert_open(r['msg'])
                    } else {
                        alert_msg(r['msg']);
                    }
                }
            });
            return true;
        } else {
            return false;
        }
    }

    $(function () {
        var nav = $('.left-sidebar li.sidebar-nav-link');
        nav.removeClass("active");
        nav.eq(1).find('ul').show();
        nav.eq(1).find('ul li').eq(6).find('a').addClass('active');
    });

</script>
</body>
</html>
