<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:85:"D:\phpStudy\WWW\yifu\Interface\public/../application/index\view\withdrawal\apply.html";i:1539919926;s:69:"D:\phpStudy\WWW\yifu\Interface\application\index\view\base\index.html";i:1539676179;}*/ ?>
<!--首部加载文件-->

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

<!--标题-->

<title>提现</title>

<!--头部结束-->

	</head>
	<body>

<!--自定义css样式-->


<!--header顶层   样式不变-->

		<div class="container-fluid header-1">
			<div class="container h1-content">
				<div class="col-md-5 text-left" style="float:left;">
					<ul>
						<li style="display:none"> <img src="../img/tel.png">服务电话： </li>
						<li style="color:#fff;padding-left:10px;"><img src="../img/tel.png">客户服务热线</li>
					</ul>
				</div>
				<div class="col-md-7 text-right" style="float:right;">
					<a href="" >欢迎您，<span style="color: #0099FF;"><?php echo $user_info['real_name']; ?></span><input type="text" value="<?php echo $user_info['id']; ?>" style="display: none;" id="user_id" /></a>
					<a  id="logout" onclick="logout()">退出</a>
				</div>
			</div>
		</div>

<!--首部--开始-->

		<div class="container-fluid">
			<div class="container h2-content">
				<div class="pull-left">
					<a href="index.html"><img src="../img/logo.png"></a>
				</div>

<!--首部--个人中心-->

				<a class="ground-item" href="index.html" style="text-decoration:none;">个人中心</a>

<!--首部--入金-->

				<a class="ground-item" href="/recharge/index.html" style="text-decoration:none;">入金</a>

<!--首部 -- 出金-->

				<a class="ground-item active" href="/withdrawal/apply.html" style="text-decoration:none;">出金</a>

<!--首部--结束-->

			</div>
		</div>

<!--左侧边--开始-->

		<div class="wrap">
			<div class="container">
				<div id="page-sidebar-menu">
					<div class="menu-logo">个人中心</div>
					<div class="menu">
						<h3 class="mymoney">我的账户</h3>
						<ul class="menu-son">

<!--左侧边--我的主页-->

							<li class="submeun-1"><i class="ioc"></i>
								<a class="l-m-icon" href="/home/index.html">我的主页</a>
							</li>

<!--左侧边--安全中心-->

							<li class="submeun-2"><i class="ioc"></i>
								<a class="l-m-icon" href="/security/index.html">安全中心</a>
							</li>
						</ul>
						<h3 class="myaccount">我的资金</h3>
						<ul class="menu-son">

<!--左侧边--入金-->

							<li class="submeun-3"><i class="ioc"></i>
								<a class="l-m-icon" href="/recharge/index.html">入金</a>
							</li>

<!--左侧边--出金-->

							<li class="submeun-4"><i class="ioc active" style="top: 12px;"></i>
								<a class="l-m-icon active" href="/withdrawal/apply.html">出金</a>
								<span class="active"></span>
							</li>

<!--左侧边--入金记录-->

							<li class="submeun-5"><i class="ioc" style="top: 12px"></i>
								<a class="l-m-icon" href="/recharge/record.html">入金记录</a>
							</li>

<!--左侧边--线下入金记录-->

							<li class="submeun-13"><i class="ioc"></i>
								<a class="l-m-icon" href="/recharge/offlinetransferrecord.html">线下入金记录</a>
							</li>

<!--左侧边--出金记录-->

							<li class="submeun-6"><i class="ioc"></i>
								<a class="l-m-icon" href="/withdrawal/record.html">出金记录</a>
							</li>

<!--左侧边结束-->

						</ul>
					</div>
				</div>

<!--右侧顶部开始-->

				<div id="page-content-wrapper">

<!--右侧显示内容--顶部-->

					<input type="hidden" id="ACTIVE_MENU" value="submeun-1" autocomplete="off">
					<div class="crumbs">
						<div class="container"> 管理系统&nbsp;&gt;&nbsp;个人中心&nbsp;&gt;&nbsp;出金 </div>
					</div>

<!--右侧显示内容-->

<div class="dlmain Myhomepage">
	<div class="homeright pageright f_r">
		<div>
			<div class="page_title">
				出金&nbsp;&nbsp;
			</div>
			<form action="chujin.html" onsubmit="return false;" method="post" class="form-horizontal actfrm">
				<div class="form-group">
					<label class="col-sm-2 control-label" style="color:red;font-weight:bold;font-size:15px;"></label>
					<div class="col-md-8">
						<span style="line-height:25px;font-size:14px;">美元：<span style="font-weight:bold;color:red" id="lbl_amount">0</span> &nbsp;&nbsp; 折合人民币：
						<span style="font-weight:bold;color:red" id="lbl_amount_cny">0</span>元
						</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">金额(人民币)<span class="required" aria-required="true">*</span></label>
					<div class="col-md-4">
						<input type="number" step="0.01" min="0.01" placeholder="请输入金额" required="required"  class="form-control required" name="amount" autocomplete="off">
					</div>
				</div><br>
				<div class="form-group" style="display: ;">
					<label class="col-sm-2 control-label" style="color:red;font-weight:bold;font-size:15px;">出金说明 :</label>
					<div class="col-md-8">
						<span style="line-height:25px;font-size:14px;color:red;">每笔出金三方支付将收取-元人民币手续费 </span>
					</div>
				</div>
				<div class="form-group">
					<div style="margin: 9px 0 0 158px;">
						<button type="submit" id="btn_sub" class="btn btn-primary">出金</button>
					</div>
				</div>
			</form>

		</div>
	</div>
</div>

<!--右侧显示内容结束-->

				</div>
			</div>
		</div>

<!--脚部	-->

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


			<script type="text/javascript" src="../js/jquery-2.1.4.js"></script>
			<script type="text/javascript" src="../js/bootstrap.min.js"></script>
			<script type="text/javascript" src="../js/login.js"></script>
			<script type="text/javascript" src="../js/layer.js"></script>

<!--自定义页面js-->

<script type="text/javascript">
			$("#btn_sub").click(
				function(){
					if($("input[name='amount']").val()><?php echo $user_account['balance']; ?>){
						layer.alert("可用余额不足！");
						return false;
					}else if($("input[name='amount']").val()<0){
						layer.alert("数据格式错误！！");
						$("input[name='amount']").focus();
						return false;
					}
					$.ajax({
						type:"post",
						data:{
							name:null,
							amount:$("input[name='amount']").val()
						},
						url:"/withdrawal/post_withdraw",
						success:function(result){
							if(result['code'] == 1){
								layer.alert("提现申请已记录！我们会尽快处理");
								window.location.href = "/withdrawal/record"
							}else if(result['code'] == -1){
								console.log(result);
								layer.alert(result['msg']);
							}else{
								console.log(result);
							}
						},
						error:function(result){
							console.log(result);
						},
						async:true
					});
				}
			)
		</script>

<!--结束-->

	</body>
</html>
