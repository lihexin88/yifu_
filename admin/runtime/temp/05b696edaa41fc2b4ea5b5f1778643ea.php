<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"D:\phpStudy\WWW\yifu\admin\public/../application/index\view\user_config\index.html";i:1540028413;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<script src="../static/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<meta charset="UTF-8">
		<title>UserConfig前台</title>
	</head>
	<body>
		<button onclick="post_user_config()">点击提交</button>
		<script type="text/javascript">
			function post_user_config(){
				var data = {
					'uid':'1',
					'open_num':'1000',
					'decla_num':'1000',
					'open_ber':'1',
					'contract':'4',
					'place_order':'4',
					'clear':'5',
					'click':'2',
					'switch':'2',
					'type':'3',
					'status':'1'
					};
				$.ajax({
					type:"post",
					data:data,
					url:"user_config/post_user_config",
					success:function(result){
						console.log(result);
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
