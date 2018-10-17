<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"D:\phpStudy\WWW\yifu\Interface\public/../application/index\view\security\security.html";i:1539393640;}*/ ?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/index.css" rel="stylesheet">
		<link href="css/common.css" rel="stylesheet">
		<link href="css/front.css" rel="stylesheet">
		<link href="css/home.css" rel="stylesheet">
	</head>

	<body>
		<header>
			<div class="container-fluid header-1">
				<div class="container h1-content">
					<div class="col-md-5 text-left" style="float:left;">
						<ul>
							<li style="display:none"> <img src="img/tel.png">服务电话： </li>
							<li style="color:#fff;padding-left:10px;"><img src="img/tel.png">客户服务热线</li>
						</ul>
					</div>
					<div class="col-md-7 text-right" style="float:right;">
						<a href="http://ef.jxpanjiu.com/security/home.html">欢迎您，qq123456</a>
						<a href="login.html">退出</a>
					</div>
				</div>
			</div>
			<div class="container-fluid">
				<div class="container h2-content">
					<div class="pull-left">
						<a href="http://ef.jxpanjiu.com/security/index.html"> <img src="img/logo.png"> </a>
					</div>
					<a class="ground-item active" href="person.html" style="text-decoration:none;">个人中心</a>
					<a class="ground-item" href="rujin.html" style="text-decoration:none;">入金</a>
					<a class="ground-item" href="chujin.html" style="text-decoration:none;">出金</a>
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
							<li class="submeun-2"><i class="ioc active"></i>
								<a class="l-m-icon active" href="security.html">安全中心</a>
								<span class="active"></span>
							</li>
						</ul>
						<h3 class="myaccount">我的资金</h3>
						<ul class="menu-son">
							<li class="submeun-3"><i class="ioc"></i>
								<a class="l-m-icon" href="rujin.html">入金</a>
							</li>
							<li class="submeun-4"><i class="ioc" style="top: 12px;"></i>
								<a class="l-m-icon" href="chujin.html">出金</a>
							</li>
							<li class="submeun-5"><i class="ioc" style="top: 12px"></i>
								<a class="l-m-icon" href="rujin_record.html">入金记录</a>
							</li>
							<li class="submeun-13"><i class="ioc"></i>
								<a class="l-m-icon" href="offlinetransferrecord.html">线下入金记录</a>
							</li>
							<li class="submeun-6"><i class="ioc"></i>
								<a class="l-m-icon" href="chujin_record.html">出金记录</a>
							</li>
						</ul>
					</div>
				</div>
				<div id="page-content-wrapper">
					<div class="crumbs">
						<div class="container"> 管理系统&nbsp;&gt;&nbsp;安全中心 </div>
					</div>
					<div class="r_main">
						<div class="tbatil">
							<ul>
								<li style="display: none;"> 
									<span class="mingzi">实名认证&nbsp;&nbsp;</span>
									<div class="set_rightb"> 
										<span style="">您已完成实名认证</span>
										<div class="setright">
											<span class="textsuccess">已认证</span> 
											<span style="color:gray;">&nbsp;|&nbsp;</span>
											<a href="http://ef.jxpanjiu.com/security/realname.html?rec=info" name="flag">查看</a>
										</div>
									</div>
								</li>
								<li> 
									<span class="mingzi">修改密码&nbsp;&nbsp;</span>
									<div class="set_rightb"> 
										<span style="">您可以修改<span style="color:#090;">会员登录密码</span>。安全性高的密码可以使帐号更安全,建议您定期更换密码。</span>
										<div class="setright"><span class="textsuccess">已设置</span> <span style="color:gray;">&nbsp;|&nbsp;</span>
											<a href="loginpassword.html" name="flag">修改</a>
										</div>
									</div>
								</li>
								<li> <span class="mingzi">绑定银行卡&nbsp;&nbsp;</span>
									<div class="set_rightb"> <span style="">银行卡已绑定</span>
										<div class="setright"><span class="textsuccess">已绑定</span> 
											<span style="color:gray;">&nbsp;|&nbsp;</span>
											<a href="bind.html" name="flag">修改</a>
										</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<footer>
			<div class="container footer">
				<div class="col-md-6 text-center"> <img class="footer-log" src="img/footlogo.png">
				</div>
				<div class="col-md-5">
					<ul>
						<li class="th">联系我们</li>
						<li>我们在聆听，在服务中进步</li>
						<li><img src="img/footer-tel.png">国内客服热线：（工作日 8：30~17：30） </li>
						<li><img src="img/footer-adr.png">地址：</li>
						<li style="">©&nbsp;2016-2018逸富版权所有</li>
					</ul>
				</div>				
			</div>
		</footer>
		<script type="text/javascript" src="js/jquery-2.1.4.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
	</body>

</html>