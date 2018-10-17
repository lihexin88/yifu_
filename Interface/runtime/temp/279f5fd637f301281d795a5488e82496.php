<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"C:\wamp64\www\yifu\Interface\public/../application/index\view\withdrawal\apply.html";i:1539396984;}*/ ?>
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
		<header>
			<div class="container-fluid header-1">
				<div class="container h1-content">
					<div class="col-md-5 text-left" style="float:left;">
						<ul>
							<li style="display:none"> <img src="../img/tel.png">服务电话： </li>
							<li style="color:#fff;padding-left:10px;"><img src="../img/tel.png">客户服务热线</li>
						</ul>
					</div>
					<div class="col-md-7 text-right" style="float:right;">
						<a href="http://ef.jxpanjiu.com/withdrawal/home.html">欢迎您，qq123456</a>
						<a href="login.html">退出</a>
					</div>
				</div>
			</div>
			<div class="container-fluid">
				<div class="container h2-content">
					<div class="pull-left">
						<a href="#"> <img src="../img/logo.png"> </a>
					</div>
					<a class="ground-item" href="person.html" style="text-decoration:none;">个人中心</a>
					<a class="ground-item" href="/recharge/index.html" style="text-decoration:none;">入金</a>
					<a class="ground-item active" href="/withdrawal/apply.html" style="text-decoration:none;">出金</a>
				</div>
			</div>
		</header>
		<div class="wrap">
			<div class="container">
				<div id="page-sidebar-menu">
					<div class="menu-logo">个人中心</div>
					<div class="menu">
						<h3 class="mymoney">我的账户</h3>
						<ul class="menu-son">
							<li class="submeun-1"><i class="ioc"></i>
								<a class="l-m-icon" href="person.html">我的主页</a>
							</li>
							<li class="submeun-2"><i class="ioc"></i>
								<a class="l-m-icon" href="security.html">安全中心</a>
							</li>
						</ul>
						<h3 class="myaccount">我的资金</h3>
						<ul class="menu-son">
							<li class="submeun-3"><i class="ioc"></i>
								<a class="l-m-icon" href="/recharge/index.html">入金</a>
							</li>
							<li class="submeun-4"><i class="ioc active" style="top: 12px;"></i>
								<a class="l-m-icon active" href="/withdrawal/apply.html">出金</a>
								<span class="active"></span>
							</li>
							<li class="submeun-5"><i class="ioc" style="top: 12px"></i>
								<a class="l-m-icon" href="/recharge/record.html">入金记录</a>
							</li>
							<li class="submeun-13"><i class="ioc"></i>
								<a class="l-m-icon" href="/recharge/offlinetransferrecord.html">线下入金记录</a>
							</li>
							<li class="submeun-6"><i class="ioc"></i>
								<a class="l-m-icon" href="/withdrawal/record.html">出金记录</a>
							</li>
						</ul>
					</div>
				</div>
				<div id="page-content-wrapper">
					<div class="crumbs">
						<div class="container">
							管理系统&nbsp;&gt;&nbsp;出金
						</div>
					</div>
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
											<input type="text" class="form-control required" name="amount" autocomplete="off">
										</div>
									</div><br>
									<div class="form-group" style="display: none;">
										<label class="col-sm-2 control-label" style="color:red;font-weight:bold;font-size:15px;">出金说明 :</label>
										<div class="col-md-8">
											<span style="line-height:25px;font-size:14px;color:red;">每笔出金三方支付将收取5元人民币手续费 </span>
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
		<script type="text/javascript" src="../js/jquery-2.1.4.js"></script>
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../layui/layui.all.js"></script>
		<!--<script type="text/javascript">
			$(function() {
				loadAvailableFund();
				$('.actfrm').on('submit', function() {
					var _this = $(this);
					App.getJSON(_this.attr('action'), _this, function(res) {
						if(res.code == 9999) {
							layer.alert('您未绑定银行卡，请先绑定银行卡', {
								icon: 5,
								closeBtn: 0
							}, function() {
								location.href = "http://ef.jxpanjiu.com/bank/bind.html";
							});
						} else if(res.code) {
							layer.alert(res.msg, {
								icon: 6,
								closeBtn: 0
							}, function() {
								location.href = res.url;
							});
						} else {
							layer.alert(res.msg, {
								icon: 5
							});
						}
					}, {
						maskSelector: '.actfrm'
					});
					return false;
				});
			});
			function loadAvailableFund() {
				App.getJSON("/withdrawal/availablefund.html", '', function(res) {
					if(res.code) {
						$('#lbl_amount').text(res.data.available_fund);
						$('#lbl_amount_cny').text(res.data.available_fund_cny);
					}
				});
			}
		</script>-->
	</body>
</html>