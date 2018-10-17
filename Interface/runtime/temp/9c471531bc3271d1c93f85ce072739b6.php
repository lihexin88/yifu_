<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:97:"C:\wamp64\www\yifu\Interface\public/../application/index\view\recharge\offlinetransferrecord.html";i:1539397074;}*/ ?>
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
					<a href="#">欢迎您，qq123456</a>
					<a href="login.html">退出</a>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="container h2-content">
				<div class="pull-left">
					<a href="rujin.html"> <img src="../img/logo.png"> </a>
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
							<li class="submeun-4"><i class="ioc" style="top: 12px;"></i>
								<a class="l-m-icon" href="/withdrawal/apply.html">出金</a>
							</li>
							<li class="submeun-5"><i class="ioc" style="top: 12px"></i>
								<a class="l-m-icon" href="/recharge/record.html">入金记录</a>
							</li>
							<li class="submeun-13"><i class="ioc active"></i>
								<a class="l-m-icon active" href="/recharge/offlinetransferrecord.html">线下入金记录</a>
								<span class="active"></span>
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
							管理系统&nbsp;&gt;&nbsp;线下入金记录
						</div>
					</div>
					<h1 class="page_title">线下入金记录</h1>
					<div class="full" id="dg1">
						<table class="table table-bordered tablerecord table-hover" width="100%" align="center">
							<thead>
								<tr class="tr-th-text-center">
									<th>时间</th>
									<th>转账金额(RMB)</th>
									<th>入金资金(USD)</th>
									<th>状态</th>
								</tr>
							</thead>
							<tbody id="table">
								<tr>
									<td>2018-10-08 13:21:13</td>
									<td>10000</td>
									<td>0</td>
									<td>拒绝</td>
								</tr>
								<tr>
									<td>2018-10-08 12:56:27</td>
									<td>50000</td>
									<td>6410.25</td>
									<td>已入金</td>
								</tr>
							</tbody>
						</table>
						<div class="pages">
							<div id="PageContent" class="page flickr ajax-page"></div>
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
			function doSearch(page) {
				page = page || 1;
				var params = {};
				params.page = page;
				App.dataGrid('dg1', "/recharge/offlinetransferrecord.html", params);
			}
			$(function($) {
				doSearch();
			});
		</script>-->
	</body>
</html>