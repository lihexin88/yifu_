<?php if (!defined('THINK_PATH')) exit(); /*a:8:{s:88:"/www/web/test/yifu/admin/public/../application/index/view/transaction/contract_edit.html";i:1540464974;s:64:"/www/web/test/yifu/admin/application/index/view/public/meta.html";i:1529999324;s:64:"/www/web/test/yifu/admin/application/index/view/public/link.html";i:1529999332;s:66:"/www/web/test/yifu/admin/application/index/view/public/header.html";i:1540460820;s:64:"/www/web/test/yifu/admin/application/index/view/public/left.html";i:1529999338;s:71:"/www/web/test/yifu/admin/application/index/view/public/content_top.html";i:1529999370;s:72:"/www/web/test/yifu/admin/application/index/view/public/content_foot.html";i:1529999376;s:64:"/www/web/test/yifu/admin/application/index/view/public/foot.html";i:1529999360;}*/ ?>
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
        <li class="am-active"><?php echo !empty($list['name'])?'修改合约':'添加合约'; ?></li>
    </ol>
     <form class="am-cf" id="form" onsubmit="return false">
    <div class="widget am-cf">
        
        <hr data-am-widget="divider" class="am-divider am-divider-default"/>

        <div class="am-panel am-panel-primary">

                <div class="am-panel-hd"><?php echo !empty($list['name'])?'修改合约信息':'添加合约信息'; ?></div>

                <br>

                <div class="am-form-group">
                    <span class="s1">品　种：　　</span>
                    <input type="hidden" name="id" value="<?php echo isset($list['id'])?$list['id']:''; ?>"/>
                    <label class="am-form-label">
                        <select name="futures" title="" class="am-form-field am-input-sm">
                        <?php if($list != ''): if(is_array($exchange) || $exchange instanceof \think\Collection || $exchange instanceof \think\Paginator): $i = 0; $__LIST__ = $exchange;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['id'] == $list['futures']): ?>
                                      <option selected value="<?php echo $vo['id']; ?>" class="am-form-field am-input-sm"><?php echo $vo['name']; ?></option>
                            <?php else: ?>
                                      <option value="<?php echo $vo['id']; ?>" class="am-form-field am-input-sm"><?php echo $vo['name']; ?></option>
                            <?php endif; endforeach; endif; else: echo "$empty" ;endif; else: if(is_array($exchange) || $exchange instanceof \think\Collection || $exchange instanceof \think\Paginator): $i = 0; $__LIST__ = $exchange;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                      <option value="<?php echo $vo['id']; ?>" class="am-form-field am-input-sm"><?php echo $vo['name']; ?></option>
                            <?php endforeach; endif; else: echo "$empty" ;endif; endif; ?>

                        </select>

                    </label>
                </div>
               
                <div class="am-form-group">
                    <span class="s1">合约名称：　</span>
                    <label class="am-form-label">
                        <input type="text" id="name" class="am-form-field am-input-sm" name="name" value="<?php echo !empty($list['name'])?$list['name']:''; ?>" />
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">合约代码：　</span>
                    <label class="am-form-label">
                        <input type="text" id="code" class="am-form-field am-input-sm" name="code" value="<?php echo !empty($list['code'])?$list['code']:''; ?>" />
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">合约短码：　</span>
                    <label class="am-form-label">
                        <input type="text" id="short" class="am-form-field am-input-sm" name="short" value="<?php echo !empty($list['short'])?$list['short']:''; ?>" />
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">合约类型：　</span>
                    <label class="am-form-label">
                        <input type="text" id="industry" class="am-form-field am-input-sm" name="industry" value="<?php echo !empty($list['industry'])?$list['industry']:''; ?>" />
                    </label>
                </div>

                <div class="am-panel-bd" style="padding-bottom: 1rem;margin-left: 13rem">
                    <button type="submit" class="am-btn am-btn-primary am-btn-sm" onclick="sub() " >确定</button>
                    <a href="index.html" class="am-btn am-btn-secondary am-btn-sm">返回</a>
                </div>
        </div>
    
            </div>
        </div>
    </div>
</div>
</div>
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
    function sub() {
        var arr = parseFormJson("#form");
        if(arr.p_names == ""){
            alert("请输入推荐人信息,或填无");
        }else{
            $.ajax({
                url: "<?php echo url('Transaction/update_contract'); ?>",
                data: {arr: arr},
                type: "post",
                success: function (r) {
                    // console.log(r);
                    // return false;
                    if (r['code'] == 1) {
                        alert_open(r['msg']);

                    } else {
                        alert_msg(r['msg']);
                    }
                }
            });
        }    
    }

     function sub_fied() {
        var arr = parseFormJson("#form");
        if(arr.reid == ""){
            alert_open("请输入推荐人信息,或填无");
        }else if(arr.reid != "无"){
            $.ajax({
                url: "<?php echo url('Users/p_name'); ?>",
                data: {arr: arr.reid},
                type: "post",
                success: function (r) {
                    if (r['code'] == 1) {
                        //alert_open(r['msg']);
                        document.getElementById("p_names").value=r['msg'];
                        document.getElementById("p_name").style.display="inline";
                    } else {
                        alert_msg(r['msg']);
                        document.getElementById("reid").value="";
                        document.getElementById("p_name").style.display="none";
                    }
                    
                }
            });
        }else if(arr.reid == "无"){
            document.getElementById("p_name").style.display="none";
        }    
    }
</script>
</body>
</html>
