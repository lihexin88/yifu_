<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:85:"D:\phpStudy\WWW\yifu\admin\public/../application/index\view\trade_controll\index.html";i:1540024110;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="../static/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <title>trade_controll_index</title>
</head>
<body>
	
	<button onclick="post_trade_controll()">点击上传</button>
	<script type="text/javascript">
		function post_trade_controll(){
			var data = {
				'uid':3,
				'code':2,
				'stop_loss':1,
				'stop_profit':1,
				'status':1
			}
			$.ajax({
				type:"post",
				data:data,
				url:"/index/trade_controll/post_trade",
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