<?php if (!defined('THINK_PATH')) exit(); /*a:8:{s:101:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\public/../application/index\view\transaction\holiday_edit.html";i:1535356866;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\meta.html";i:1529999324;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\link.html";i:1529999332;s:80:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\header.html";i:1529999348;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\left.html";i:1529999338;s:85:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\content_top.html";i:1529999370;s:86:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\content_foot.html";i:1529999376;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\foot.html";i:1529999360;}*/ ?>
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
<link rel="stylesheet" href="/static/css/amazeui.min.css"/>
<link rel="stylesheet" href="/static/css/amazeui.datetimepicker.css"/>
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
    <ol class="am-breadcrumb">
        <li><a href="../home/index.html" class="am-icon-home">首页</a></li>
        <li><a href="#">参数管理</a></li>
        <li class="am-active">添加假期</li>
    </ol>
     <form class="am-cf" id="form" onsubmit="return false">
    <div class="widget am-cf">
        
        <hr data-am-widget="divider" class="am-divider am-divider-default"/>

        <div class="am-panel am-panel-primary">

                <div class="am-panel-hd">添加假期信息</div>

                <br>

                
               
                <div class="am-form-group">
                    <span class="s1">假期名称：　</span>
                    <label class="am-form-label">
                        <input type="text" id="explain" class="am-form-field am-input-sm" name="explain" value="" />
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">开始时间：　</span>
                    <label class="am-form-label">
                        <!-- <input type="text" class="am-form-field am-input-sm" name="time" placeholder="请选择日期"

                       data-am-datepicker onfocus="this.blur()" value=""/> -->
                       <input type="text" value="" id="datetimepickers" name="time" class="am-form-field">
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">结束时间：　</span>
                    <label class="am-form-label">
                        <!-- <input type="text" id="datetimepicker" class="am-form-field am-input-sm" name="closed_time" placeholder="请选择日期"

                       data-am-datepicker onfocus="this.blur()" value=""/> -->
                        <input type="text" value="" id="datetimepicker" name="closed_time" class="am-form-field">
                       <!-- <input type="text" value="2015-02-15 21:05" id="datetimepicker" class="am-form-field"> -->
                    </label>
                </div>

                

                <div class="am-form-group">
                    <span class="s1">备　　注：　</span>
                    <label class="am-form-label">
                        <input type="text" id="desc" class="am-form-field am-input-sm" name="desc" value="" />
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
<script src="/static/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/js/amazeui.datetimepicker.min.js"></script>
<script type="text/javascript">
   $('#datetimepicker').datetimepicker({
        format: 'yyyy-mm-dd hh:ii'
     });
   $('#datetimepickers').datetimepicker({
        format: 'yyyy-mm-dd hh:ii'
     });
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
                url: "<?php echo url('Transaction/holiday_edit'); ?>",
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


var onSelect = function(date) {
  console.log(date);
};



</script>
</body>
</html>
