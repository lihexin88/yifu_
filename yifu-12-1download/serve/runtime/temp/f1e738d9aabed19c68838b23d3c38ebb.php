<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"/www/web/test/yifu/serve/public/../application/index/view/security/index.html";i:1540783503;s:63:"/www/web/test/yifu/serve/application/index/view/base/index.html";i:1540805351;}*/ ?>
<!--首部加载文件-->

<!DOCTYPE html>
<html lang="en">


	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<link href="/css/index.css" rel="stylesheet">
		<link href="/css/common.css" rel="stylesheet">
		<link href="/css/front.css" rel="stylesheet">
		<link href="/css/home.css" rel="stylesheet">

<!--标题-->

		<title></title>

<!--头部结束-->

	</head>
	<body>

<!--自定义css样式-->


<!--header顶层   样式不变-->

		<div class="container-fluid header-1">
			<div class="container h1-content">
				<div class="col-md-5 text-left" style="float:left;">
					<ul>
						<li style="display:none"> <img src="/img/tel.png">服务电话： </li>
						<li style="color:#fff;padding-left:10px;"><img src="/img/tel.png">客户服务热线</li>
					</ul>
				</div>
				<div class="col-md-7 text-right" style="float:right;">
					<a href="" >欢迎您，<span style="color: #0099FF;"><?php echo $user_info['name']; ?></span><input type="text" value="<?php echo $user_info['id']; ?>" style="display: none;" id="user_id" /></a>
					<a  id="logout" onclick="logout()">退出</a>
				</div>
			</div>
		</div>

<!--首部--开始-->

		<div class="container-fluid">
			<div class="container h2-content">
				<div class="pull-left">
					<a href="index_index.html"><img src="/img/logo.png"></a>
				</div>

<!--首部--个人中心-->

				<a class="ground-item" href="/index.php/home/index_index.html" style="text-decoration:none;">个人中心</a>

<!--首部--入金-->

				<a class="ground-item" href="/index.php/recharge/index.html" style="text-decoration:none;">入金</a>

<!--首部 -- 出金-->

				<a class="ground-item" href="/index.php/withdrawal/apply.html" style="text-decoration:none;">出金</a>

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
								<a class="l-m-icon" href="/index.php/home/index_index.html">我的主页</a>
							</li>

<!--左侧边--安全中心-->

<li class="submeun-2"><i class="ioc active"></i>
	<a class="l-m-icon active" href="/index.php/security/index.html">安全中心</a>
	<span class="active"></span>
</li>
</ul>
<h3 class="myaccount">我的资金</h3>
<ul class="menu-son">

<!--左侧边--入金-->

							<li class="submeun-3"><i class="ioc"></i>
								<a class="l-m-icon" href="/index.php/recharge/index.html">入金</a>
							</li>

<!--左侧边--出金-->

							<li class="submeun-4"><i class="ioc" style="top: 12px;"></i>
								<a class="l-m-icon" href="/index.php/withdrawal/apply.html">出金</a>
							</li>

<!--左侧边--入金记录-->

							<li class="submeun-5"><i class="ioc" style="top: 12px"></i>
								<a class="l-m-icon" href="/index.php/recharge/record.html">入金记录</a>
							</li>

<!--左侧边--线下入金记录-->

							<li class="submeun-13"><i class="ioc"></i>
								<a class="l-m-icon" href="/index.php/recharge/offlinetransferrecord.html">线下入金记录</a>
							</li>

<!--左侧边--出金记录-->

							<li class="submeun-6"><i class="ioc"></i>
								<a class="l-m-icon" href="/index.php/withdrawal/record.html">出金记录</a>
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
	<div class="container"> 管理系统&nbsp;&gt;&nbsp;个人中心&nbsp;&gt;&nbsp;安全中心 </div>
</div>

<!--右侧显示内容-->

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
					<div class="setright">
						<span class="textsuccess">已设置</span>
						<span style="color:gray;">&nbsp;|&nbsp;</span>
						<a href="loginpassword.html" name="flag">修改</a>
					</div>
				</div>
			</li>
			<li> <span class="mingzi">绑定银行卡&nbsp;&nbsp;</span>
				<?php if(($is_user_bind) != null): ?>				
				<div class="set_rightb"> <span style="">银行卡已绑定</span>
					<div class="setright"><span class="textsuccess">已绑定</span>
						<span style="color:gray;">&nbsp;|&nbsp;</span>
						<a href="/index.php/bank/bind.html" name="flag">修改</a>
					</div>
				</div>
				<?php else: ?>
				<div class="set_rightb"> <span style="">未绑定银行卡，暂时无法进行出入金操作，请先绑定银行卡</span>
					<div class="setright"><span style="color: red;">未绑定</span>
						<span style="color:gray;">&nbsp;|&nbsp;</span>
						<a href="/index.php/bank/bind.html" name="flag">绑定</a>
					</div>
				</div>
				<?php endif; ?>
			</li>
		</ul>
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
						<img class="footer-log" src="/img/footlogo.png">
					</div>
					<div class="col-md-5">
						<ul>
							<li class="th">联系我们</li>
							<li>我们在聆听，在服务中进步</li>
							<li><img src="/img/footer-tel.png">国内客服热线：（工作日 8：30~17：30） </li>
							<li><img src="/img/footer-adr.png">地址：</li>
							<li style="">©&nbsp;2016-2018逸富版权所有</li>
						</ul>
					</div>
				</div>
			</footer>


			<script type="text/javascript" src="/js/jquery-2.1.4.js"></script>
			<script type="text/javascript" src="/js/bootstrap.min.js"></script>
			<script type="text/javascript" src="/js/login.js"></script>
			<script type="text/javascript" src="/js/layer.js"></script>

<!--自定义页面js-->


<!--结束-->

	</body>
</html>
