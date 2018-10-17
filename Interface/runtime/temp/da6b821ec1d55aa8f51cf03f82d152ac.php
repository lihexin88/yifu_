<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"D:\phpStudy\WWW\yifu\Interface\public/../application/index\view\login\loginpassword.html";i:1539598749;}*/ ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/index.css" rel="stylesheet">
		<link href="../css/common.css" rel="stylesheet">
		<link href="../css/front.css" rel="stylesheet">
		<link href="../css/home.css" rel="stylesheet">
	</head>
	<body>
		<div class="container-fluid header-1">
			<div class="container h1-content">
				<div class="col-md-5 text-left" style="float:left;">
					<ul>
						<li style="display:none"> <img src="../img/tel.png">服务电话： </li>
						<li style="color:#fff;padding-left:10px;"><img src="../img/tel.png">客户服务热线</li>
					</ul>
				</div>
				<div class="col-md-7 text-right" style="float:right;">
					<a href="#javascript;">欢迎您，qq123456</a>
					<a href="login.html">退出</a>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="container h2-content">
				<div class="pull-left">
					<a href="#javascript;"> <img src="../img/logo.png"> </a>
				</div>
				<a class="ground-item active" href="person.html" style="text-decoration:none;">个人中心</a>
				<a class="ground-item" href="rujin.html" style="text-decoration:none;">入金</a>
				<a class="ground-item" href="chujin.html" style="text-decoration:none;">出金</a>
			</div>
		</div>
		<div class="wrap">
			<div class="container">
				<div id="page-sidebar-menu">
					<div class="menu-logo">个人中心</div>
					<div class="menu">
						<h3 class="mymoney">我的账户</h3>
						<ul class="menu-son">
							<li class="submeun-1"><i class="ioc"></i>
								<a class="l-m-icon" href="person.html">我的主页</a><span class=""></span></li>
							<li class="submeun-2"><i class="ioc active"></i>
								<a class="l-m-icon active" href="security.html">安全中心</a><span class="active"></span></li>
						</ul>
						<h3 class="myaccount">我的资金</h3>
						<ul class="menu-son">
							<li class="submeun-3"><i class="ioc"></i>
								<a class="l-m-icon" href="rujin.html">入金</a><span></span></li>
							<li class="submeun-4"><i class="ioc" style="top: 12px;"></i>
								<a class="l-m-icon" href="chujin.html">出金</a><span></span></li>
							<li class="submeun-5"><i class="ioc" style="top: 12px"></i>
								<a class="l-m-icon" href="rujin_record.html">入金记录</a><span></span></li>
							<li class="submeun-13"><i class="ioc"></i>
								<a class="l-m-icon" href="offlinetransferrecord.html">线下入金记录</a><span></span></li>
							<li class="submeun-6"><i class="ioc"></i>
								<a class="l-m-icon" href="chujin_record.html">出金记录</a><span></span></li>
						</ul>
					</div>
				</div>
				<div id="page-content-wrapper">

					<div class="crumbs">
						<div class="container">
							管理系统&nbsp;&gt;&nbsp;修改密码
						</div>
					</div>
					<div class="dlmain Myhomepage">
						<div class="homeright pageright f_r">
							<div>
								<div class="page_title">
									修改密码&nbsp;&nbsp;
								</div>
								<form class="form-horizontal" action="loginpassword.html" method="POST" onsubmit="return false;" id="lgpwd_frm">
									<div class="form-group">
										<label class="col-sm-2 control-label">当前密码</label>
										<div class="col-md-4">
											<input type="password" class="form-control required" name="old_password" id="old_password" autocomplete="off">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">新密码</label>
										<div class="col-md-4">
											<input type="password" class="form-control required" name="password" id="password" autocomplete="off">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">新密码确认</label>
										<div class="col-md-4">
											<input type="password" class="form-control required" name="repassword" id="repassword" autocomplete="off">
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-4 col-sm-offset-2">
											<button type="submit" id="btn_sub" class="btn btn-primary">提交</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<footer>
			<div class="container footer">
				<div class="col-md-6 text-center">
					<img class="footer-log" src="../img/footlogo.png">
				</div>
				<div class="col-md-5">
					<ul>
						<li class="th">联系我们</li>
						<li>我们在聆听，在服务中进步</li>
						<li><img src="../img/footer-tel.png">国内客服热线：（工作日 8：30~17：30） </li>
						<li><img src="../img/footer-adr.png">地址：</li>
						<li style="">©&nbsp;2016-2018逸富版权所有</li>
					</ul>
				</div>
			</div>
		</footer>
	</body>
	<script type="text/javascript" src="../js/jquery-2.1.4.js" ></script>
	<script type="text/javascript" src="../js/bootstrap.min.js" ></script>
	<script type="text/javascript" src="../layui/layui.all.js" ></script>
	<script type="text/javascript">
		$("#lgpwd_frm").submit(
			function(){
				if($("#password").val()!=$("#repassword").val()){
					$("#password").focus();
					alert("两次密码不一致！");
					return false;
				}
				var data = {"user_id":<?php echo $user_info['id']; ?>,"old_password":$("#old_password").val(),"new_password":$("#password")};
				console.log(data);return false;
				$.ajax({
					type:"post",
					url:"/login/change_password",
					data:data,
					success:function(result){
						console.log(result);
					},
					error:function(result){
						console.log(result);
					},
					async:true
				});
				
			}
		)
	</script>
</html>