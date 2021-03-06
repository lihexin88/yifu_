<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"D:\phpStudy\WWW\yifu\Interface\public/../application/index\view\login\login.html";i:1539830121;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>用户登录</title>
		<link rel="stylesheet" type="text/css" href="../css/login.css" />
	</head>

	<body style="">
		<div style="text-align: center;padding-top: 80px;">
			<img src="../img/footlogo.png" style="height:100px;">
		</div>
		<div class="box">
			<div class="login-container">
				<div id="loginForm">
					<h3 style="text-align: center;color: #4d617e;font-size: 24px;font-weight: bold;">用户登录</h3>
					<div>
						<input type="text" id="efuid" class="username" placeholder="逸富用户名">
					</div>
					<div>
						<input type="password" id="password" class="password" placeholder="逸富密码">
					</div>
					<button id="btn_login">登录</button>
				</div>
			</div>
		</div>
		<ul id="supersized" class="quality" style="visibility: visible;">
			<li class="slide-1 prevslide" style="visibility: visible; opacity: 1;">
				<a target="_blank"><img src="../img/2.jpg"></a>
			</li>
		</ul>
	</body>
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script type="text/javascript" src="../layui/layui.all.js"></script>
	<script>
		$("#btn_login").click(function() {
			var name = $(".username").val();
			var pass = $(".password").val();
			if(name == "") {
				$("#efuid").focus();
				layer.msg("请输入用户名", {
					icon: 15,
					time: 1500,
				});
			}else if(pass == ""){
				$("#password").focus();
				layer.msg("请输入用户密码", {
					icon: 15,
					time: 1500
				});
			}else{
				var data = {"uid":$("#efuid").val(),"password":$("#password").val()};
				layer.msg('登录中，请稍候。。。');
				$.ajax({
					type:"post",
					dataType:"json",
					data:data,
					url:"/login/login",
					success:function(result){
						if(result['code'] == 1){
							layer.msg(result['msg']);
							document.cookie = "token="+result['data']['token'];
							window.location.href = "/home/index";
						}else if(result['code'] == -1){
							layer.confirm(
							'登录失败！请检查您的用户名或密码！',
							function(index){
								window.location.reload();
							})
						}else{
							layer.confirm(
							result['msg'],
							function(index){
								window.location.reload();
							})
						}
					},
					error:function(result){
						layer.confirm(
							'登录失败！请检查您的用户名或密码！',
							function(index){
								window.location.reload();
							}
						)
//						console.log(result);
						return false;
					},
					async:true
				});
			}
		});
	</script>

</html>