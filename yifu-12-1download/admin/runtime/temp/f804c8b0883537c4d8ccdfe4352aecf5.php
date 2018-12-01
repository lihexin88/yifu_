<?php if (!defined('THINK_PATH')) exit(); /*a:7:{s:75:"/www/web/test/yifu/admin/public/../application/index/view/agents/index.html";i:1540552270;s:64:"/www/web/test/yifu/admin/application/index/view/public/meta.html";i:1529999324;s:64:"/www/web/test/yifu/admin/application/index/view/public/link.html";i:1529999332;s:66:"/www/web/test/yifu/admin/application/index/view/public/header.html";i:1540460820;s:64:"/www/web/test/yifu/admin/application/index/view/public/left.html";i:1529999338;s:71:"/www/web/test/yifu/admin/application/index/view/public/content_top.html";i:1529999370;s:64:"/www/web/test/yifu/admin/application/index/view/public/foot.html";i:1529999360;}*/ ?>
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
                <li><a href="../agents/index.html">代理管理</a></li>
            </ol>
            <div class="widget am-cf">
                <div class="am-form-inline" role="form">
                    <div class="am-form-group ">
                        <input type="text" id="phone" name="phone" class="am-form-field am-input-sm" placeholder="请输入手机号" />
                    </div>
                    <div class="am-form-group ">
                        <input type="text" id="number" name="number" class="am-form-field am-input-sm" placeholder="请输入代理编号" />
                    </div>
                    <button type="button" class="am-btn am-btn-primary am-btn-sm" onclick="info_query()">查询</button>
                    <button type="button" class="am-btn am-btn-warning am-btn-sm" onclick="clear_query()">清除查询</button>

                    <a href="add_agent.html" class="am-btn am-btn-primary am-btn-sm">代理添加</a>
                    <!--<a href="add_staff.html" class="am-btn am-btn-primary am-btn-sm">员工添加</a>-->
                </div>
                <hr data-am-widget="divider" class="am-divider am-divider-default"/>

                <div class="widget-body widget-body-lg am-fr">
                    <table class="<?php echo $table; ?>">
                        <thead>
                            <tr>
                                <th>选择</th>
                                <th>ID</th>
                                <th>代理编号</th>
                                <th>名称</th>
                                <th>代理域名</th>
                                <!--<th>代理来源</th>-->
                                <th>联系人姓名</th>
                                <th>联系人电话</th>
                                <th>手续费提成</th>
                                <th>递延费提成</th>
                                <th>盈利分配提成</th>
                                <th>代理值</th>
                                <th>状态</th>
                                <th>备注</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody id="list">
                            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <tr>
                                <td><input id="rptList_chkId_3" type="checkbox" name="rptList$ctl04$chkId"></td>
                                <td><?php echo $vo['id']; ?></td>
                                <td><?php echo $vo['number']; ?></td>
                                <td><?php echo $vo['name']; ?></td>
                                <td><?php echo $vo['agent_url']; ?></td>
                                <!--<td><?php echo $vo['agent']; ?></td>-->
                                <td><?php echo $vo['real_name']; ?></td>
                                <td><?php echo $vo['phone']; ?></td>
                                <td><?php echo $vo['fee_ratio']; ?></td>
                                <td><?php echo $vo['defer_ratio']; ?></td>
                                <td><?php echo $vo['wit_ratio']; ?></td>
                                <td>
                                    <?php switch($vo['grade']): case "1": ?>一级代理<?php break; case "2": ?>二级代理<?php break; case "3": ?>三级代理<?php break; endswitch; ?>
                                </td>
                                <td><?php echo $vo['status']; ?></td>
                                <td><?php echo $vo['remake']; ?></td>
                                <td>
                                    <a href="javascript:" onClick="del(this, <?php echo $vo['id']; ?>)" class="am-btn am-btn-warning am-btn-xs">删除</a>
                                    <?php if($vo['status'] == '禁用'): ?>
                                    <a href="javascript:" onClick="pass(this, <?php echo $vo['id']; ?>)"  class="am-btn am-btn-success am-btn-xs">开启</a>
                                    <?php else: ?>
                                    <a href="javascript:" onClick="push(this, <?php echo $vo['id']; ?>)" class="am-btn am-btn-warning am-btn-xs">禁用</a>
                                    <?php endif; if($vo['grade'] == 3): ?>
                                    <!--<a href="#" class="am-btn am-btn-warning am-btn-xs">第三等级</a>-->
                                    <?php else: ?>
                                    <a href="add_agent.html?id=<?php echo $vo['id']; ?>" class="am-btn am-btn-success am-btn-xs">添加下级</a>
                                    <?php endif; ?>
                                    <!--<a href="staff.html?id=<?php echo $vo['id']; ?>" class="am-btn am-btn-success am-btn-xs">员工</a>-->
                                    <a href="add_agent.html?id=<?php echo $vo['id']; ?>&modify=1" class="am-btn am-btn-success am-btn-xs">修改</a>
                                    <a href="login.html?id=<?php echo $vo['id']; ?>" class="am-btn am-btn-success am-btn-xs" target="_blank">登录代理后台</a>
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
        function del(obj, id) {
        layer.confirm('确认要删除代理吗？', function (index) {
        $.ajax({
        type: 'POST',
                url: "<?php echo url('index/Agents/del'); ?>",
                data: {id: id},
                dataType: 'json',
                success: function (data) {
                layer.msg('删除成功!', {icon: 1, time: 1000});
                $(obj).parents("tr").remove();
                },
                error: function (data) {
                layer.msg('删除失败!', {icon: 0, time: 1000});
                },
        });
        });
        }

        function pass(obj, id) {
        layer.confirm('确认要开启代理吗？', function (index) {
        $.ajax({
        type: 'POST',
                url: "<?php echo url('index/Agents/modify_recharge'); ?>",
                data: {id: id, edit_type: 1},
                dataType: 'json',
                success: function (data) {
                layer.msg('开启成功!', {icon: 1, time: 1000});
                window.location.href = "<?php echo url('index/Agents/index'); ?>";
                },
                error: function (data) {
                layer.msg('开启失败!', {icon: 0, time: 1000});
                },
        });
        });
        }

        function push(obj, id) {
        layer.confirm('确认要禁用代理吗？', function (index) {
        $.ajax({
        type: 'POST',
                url: "<?php echo url('index/Agents/modify_recharge'); ?>",
                data: {id: id, edit_type: 0},
                dataType: 'json',
                success: function (data) {
                layer.msg('锁定成功!', {icon: 1, time: 1000});
                window.location.href = "<?php echo url('index/Agents/index'); ?>";
                },
                error: function (data) {
                layer.msg('锁定失败!', {icon: 0, time: 1000});
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
        function info_query() {
        info();
        $("#form").submit();
        }
        function info() {
        $("input[name='start_query']").val($("input[name='start']").val());
        $("input[name='end_query']").val($("input[name='end']").val());
        $("input[name='phone']").val($("#phone").val());
        $("input[name='number']").val($("#number").val());
        $("input[name='status']").val($("select[name='status']").val());
        }
        function clear_query() {
        $("input[name='start_query']").val();
        $("input[name='end_query']").val();
        $("select[name='status']").val( - 1);
        $("input[name='phone']").val();
        $("input[name='number']").val();
        $("#form").submit();
        }
    </script>
</body>
</html>