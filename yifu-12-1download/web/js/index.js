/*是否记住账号*/
localStorage.removeItem("token");
if(localStorage.getItem("quotation_name")) {
	$(".login_name1").val(localStorage.getItem("quotation_name"));
	$(".remember_type1").prop("checked", true);
}
if(localStorage.getItem("deal_name")) {
	$(".login_name2").val(localStorage.getItem("deal_name"));
	$(".remember_type2").prop("checked", true);
}
/*是否记住账号 end*/
//行情登录  交易登录 切换
$(".login_way p").click(function() {
	$(this).addClass("login_wayact");
	$(".login_way p").not(this).removeClass("login_wayact");
	if($(this).attr("type") == 1) {
		//行情登录
		$(".login_deal").hide();
		$(".login_mar").show();
	} else {
		//交易登录
		$(".login_mar").hide();
		$(".login_deal").show();
	}
});
//点击 忘记密码
$(".forget").click(function() {
	$(".login_input").hide();
	$(".register").hide();
	$(".login_repass").show();
	$(".back").show();
});
//注册 找回密码 返回按钮
$(".back").click(function() {
	$(".login_input").show();
	$(".login_repass").hide();
	$(".register").hide();
	$(".back").hide();
});
//点击 注册
$(".register_btn").click(function() {
	$(".login_input").hide();
	$(".register").show();
	$(".login_repass").hide();
	$(".back").show();
});
//服务器地址
function index_info() {
	$.post(globalUrl, {
		nozzle: "index_info",
		token: localStorage.getItem("token")
	}, function(res) {
		//行情服务器
		var quote = res.data.quote;
		//交易服务器
		var trade = res.data.trade;
		for(var i = 0; i < quote.length; i++) {
			var html = '<option value="' + quote[i].id + '">' + quote[i].name + '</option>'
			$(".service_type1").append(html);
		}
		for(var i = 0; i < trade.length; i++) {
			var str = '<option value="' + trade[i].id + '">' + trade[i].name + '</option>'
			$(".service_type2").append(str);
		}
	});
};
index_info();
/*登录验证*/
//行情登陆
$(".login_btn1").click(function() {
	var service_type1 = $(".service_type1").val();
	var login_name1 = $(".login_name1").val();
	var login_pass1 = $(".login_pass1").val();
	var remember_type1 = $(".remember_type1").prop("checked");
	var certify = verification("No", login_name1, login_pass1, "No", "No");
	if(certify == true) {
		//验证通过了去请求吧
		$.post(globalUrl, {
			nozzle: "login",
			phone: login_name1,
			password: login_pass1,
			type: 0
		}, function(res) {
			var data = handle_data(res);
			if(data.citify == true) {
				if(remember_type1 == true) {
					window.localStorage.setItem("quotation_name", login_name1);
				} else {
					localStorage.removeItem("quotation_name");
				}
				window.localStorage.setItem("login_type", 1);
				window.localStorage.setItem("token", data.data.token);
				setTimeout(function() {
					window.location.href = "market.html";
				}, 1500);
			};
		});
	}
});
//交易登录
$(".login_btn2").click(function() {
	var service_type2 = $(".service_type2").val();
	var login_name2 = $(".login_name2").val();
	var login_pass2 = $(".login_pass2").val();
	var remember_type2 = $(".remember_type2").prop("checked");
	var certify = verification("No", login_name2, login_pass2, "No", "No");
	if(certify == true) {
		//验证通过了去请求吧http://yfserve.bjfable.com/
		$.post(globalUrl, {
			nozzle: "login",
			phone: login_name2,
			password: login_pass2,
			type: 1
		}, function(res) {
			var data = handle_data(res);
			if(data.citify == true) {
				if(remember_type2 == true) {
					window.localStorage.setItem("deal_name", login_name2);
				} else {
					localStorage.removeItem("deal_name");
				}
				window.localStorage.setItem("login_type", 2);
				window.localStorage.setItem("token", data.data.token);
				setTimeout(function() {
					window.location.href = "market.html";
				}, 1500);
			};
		});
	}
});
/*登录验证 end*/
/*注册找回密码验证*/
$(".forgot_btn").click(function() {
	var forget_phone = $(".forget_phone").val();
	var forget_pass = $(".forget_pass").val();
	var forget_confirm = $(".forget_confirm").val();
	var forget_code = $(".forget_code").val();
	var certify = verification(forget_phone, "No", forget_pass, forget_confirm, forget_code);
	if(certify == true) {
		//验证通过了去请求吧
		alert(1);
	}
});
//找回密码 获取验证码
$(".get_forgetcode").click(function() {
	var forget_phone = $(".forget_phone").val();
	if(!(/^1(3|4|5|6|7|8|9)\d{9}$/.test(forget_phone))) {
		show_tilps("red", "手机号码格式不正确", 1500);
	} else {
		var time = 60;
		$(".get_forgetcode").attr("disabled", true);
		$(".get_forgetcode").css("background", "#bbb");
		$(".get_forgetcode").html(time + "s");
		var timer1 = setInterval(function() {
			time--;
			$(".get_forgetcode").html(time + "s");
			if(time < 0) {
				clearInterval(timer1);
				$(".get_forgetcode").attr("disabled", false);
				$(".get_forgetcode").css("background", "#F68C04");
				$(".get_forgetcode").html("获取验证码");
			}
		}, 1000);
	}
});
//注册 获取验证码
$(".register_getcode").click(function() {
	var register_phone = $(".register_phone").val();
	if(!(/^1(3|4|5|6|7|8|9)\d{9}$/.test(register_phone))) {
		show_tilps("red", "手机号码格式不正确", 1500);
	} else {
		var time = 60;
		$(".register_getcode").attr("disabled", true);
		$(".register_getcode").css("background", "#bbb");
		$(".register_getcode").html(time + "s");
		var timer2 = setInterval(function() {
			time--;
			$(".register_getcode").html(time + "s");
			if(time < 0) {
				clearInterval(timer2);
				$(".register_getcode").attr("disabled", false);
				$(".register_getcode").css("background", "#F68C04");
				$(".register_getcode").html("获取验证码");
			}
		}, 1000);
	}
});
//注册验证
$(".register_click").click(function() {
	var register_phone = $(".register_phone").val();
	var register_pass = $(".register_pass").val();
	var register_confirm = $(".register_confirm").val();
	var register_code = $(".register_code").val();
	var certify = verification(register_phone, "No", register_pass, register_confirm, register_code);
	if(certify == true) {
		//验证通过了去请求吧
		alert(1);
	}
});
/*注册找回密码验证 end*/