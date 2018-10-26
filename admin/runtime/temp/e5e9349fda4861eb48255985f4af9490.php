<?php if (!defined('THINK_PATH')) exit(); /*a:8:{s:79:"D:\phpStudy\WWW\yifu\admin\public/../application/index\view\users\feedback.html";i:1540517098;s:66:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\meta.html";i:1529999324;s:66:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\link.html";i:1529999332;s:68:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\header.html";i:1529999348;s:66:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\left.html";i:1529999338;s:73:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\content_top.html";i:1529999370;s:74:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\content_foot.html";i:1529999376;s:66:"D:\phpStudy\WWW\yifu\admin\application\index\view\public\foot.html";i:1529999360;}*/ ?>
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
    <link rel="stylesheet" href="/static/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/modules.css">
    <link rel="stylesheet" href="/static/css/question.css">
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
    <div class="am-cf">
        <div class="row">
            <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
    <ol class="am-breadcrumb">
        <li><a href="../home/index.html" class="am-icon-home">首页</a></li>
        <li><a href="#">会员管理</a></li>
        <li class="am-active">会员反馈</li>
    </ol>

    <div class="am-form-inline" role="form">

            <span>日期范围：</span>

            <div class="am-form-group ">

                <input type="text" class="am-form-field am-input-sm" name="start" id="start_time" placeholder="请选择起始日期"

                       data-am-datepicker onfocus="this.blur()" value="<?php echo $arr['start_query']; ?>"/>

            </div>

            <div class="am-form-group ">

                <input type="text" class="am-form-field am-input-sm" name="end" id="end_time" placeholder="请选择结束日期" data-am-datepicker

                       onfocus="this.blur()" value="<?php echo $arr['end_query']; ?>"/>

            </div>

            <div class="am-form-group ">

                <input type="text" id="feedback_id" class="am-form-field am-input-sm" placeholder="请输入问题编号"

                       value="<?php echo $arr['name']; ?>"/>

            </div>
            <div class="am-form-group ">

                <input type="text" id="user_id" class="am-form-field am-input-sm" placeholder="请输入用户id"
                	
                       value=""/>

            </div>

            <button type="button" class="am-btn am-btn-primary am-btn-sm" onclick="query_feedback()">查询</button>

            <button type="button" class="am-btn am-btn-warning am-btn-sm" onclick="clear_query()">清除查询</button>

    </div>

    <div class="widget am-cf">
        <div class="feedback">
            <form>
                <table class="table table-bordered tablerecord table-hover">
                    <thead>
                    <tr>
                        <th>用户id</th>
                        <th>问题编号</th>
                        <th>问题类别</th>
                        <th>问题描述</th>
                        <th>提交时间</th>
                        <th>处理状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($feedback_log) || $feedback_log instanceof \think\Collection || $feedback_log instanceof \think\Paginator): $key = 0; $__LIST__ = $feedback_log;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$feedback_log): $mod = ($key % 2 );++$key;if($key%2 == '0'): ?>
						<tr style="color: gray;">
					<?php else: ?>
						<tr>
					<?php endif; ?>
							<td><?php echo $feedback_log['uid']; ?></td>
							<td><?php echo $feedback_log['id']; ?></td>
							<td><?php if(($feedback_log['type']) == 1): ?>交易<?php elseif(($feedback_log['type']) == 2): ?>出入金<?php else: ?>其他<?php endif; ?></td>
							<td title="<?php echo $feedback_log['describe']; ?>"><?php if((strlen($feedback_log['describe']) < 24 )): ?> <?php echo $feedback_log['describe']; else: ?> <?php echo substr($feedback_log['describe'],0,24); ?>... <?php endif; ?></td>
							<td><?php echo date('Y-m-d H:i:s',$feedback_log['time']); ?></td>
							<td><?php if(($feedback_log['headle']) == 1): ?>已处理<?php else: ?>待处理<?php endif; ?></td>
							<td onclick="ondetail(this.id)" id = "<?php echo $feedback_log['id']; ?>">查看</td>
						</tr>
					<?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                    
                </table>
                <?php echo $page; ?>
            </form>
            <!--没有记录时显示-->
            <div class="noRecord"><?php if((empty($noRecord))): ?>最近没有反馈记录<?php else: endif; ?></div>
            <!--没有记录时显示 end-->
        </div>

<div  id="myModal" style="display: none;top: 10px;position: fixed;z-index: 9999;left: 30%;" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" onclick="hiden_detial()" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    反馈记录详情
                </h4>
            </div>
            <div class="modal-body">
                <ul>
                    <li>
                        <span>问题编号：</span>
                        <span id = "question_display_id">160</span>
                    </li>
                    <li>
                        <span>问题类别：</span>
                        <span id="question_display_type">交易</span>
                    </li>
                    <li>
                        <span>提交时间：</span>
                        <span id="question_display_create_time">2018-09-22 12:05</span>
                    </li>
                    <li>
                        <span>解决时间：</span>
                        <span id="question_display_done_time">-</span>
                    </li>
                    <li>
                        <span>处理状态：</span>
                        <span id="question_display_headle">待处理</span>
                    </li>
                </ul>
                <div class="detailBox">
                    <p>问题描述：</p>
                    <textarea id="question_display_describe" disabled="disabled"></textarea>
                </div>
                <div class="detailBox">
                    <p>处理意见：</p>
                    <textarea id="question_display_headle_suggestion" ></textarea>
                </div>
            </div>
            <div class="modal-footer">
            	<button type="button" style="float: left;" onclick="submit_this_question()" class="btn btnqure" data-dismiss="modal">
                    提交
                </button>
                <button type="button" onclick="hiden_detial()" class="btn btnqure" data-dismiss="modal">
                    取消
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

<script src="/static/js/jquery.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/layui-v2.3.0/layui/layui.js"></script>
<script src="/static/js/question.js"></script>
<script src="/static/js/layer.js"></script>


    </div>
    
            </div>
        </div>
    </div>
</div>
</div>
<form action="<?php echo url('feedback'); ?>" class="am-form-inline" role="form" id="form" method="post">
    <input type="hidden" id="form_start_time" name="start_query">
    <input type="hidden" id="form_end_time" name="end_query">
    <input type="hidden" name="question_id" id="form_feedback_id" />
    <input type="hidden" name="uid" id="form_user_id" />
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
    function query_feedback()
    {
//  	var data = {"start_time":$("#start_time").val(),"end_time":$("#end_time").val()};
//  	data['start_time'] = $("#start_time").val();
//  	data['end_time'] = $("#end_time").val();
//  	console.log(data);
    	$("#form_start_time").val($("#start_time").val());
    	$("#form_end_time").val($("#end_time").val());
    	$("#form_feedback_id").val($("#feedback_id").val());
    	$("#form_user_id").val($("#user_id").val());
    	$("#form").submit();
//  	console.log(data);
    }
    function submit_this_question()
    {
    	//问题id和问题处理意见
    	var data = {"question_id":$("#question_display_id").html(),"question_suggest":$("#question_display_headle_suggestion").val()};
    	console.log(data);
		$.ajax({
			type:"post",
			data:data,
			url:"edit_feedback",
			success:function(result){
				console.log(result);
				if(result['code'] == 1){
					console.log(11111);
					layer.alert("问题已处理",function(index){						
						$("#myModal").slideUp(500);
						location.reload();
					});
				}else{
					layer.alert('问题处理失败~<br>请稍后再试！');
				}
			},
			error:function(result){
				console.log(result);
			},
			async:true
		});
    }
</script>
</body>
</html>
