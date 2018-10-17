<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"D:\phpStudy\WWW\yifu\Interface\public/../application/index\view\bank\bind.html";i:1539676152;s:69:"D:\phpStudy\WWW\yifu\Interface\application\index\view\base\index.html";i:1539676179;}*/ ?>
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

<title>绑定银行卡</title>

<!--头部结束-->

	</head>
	<body>

<!--自定义css样式-->

<link rel="stylesheet" type="text/css" href="../css/layer.css"/>

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
			
				<a class="ground-item" href="/home/index" style="text-decoration:none;">个人中心</a>

<!--首部--入金-->

				<a class="ground-item" href="/recharge/index.html" style="text-decoration:none;">入金</a>

<!--首部 -- 出金-->

				<a class="ground-item" href="/withdrawal/apply.html" style="text-decoration:none;">出金</a>

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

							<li class="submeun-2"><i class="ioc active"></i>
								<a class="l-m-icon active" href="/security/index.html">安全中心</a><span class="active"></span>
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

					<div class="crumbs">
						<div class="container">管理系统&nbsp;&gt;&nbsp;银行卡绑定	</div>
					</div>

<!--右侧显示内容-->

					<h1 class="page_title">绑定银行卡</h1>
					<div style="margin: 0;padding: 10px;">
						<ul class="bank-info">
							<li>
								<span>持卡人：</span>
								<span><?php echo $user_bank_info['name']; ?></span>
							</li>

							<li>
								<span>银行名称：</span>
								<span><?php echo $user_bank_info['bank_name']; ?></span>
							</li>

							<li>
								<span>银行卡号：</span>
								<span><?php if(($user_bank_info['bank_card'] != Null)): ?>
										<?php echo $user_bank_info['bank_card']; else: ?>
										<input style="border: 1px solid grey;line-height: 10px;" type="text" name="bank_card" id="bank_card" placeholder="输入银行卡号进行绑定" />
										<select name="bank_name"  id = "input_bankItems"><option>加载中...</option></select>
										<?php endif; ?>
								</span>
								<span>
			                    	<a href="javascript:void(0);" class="btn btn-primary" <?php if(($user_bank_info['bank_card'] != Null)): ?> onclick="return unbindBank(this,'unbind');" data-cleartext="6217856300005629491">解绑<?php else: ?> onclick="return unbindBank(this,'bind');" >绑定<?php endif; ?></a>
								</span>
							</li>
						</ul>
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

		<!--<div class="layui-layer-move"></div>-->
		<script type="text/javascript" src="../js/layer.js"></script>
		<script type="text/javascript" src="../js/app.min.js"></script>
		<script type="text/javascript" src="../layui/layui.all.js" ></script>
		<script type="text/javascript">
			$(function() {
				$('.actfrm').on('submit', function() {
					var _this = $(this);
					App.getJSON(_this.attr('action'), _this, function(res) {
						if(res.code) {
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

			function unbindBank(ele,card_operate_type) {
//				console.log(card_operate_type);return false;
				if(card_operate_type == "bind"){
					//绑定操作
//					console.log(card_operate_type);
					var true_length = new Array(16,18,19);
					var data = {"user_id":$("#user_id").val(),"bank_card":$("#bank_card").val(),"bank_name":$("#input_bankItems").val()};
//					console.log(data['bank_card'].length);
					if((jQuery.inArray(data['bank_card'].length,true_length))){
						layer.alert("银行卡位数不正确",{
							title:'温馨提示',
						});
						$("#bank_card").focus();
						return false;
					}
					$.ajax({
						type:"post",
						data:data,
						url:"/bank/bind_card",
						success:function(result){
							layer.alert(result['msg']+'_ _ _ 自动加载中...',{title:'温馨提示'});									
							setTimeout(
								function()
								{
									window.location.reload();
								},1500
							)
						},
						async:true
					});
					return false;
				}
				//解除绑定
				layer.confirm('是否确认解绑该银行卡？', {
					closeBtn: 0,
					title: '温馨提示',
					btn: ['确定', '取消']
				}, function() {
					var bank_card = $(ele).data('cleartext');
//					return false;
					data = {"user_id":$("#user_id").val(),"bind_bank":bank_card};
					$.ajax({
						type:"post",
						data:data,
						url:"/bank/unbind/",
						success:function(result){	
//							console.log(result);return false;
							layer.alert(result['msg']+'_ _ _ 自动加载中...',{title:'来自后台消息'});
							setTimeout(
								function(){									
									if(result['code'] == 1){
										window.location.reload();
									}
								},1500
							)
						},
						error:function(result){
//							console.log(result);
							layer.alert('执行错误!请稍后重试！');
						},
						async:true
					});
				});
				return false;
			}

			function loadEfSupportBanks() {
				App.getJSON("/bank/efsupportbanks.html", '', function(res) {
//					console.log(res);
					if(res.code) {
						var banks = res.data;
						var options = '';
						for(var i = 0; i < banks.length; i++) {
							options += '<option value="' + banks[i].name + '">' + banks[i].name + '</option>';
						}
						$('#input_bankItems').html(options);
					} else {}
				});
			}
		</script>
		<?php if(($user_bank_info['bank_card'] == Null)): ?>
			<script>loadEfSupportBanks()</script>
		<?php endif; ?>

<!--结束-->

	</body>
</html>
