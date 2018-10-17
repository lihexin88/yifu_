<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:86:"D:\phpStudy\WWW\yifu\Interface\public/../application/index\view\withdrawal\record.html";i:1539687860;s:69:"D:\phpStudy\WWW\yifu\Interface\application\index\view\base\index.html";i:1539676179;}*/ ?>
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

		<title>出金</title>

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

							<li class="submeun-4"><i class="ioc" style="top: 12px;"></i>
								<a class="l-m-icon" href="/withdrawal/apply.html">出金</a>
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

							<li class="submeun-6"><i class="ioc active"></i>
								<a class="l-m-icon active" href="/withdrawal/record.html">出金记录</a>
								<span class="active"></span>
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
						<div class="container"> 管理系统&nbsp;&gt;&nbsp;出金记录 </div>
					</div>

<!--右侧显示内容-->

<h1 class="page_title">出金记录</h1>
	<div class="full" id="dg1">
		<table class="table table-bordered tablerecord table-hover" width="100%" align="center">
			<thead>
				<tr class="tr-th-text-center">
					<th>出金时间</th>
					<th>出金金额(RMB)</th>
					<th>实际金额</th>
					<th>手续费</th>
					<th>出金状态</th>
					<th>审核时间</th>
					<th>备注</th>
				</tr>
			</thead>
			<tbody id="table">
				
				<?php if(is_array($withdraw_log) || $withdraw_log instanceof \think\Collection || $withdraw_log instanceof \think\Paginator): $i = 0; $__LIST__ = $withdraw_log;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$withdraw_log): $mod = ($i % 2 );++$i;?>
					<tr>
						<td><?php echo date("Y-m-d H:i:s",$withdraw_log['time']); ?></td>
						<td><?php echo $withdraw_log['number']; ?></td>
						<td><?php echo $withdraw_log['total']; ?></td>
						<td><?php echo $withdraw_log['fee']; ?></td>
						<td><?php if(($withdraw_log['status']) == 0): ?>审核中<?php elseif(($withdraw_log['status']) == 1): ?>提现成功<?php elseif(($withdraw_log['status'] == 2)): ?>提现失败<?php elseif(($withdraw_log['status'] == 3)): ?>已撤回<?php endif; ?></td>
						<td></td>
						<td><?php echo $withdraw_log['remark']; ?></td>
					</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
		<!--显示分页-->
		<?php echo $withdraw_page; ?>
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


<!--结束-->

	</body>
</html>
