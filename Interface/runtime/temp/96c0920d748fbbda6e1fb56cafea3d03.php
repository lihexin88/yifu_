<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:83:"D:\phpStudy\WWW\yifu\Interface\public/../application/index\view\recharge\index.html";i:1539918819;s:69:"D:\phpStudy\WWW\yifu\Interface\application\index\view\base\index.html";i:1539943090;}*/ ?>
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

		<title>入金</title>

<!--头部结束-->

	</head>
	<body>

<!--自定义css样式-->

<style type="text/css">
	.dlmain,
	.pageright {
		width: initial;
	}
	
	.liang {
		color: #aa5800;
	}
	
	.moremony span.warning {
		font-size: 1.4rem;
		color: #ccc;
	}
	
	.moremony .male,
	.moremony .female {
		position: relative;
		width: 6rem;
		height: 3.9rem;
		z-index: 1;
		line-height: 3.9rem;
		text-align: center;
		float: left;
		margin-right: 2rem;
		margin-bottom: 0.4rem;
	}
	
	.moremony .male label,
	.moremony .female label {
		position: absolute;
		top: 0;
		bottom: 0;
		left: 0;
		width: 6rem;
		height: 3.9rem;
		z-index: 3;
		opacity: 0;
		margin: auto;
		display: inline-block;
		line-height: 3.9rem;
		cursor: pointer;
	}
	
	.moremony input {
		display: inline-block;
		vertical-align: middle;
		/*让默认的单选样式的圆圈和“男”“女”的文本没有高差，看起来在同一水平线*/
		height: 2.8rem;
		line-height: 2.8rem;
		margin: 0;
		/*清除浏览器默认的外边距*/
	}
	
	.moremony .male span.btn,
	.moremony .female span.btn {
		position: absolute;
		top: 0;
		bottom: 0;
		left: 0;
		width: 6rem;
		height: 3.8rem;
		z-index: 2;
		margin: auto;
		display: inline-block;
		line-height: 2.6rem;
		text-align: center;
		border: .1rem solid #3498db;
		color: #3498db;
	}
	
	.moremony .male span {
		border-top-left-radius: .2rem;
		border-bottom-left-radius: .2rem;
	}
	
	.moremony .female span {
		border-top-right-radius: .2rem;
		border-bottom-right-radius: .2rem;
	}
	
	.moremony .male span.active,
	.moremony .female span.active {
		background-color: #3498db;
		color: #fff;
	}
	
	.pay {
		width: 58px;
		height: 30px;
		display: inline-block;
		background: #fff;
		border-radius: 3px;
	}
	
	.pay.sxy {
		background: #fff url(../tpl/efext_theme_def/assets/images/sxy1.png) no-repeat;
		float: left;
		margin-left: 2px;
	}
	
	.pay-active {
		background: #f00;
	}
	
	.pay-active:after {
		content: "";
		display: block;
		width: 100%;
		height: 30px;
		border-radius: 3px;
		border: 2px solid #0096db;
		background: url(/tpl/efext_theme_def/assets/images/checked2.png) no-repeat 0 -3px;
		box-sizing: border-box;
	}
	
	.rtype {
		width: 85px;
		height: 50px;
		display: inline-block;
		background: #fff;
		border-radius: 3px;
	}
	
	.rtype.pz {
		background: #fff url(/tpl/efext_theme_def/assets/images/pzrj2.png) no-repeat;
	}
	
	.rtype.zj {
		background: #fff url(/tpl/efext_theme_def/assets/images/zjzj2.png) no-repeat;
	}
	
	.rtype-active {
		background: #f00;
	}
	
	.rtype-active:after {
		content: "";
		display: block;
		width: 100%;
		height: 38px;
		border-radius: 3px;
		border: 2px solid #0096db;
		background: url(/tpl/efext_theme_def/assets/images/checked1.png) no-repeat 0 -3px;
		box-sizing: border-box;
	}
	
	.rujin_yinhangName {
		font-size: 14px;
		width: 190px;
		text-align: right;
		float: left;
		line-height: 54px;
		margin-right: 10px;
	}
	
	.bank {
		float: left;
		width: 555px;
	}
	
	.bank li {
		float: left;
		font-size: 16px;
		width: 90px;
		text-align: center;
		height: 30px;
		line-height: 30px;
		margin-right: 5px;
		margin-bottom: 5px;
		color: #fff;
		background: #676765;
		cursor: pointer;
	}
	
	.bank li span {
		display: none;
	}
	
	.bank .bank_active {
		background: #FA4846;
	}
	
	.first {
		margin-bottom: 5px;
	}
	
	.form-horizontal {
		margin: 20px 0 50px !important;
	}
	
	.form-group .control-label {
		line-height: 40px;
	}
</style>

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

<a class="ground-item active" href="/recharge/index.html" style="text-decoration:none;">入金</a>

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

							<li class="submeun-2"><i class="ioc"></i>
								<a class="l-m-icon" href="/security/index.html">安全中心</a>
							</li>
						</ul>
						<h3 class="myaccount">我的资金</h3>
						<ul class="menu-son">

<!--左侧边--入金-->

<li class="submeun-3"><i class="ioc active"></i>
	<a class="l-m-icon" href="/recharge/index.html">入金</a>
	<span class="active"></span>
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

<input type="hidden" id="ACTIVE_MENU" value="submeun-1" autocomplete="off">
<div class="crumbs">
	<div class="container"> 管理系统&nbsp;&gt;&nbsp;个人中心 &nbsp;&gt;&nbsp;入金</div>
</div>

<!--右侧显示内容-->
 
<div>
		<div class="page_title"> 入金&nbsp;&nbsp; </div>
		<form action="rujin.html" method="POST" onsubmit="return doPay();" class="form-horizontal edit-frm" target="_blank">
			<div style="margin-bottom: 0;">
				<label class="col-sm-2 control-label" style="width: 199px !important;">支付方式<span class="required" aria-required="true">*</span></label>
				<div style="display:inline-block;line-height: 56px;padding-left:16px;">
					<label for="c" style="font-size: 16px;" onclick="bank.style.display=&#39;none&#39;;clickmoney.style.display=&#39;block&#39;;rate=0.006;$(&#39;#amount&#39;).trigger(&#39;keyup&#39;);">
							<input type="radio" id="c" value="1" name="pay_radio" class="tongdao" checked="checked" autocomplete="off">&nbsp;&nbsp;支付宝支付
						</label>
					<span style="color:red;padding-left:6px;" id="recharge_ratio">(千6费率)</span>
				</div>
				<div style="display: inline-block;line-height: 56px;">
					<label for="g" style="font-size: 16px;">
			            	<input type="radio" value="2" id="g" value="13" name="pay_radio" class="tongdao" disabled="disabled" readonly="readonly" autocomplete="off">&nbsp;&nbsp;网银支付
						</label>
					<span style="color:red;padding-left:6px;">(通道维护)</span>
				</div>
				<div style="display:inline-block;line-height: 56px;padding-left:16px;">
					<label for="h" style="font-size: 16px;" onclick="bank.style.display=&#39;none&#39;;clickmoney.style.display=&#39;none&#39;;rate=0.000;$(&#39;#amount&#39;).trigger(&#39;keyup&#39;);">
			            	<input type="radio" id="h" value="3" name="pay_radio" class="tongdao" autocomplete="off">&nbsp;&nbsp;线下转账
						</label>
					<span style="color:red;padding-left:6px;">(线下入金暂免费率)</span>
				</div>
			</div>
			<div class="first clearfix" id="bank" style="display: none;">
				<div class="rujin_yinhangName"></div>
				<div class="rujin_yinhangName" style="margin-top: 20px; width:180px !important;font-size:16px;">选择银行：</div>
				<ul class="bank" style="margin-top: 16px;" id="tcontent">
					<li>建设银行<span>建设银行</span></li>
					<li>民生银行<span>民生银行</span></li>
					<li>工商银行<span>工商银行</span></li>
					<li>光大银行<span>光大银行</span></li>
					<li>广发银行<span>广发银行</span></li>
					<li>农业银行<span>农业银行</span></li>
					<li>邮储银行<span>邮储银行</span></li>
					<li>北京银行<span>北京银行</span></li>
				</ul>
				<input type="hidden" name="bankid" id="tresult" autocomplete="off">
			</div>
			<div class="form-group" id="clickmoney" style="display: block;">
				<label class="col-sm-2 control-label" style="width: 199px !important;">快捷选择<span class="required" aria-required="true">*</span></label>
				<div class="col-md-8">
					<div class="moremony">
						<div class="select fl">
							<div class="female fl">
								<label for="female" onclick="ccc(this)"><input type="radio" class="balance" name="" value="500" autocomplete="off"></label><span class="btn">500</span>
							</div>
							<div class="female fl">
								<label for="female" onclick="ccc(this)"><input type="radio" class="balance" name="" value="1000" autocomplete="off"></label><span class="btn">1000</span>
							</div>
							<div class="female fl">
								<label for="female" onclick="ccc(this)"><input type="radio" class="balance" name="" value="2000" autocomplete="off"></label><span class="btn">2000</span>
							</div>
							<div class="female fl">
								<label for="female" onclick="ccc(this)"><input type="radio" class="balance" name="" value="3000" autocomplete="off"></label><span class="btn">3000</span>
							</div>
							<div class="female fl">
								<label for="female" onclick="ccc(this)"><input type="radio" class="balance" name="" value="5000" autocomplete="off"></label><span class="btn">5000</span>
							</div>
							<div class="female fl">
								<label for="female" onclick="ccc(this)"><input type="radio" class="balance" name="" value="10000" autocomplete="off"></label><span class="btn">10000</span>
							</div>
							<div class="female fl">
								<label for="female" onclick="ccc(this)"><input type="radio" class="balance" name="" value="20000" autocomplete="off"></label><span class="btn">20000</span>
							</div>
							<div class="female fl">
								<label for="female" onclick="ccc(this)"><input type="radio" class="balance" name="" value="50000" autocomplete="off"></label><span class="btn">50000</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" style="width: 199px !important;padding-top: 0;">充值金额<span class="required" aria-required="true">*</span></label>
				<div class="col-md-4">
					<input type="number" step="0.01" min="0.01" id="amount" class="form-control required chinaMobile" name="amount" onkeyup="amount_change(this.value)" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" style="color:red;font-weight:bold;font-size:15px;"></label>
				<div class="col-md-8">
					<span style="line-height:25px;font-size:14px;"> 汇率：<span id = "rmbt_usd" style="font-weight:bold;color:red">-</span>&nbsp;&nbsp; 手续费：
					<span style="font-weight:bold;color:red" id="sxf">0</span> 元&nbsp;&nbsp; 美元：
					<span style="font-weight:bold;color:red" id="sjrj">0</span>美元 </span>
				</div>
			</div>
			<br>
			<div class="form-group">
				<label class="col-sm-2 control-label" style="color:red;font-weight:bold;font-size:15px;">入金说明 :</label>
				<div class="col-md-8" style="padding-top: 15px;">
					<span style="line-height:25px;font-size:14px;color:red"> 
							<span style="font-weight:bold">入金人民币将按固定汇率自动转换成美元到逸富账户 </span> <br>
					</span>
				</div>
			</div>
			<div class="form-group">
				<div style="margin: 9px 0 0 158px;">
					<button type="submit" id="btn_sub" class="btn btn-primary">入金</button>
				</div>
			</div>
		</form>
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
	var exchange_rate = 0.0;
	var rate = 0.0;
	$.ajax({
		type:"post",
		url:"/recharge/get_config",
		success:function(result){
			if(result['code'] == 1){
				//汇率
				exchange_rate = result['data']['usdt_rmb'];
				//充值手续费
				recharge_rate = result['data']['withdraw_ratio'];
				//提现手续费
				withdraw_rate = result['data']['recharge_ratio'];
//				console.log(rate+"asdfasdf"+exchange_rate+"asf"+withdraw_rate);return false;
				$("#recharge_ratio").html("(千"+1000*recharge_rate+"费率)");
				$("#rmbt_usd").html(exchange_rate);
			}
//			console.log(result);
		},
		async:true
	});
		//			$(".tongdao").on('click', function() {
		//				$(".tongdao").removeAttr("checked");
		//				$(this).attr("checked", "checked");
		//			});
		<!--点击单选框按钮-->
		function ccc(e) {
			var money = $(e).children().val();
			$('#amount').val(money).trigger('keyup');
		}

		function amount_change(money) {
			var sxf = 0;
			var sxf = (money * 1) * rate;
			var sjrj = (money * 1 - sxf) / exchange_rate;
			document.getElementById("sxf").innerHTML = sxf.toFixed(2);
			document.getElementById("sjrj").innerHTML = sjrj.toFixed(2);
		}

		function doPay() {
			//				console.log();
			//				alert;
			if(!$("#amount").val()) {
				layer.alert("请输入金额！");
				return false;
			}
			recharge();
			return false;
			layer.confirm('请在新页面完成付款', {
				btn: ['付款成功', '付款失败']
			}, function() {
				location.href = "/withdrawal/record.html";
			}, function() {
				layer.msg('请重新付款');
			});
			return true;
		}
		function recharge() {
			$.ajax({
				type: "post",
				dataType: "json",
				data: {
					pay_type: $("input[name='pay_radio']:checked").val(),
					amount: $("#amount").val(),
				},
				url: "/recharge/post_recharge",
				success: function(result) {
//					console.log(result);return false;
					if(result['code'] == 1){
						layer.alert("入金申请已提交",{time:1000});
						window.location.href = "/recharge/record";						
					}else{
						layer.alert(result['msg']);
						return false;
					}
				},
				error: function(result) {
					console.log(result);
				},
				async: true
			});
		}
	</script>

<!--结束-->

	</body>
</html>
