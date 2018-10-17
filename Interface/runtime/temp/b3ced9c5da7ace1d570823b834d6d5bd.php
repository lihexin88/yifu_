<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"C:\wamp64\www\yifu\Interface\public/../application/index\view\recharge\login.html";i:1539255462;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>login</title>
    <script src = "/static/js/jquery-3.3.1.js"></script>
</head>
<body>
	<select id = "log_type">
		<option value="login">登录</option>
		<option value="logout">登出</option>
	</select>
    <input type="button" id="login_submit" value="提交">
<script type="text/javascript">
	$("#login_submit").click(function(){
		var data_type = {"type":$("#log_type").val()};
//		console.log(data_type);
		$.ajax({
		type:"post",
		data:data_type,
		url:"check_login",
		success:function(result){
//			console.log(result);
			alert("返回码："+result['code']+"消息为："+result['msg']);
		},
		error:function(result){
			console.log(result);
		},
		async:true
	});}
	)
	
</script>
</body>
</html>