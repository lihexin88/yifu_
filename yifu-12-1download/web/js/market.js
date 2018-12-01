/*交易账号 记住账号*/
if(localStorage.getItem("account_name")) {
	$(".deal_account").val(localStorage.getItem("account_name"));
	$("#remember_account").prop("checked", true);
};

function handel_link() {
	var login_type = Number(localStorage.getItem("login_type"));
	if(login_type == 1) {
		//行情登录
		$(".computer > div:eq(1)").css("background", "url(img/computer.gif) no-repeat right center/16px 16px");
		$(".computer > div:eq(0)").css("background", "url(img/computer2.png) no-repeat right center/16px 16px");

	} else if(login_type == 2) {
		//交易登录
		$(".computer > div:eq(0)").css("background", "url(img/computer.gif) no-repeat right center/16px 16px");
		$("#my_views").attr("src", "my/my_hold.html");
		//请求账户信息
		account();
	}
};
handel_link();
//请求账户信息
function account() {
	$.post(globalUrl, {
		nozzle: "account",
		token: localStorage.getItem("token")
	}, function(res) {
		var data = handle_data(res);
		if(data.citify == true) {
			$(".zhanghu").html(data.data.account); //账户
			$(".quanyi").html(data.data.total); //权益（总资产）
			$(".close_profit").html(data.data.close_profit); //平仓盈亏
			$(".hold_profit").html(data.data.open_profit); //持仓盈亏
			$(".bond").html(data.data.frozen_bond); //保证金
			$(".can_use").html(data.data.day_withdraw); //可用
			$(".fengxian").html(data.data.risk_control); //风险服
			$(".fengkong").html(data.data.account); //风控线
		}
	});
};
//监控网络状态
window.addEventListener('online', function() {
	handel_link();
});
window.addEventListener('offline', function() {
	$(".computer > div").css("background", "url(img/computer2.png) no-repeat right center/16px 16px");
});
//点击屏幕  隐藏元素 公用函数
$(document).click(function() {
	$(".system").slideUp(50);
	$(".set_up_cont").slideUp(50);
	$(".system_list2").slideUp(50);
	$(".sleeve_box").slideUp(50);
	$(".contact_type").slideUp(50);
	$(".contact_val_box").slideUp(1);
	$(".price_type").slideUp(50);
});
//显示风险揭示书
function show_tips() {
	if(localStorage.getItem("book_show") == null && Number(localStorage.getItem("login_type")) == 2) {
		$.post(globalUrl, {
			nozzle: "protocol",
			type: 2,
			token: localStorage.getItem("token")
		}, function(res) {
			var data = handle_data(res);
			if(data.citify == true) {
				//				$(".module1_tittle_color").html(data.data.title);
				$(".module1_color").html(data.data);
				$(".module_box1").fadeIn(50);
			}
		});
	};
};
show_tips();
$(".module1_button button").click(function() {
	var type = $(this).attr("type");
	if(type == 1) {
		//同意
		window.localStorage.setItem("book_show", 1);
		$(".module_box1").fadeOut(50);
	} else if(type == 2) {
		//退出
		window.location.href = "index.html";
	}
});
$(".book_tittle span").click(function() {
	//退出
	window.location.href = "index.html";
});
//风险揭示书 end
//关于我们
function about_module() {
	$.post(globalUrl, {
		nozzle: "about",
	}, function(res) {
		var data = handle_data(res);
		if(data.citify == true) {
			console.log(data.data);
			$(".module2_cont > p:eq(0)").html(data.data.cn_name);
			$(".module2_cont > p:eq(1)").html(data.data.en_name);
			$(".module_code").html(data.data.version);
			$(".module_box2").fadeIn(50);
		};
	});
};
$(".about_tittle > span,.module2_bottom > button").click(function() {
	$(".module_box2").fadeOut(50);
});
//关于我们end
//自选 行情 分时 切换
$(".slider_text li").click(function() {
	$(this).addClass("slider_textAct");
	$(".slider_text li").not(this).removeClass("slider_textAct");
});
/*底部交易操作*/
//下单方式 样式 切换样式切换元素
$(".make_order_way p").click(function() {
	//父窗口 获取 子窗口元素的 方法
	//	console.log($("#list_table").contents().find(".market_box").attr("data"));
	$(this).addClass("make_order_wayAct");
	$(".make_order_way p").not(this).removeClass("make_order_wayAct");
	var type = $(this).attr("type");
	$(".make_order_way").attr("type", type);
	if(type == 1) {
		//两键下单
		$(".radio_buy_sell").css("display", "none");
		$(".ordinary_buy").css("display", "none");
		$(".open_ping").css("display", "none");
		$(".can_ping").css("display", "none");
		$(".sell_btn").css("display", "none");
		$(".price_input > input").removeClass("width158");
		//三键下单 按钮长度变化
		$(".sell_buy").removeClass("sell_buy_active");
		$(".sell_btn").removeClass("sell_btn_active");

		$(".ping_way").css("display", "block");
		$(".buy_sell_list").css("display", "block");
		$(".sell_buy").css("display", "flex");
		$(".price > small").css("display", "inline-block");
		$(".line_bottom").css("display", "inline-block");
	} else if(type == 2) {
		//三键下单
		$(".radio_buy_sell").css("display", "none");
		$(".ordinary_buy").css("display", "none");
		$(".open_ping").css("display", "block");
		$(".can_ping").css("display", "none");
		$(".sell_btn").css("display", "none");
		$(".price_input > input").removeClass("width158");
		//三键下单 按钮长度变化
		$(".sell_buy").addClass("sell_buy_active");
		$(".sell_btn").addClass("sell_btn_active");

		$(".ping_way").css("display", "none");
		$(".buy_sell_list").css("display", "none");
		$(".sell_btn").css("display", "block");
		$(".can_ping").css("display", "block");
		$(".sell_buy").css("display", "flex");
		$(".price > small").css("display", "inline-block");
		$(".line_bottom").css("display", "none");
	} else if(type == 3) {
		//普通下单
		$(".radio_buy_sell").css("display", "inline-block");
		$(".ordinary_buy").css("display", "block");
		$(".open_ping").css("display", "none");
		$(".can_ping").css("display", "none");
		$(".sell_btn").css("display", "none");
		$(".price_input > input").addClass("width158");
		$(".ping_way").css("display", "block");
		$(".buy_sell_list").css("display", "block");
		//三键下单 按钮长度变化
		$(".sell_buy").removeClass("sell_buy_active");
		$(".sell_btn").removeClass("sell_btn_active");

		$(".sell_buy").css("display", "none");
		$(".price > small").css("display", "none");
		$(".line_bottom").css("display", "inline-block");
	}
});
//type=1我的持仓  type=2 成交记录  type=3 交易委托  type=4 我的条件单 切换
$(".my_title > button").click(function() {
	$(this).addClass("my_titleAct1");
	$(".my_title > button").not(this).removeClass("my_titleAct1");
	var type = $(this).attr("type");
	if(type == 1) {
		//我的持仓
		$("#my_views").attr("src", "my/my_hold.html");
		$(".my_btn").css("display", "inline-block");
		$(".entrust_btn").css("display", "none");
		$(".condition_btn").css("display", "none");
	} else if(type == "2") {
		//成交记录
		$("#my_views").attr("src", "my/deal_record.html");
		$(".my_btn").css("display", "none");
		$(".entrust_btn").css("display", "none");
		$(".condition_btn").css("display", "none");
	} else if(type == 3) {
		//交易委托
		$("#my_views").attr("src", "my/entrustment.html");
		$(".my_btn").css("display", "none");
		$(".entrust_btn").css("display", "inline-block");
		$(".condition_btn").css("display", "none");
	} else if(type == 4) {
		//我的条件单
		$("#my_views").attr("src", "my/condition.html");
		$(".my_btn").css("display", "none");
		$(".entrust_btn").css("display", "none");
		$(".condition_btn").css("display", "inline-block");
	}
});
/*底部交易操作 end*/
//收起交易信息
$(".down_deal").click(function() {
	toggle_updown();
});
/*侧边栏自选  行情 K线*/
//点击自选  更新列表数据
$(".slider_text li:eq(0)").click(function() {
	$("#list_table").attr("src", "self_list.html");
	$(".iframe_footer").css("display", "none");
	delete_class(2);
});
//点击行情
$(".slider_text li:eq(1)").click(function() {
	$("#list_table").attr("src", "market_lable.html");
	$(".iframe_footer").css("display", "block");
	$(".exchange_listAct").removeClass("exchange_listAct");
	$(".exchange_list > li:eq(0)").addClass("exchange_listAct");
	delete_class(3);
});
//点击分时数据
$(".slider_text li:eq(2)").click(function() {

	$("#list_table").attr("src", "k_line.html");
	$(".iframe_footer").css("display", "none");
	delete_class(4);
});
//点击K线数据
$(".slider_text li:eq(3)").click(function() {

	$("#list_table").attr("src", "k_line.html");
	$(".iframe_footer").css("display", "none");
	delete_class(5);
});
/*侧边栏自选  行情 K线  end*/
/*页面顶部功能按钮*/
$(".title_text1 li").click(function() {
	var index = $(this).index() + 1;
	var str = "title_text1Act" + index;
	$(this).addClass(str);
	delete_class(index);
});
//删除顶部 所有 样式
function delete_class(index) {
	var num = index - 1;
	$(".title_text1 li:eq(" + num + ")").addClass("title_text1Act" + index);
	for(var i = 1; i <= $(".title_text1 li").length; i++) {
		var oter_class = "title_text1Act" + i;
		if(i != index) {
			$(".title_text1 li").removeClass(oter_class);
		}
	}
};
//index = 1:返回上一页
function back() {
	javascript: history.back(-1);
};
//index = 2自选页面
function get_selfdata() {
	//右侧样式切换
	$(".slider_text >li").addClass("slider_textAct");
	$(".slider_text >li").not(".slider_text >li:eq(0)").removeClass("slider_textAct");
	//更新数据
	$("#list_table").attr("src", "self_list.html");
	$(".iframe_footer").css("display", "none");
};
//index = 3行情按钮
function get_marketdata() {
	//右侧样式切换
	$(".slider_text >li:eq(1)").addClass("slider_textAct");
	$(".slider_text >li").not(".slider_text >li:eq(1)").removeClass("slider_textAct");
	$("#list_table").attr("src", "market_lable.html");
	$(".iframe_footer").css("display", "block");
	$(".exchange_listAct").removeClass("exchange_listAct");
	$(".exchange_list > li:eq(0)").addClass("exchange_listAct");
};
//index = 4 分时
function get_timedata() {
	//右侧样式切换
	$(".slider_text >li:eq(2)").addClass("slider_textAct");
	$(".slider_text >li").not(".slider_text >li:eq(2)").removeClass("slider_textAct");
	$("#list_table").attr("src", "k_line.html");
	$(".iframe_footer").css("display", "none");
};
//index = 5 K线
function get_kdata() {
	//右侧样式切换
	$(".slider_text >li:eq(3)").addClass("slider_textAct");
	$(".slider_text >li").not(".slider_text >li:eq(3)").removeClass("slider_textAct");
	$("#list_table").attr("src", "k_line.html");
	$(".iframe_footer").css("display", "none");
};
//交易按钮 交易(F11)
function toggle_updown() {
	var login_type = Number(window.localStorage.getItem("login_type"));
	if(login_type == 1) {
		//行情登陆
		$(".module19_box").fadeIn(50);
	} else if(login_type == 2) {
		//交易登录
		$(".wrapper_cont").toggleClass("height32");
		$(".down_deal").toggleClass("deal_up");
		$(".foot_cont ,.account_views").toggleClass("height0");
	}
};
document.onkeydown = function() {
	var oEvent = window.event;
	if(oEvent.keyCode == 122) {
		toggle_updown();
		return false;
	}
};
//交易登录
$(".module19_box").click(function() {
	$(".condition_way7").slideUp(50);
});
$(".deal_login_module > input").click(function(event) {
	$(".condition_way7").slideToggle(50);
	prevent(event);
});
$(".condition_way7 > li").click(function() {
	$(".deal_login_module").attr("type", $(this).attr("type"));
	$(".deal_login_module > input").val($(this).html());
});
$(".deal_home_login").click(function() {
	var server_type = $(".deal_login_module > input").val();
	var account = $(".deal_account").val();
	var pass = $(".deal_password").val();
	var checked = $("#remember_account").prop("checked");
	if(account == "") {
		show_tilps("red", "请输入交易账号", 1500);
	} else if(pass == "") {
		show_tilps("red", "请输入交易密码", 1500);
	} else {
		$.post(globalUrl, {
			nozzle: "login",
			phone: account,
			password: pass,
			type: 1
		}, function(res) {
			var data = handle_data(res);
			if(data.citify == true) {
				if(checked == true) {
					window.localStorage.setItem("account_name", account);
				} else {
					localStorage.removeItem("account_name");
				}
				window.localStorage.setItem("login_type", 2);
				window.localStorage.setItem("token", data.data.token);
				setTimeout(function() {
					handel_link();
					$(".module19_box").fadeOut(50);
					show_tips();
				}, 1500);
			};
		});
	}
});
$(".deal_login_exit > span").click(function() {
	$(".module19_box").fadeOut(50);
});
//点击系统
$(".system_click").click(function(event) {
	$(".system").slideToggle(50);
	$(".set_up_cont").slideUp(50);
	prevent(event);
});
$(".system_table > div").click(function(event) {
	$(".system").slideToggle(50);
	prevent(event);
});
//系统 -> 修改子账户密码
$(".system_table div:eq(0)").click(function() {
	$(".module_box3").fadeIn(300);
});
$(".change_pass_tittle,.module3_btn button:eq(1)").click(function() {
	$(".module_box3").fadeOut(300);
});
$(".module3_btn button:eq(0)").click(function() {
	alert("修改成功");
	$(".module_box3").fadeOut(300);
});
//系统 -> 历史结算单查询
$(".system_table div:eq(1)").click(function() {
	$(".module_box4").fadeIn(300);
});
$(".module_search_title > span,.module4_exit button").click(function() {
	$(".module_box4").fadeOut(300);
});
$(".module4_search button").click(function() {
	alert("查询成功");
	$(".module_box4").fadeOut(300);
});
//点击设置
$(".set_up").click(function(event) {
	$(".set_up_cont").slideToggle(50);
	$(".system").slideUp(50);
	prevent(event);
});
//系统参数设置
$(".set_table >div:eq(0)").click(function(event) {
	$(".set_up_cont").slideToggle(50);
	$(".system_list2").slideUp(50);
	$(".module18_box").fadeIn(50);
	prevent(event);
});
$(".module18_exit > span,.module18_btm > button:eq(0)").click(function() {
	$(".module18_box").fadeOut(50);
});
$(".module18_btm > button:eq(1)").click(function() {
	change_parameter();
	$(".module18_box").fadeOut(50);
});
//修改参数的请求
function change_parameter() {
	var istrue_placeorder = $("#order_confirm").prop("checked");
	istrue_placeorder = istrue_placeorder == true ? 1 : 2; //下单前确认(1 为确认 2为不确认)
	var open_transaction_number = $("#open_transaction_number").val(); //开仓默认手数
	var customs_max_number = $("#customs_max_number").val(); //报单最大手数
	var close_number = $("input[name = 'can_p']:checked").val(); //平仓默认数量(1可平持仓量  2开仓默认量)
	var select_order_focus = $("input[name = 'choice_focus']:checked").val(); //选择合约焦点位置(1 合约 2 价格 3 手数 4不处理)
	var place_order_focus = $("input[name = 'order_focus']:checked").val(); //下单后焦点位置(1 合约 2 价格 3手数 4 不处理)
	var place_order_close = $("input[name = 'clear']:checked").val(); //下单后清空(1清空所有 2清空价格 3清空手数 4清空价格和手数 5不清空)
	var click_market_direction = $("input[name = 'reverse']:checked").val(); //单击行情买卖方向(1反向 2同向)
	var click_market_negation = $("#ctrl_reverse").prop("checked");
	click_market_negation = click_market_negation == true ? 1 : 2; //单击行情按CRTL临时取反(1是 2否)
	var order_cut_kaiping_direction = $("input[name = 'korp']:checked").val(); //合约切换开平方向(1总是开仓 2自动开平)
	var order_cut_market_type = $(".module18_newstyle").attr("type"); //合约切换下单价格类型(1限价 2最新价 3对手价 4挂单价 5快速价)
	var list_operation_is_true = $("#list_operation_confirm").prop("checked");
	list_operation_is_true = list_operation_is_true == true ? 1 : 2; //列表操作前确认(1确认 2不确认)
	var double_click_order = $("#doble_cancel").prop("checked");
	double_click_order = double_click_order == true ? 1 : 2; //双击挂单撤单(1确认 2不确认)
	var double_click_open_close = $("#doble_p").prop("checked");
	double_click_open_close = double_click_open_close == true ? 1 : 2; //双击持仓平仓(1确认 2取消确认)
	var double_open_price = $("input[name = 'order_price_type']:checked").val(); //双击持仓平仓下单价格(1对手价 2快速成交价)
	var double_open_num = $("input[name = 'order_num']:checked").val(); //双击持仓平仓下单价格(1对手价 2快速成交价)
	var speediness_backhand = $("#quick_back_hand").prop("checked");
	speediness_backhand = speediness_backhand == true ? 1 : 2; //快速反手(1确认 2不确认)
	var speediness_backhand_open_price = $("input[name = 'order_num']:checked").val(); //快速反手下单价格(1对手价 2快速成交价)
	var speediness_locked_position = $("#lock_position").prop("checked");
	speediness_locked_position = speediness_locked_position == true ? 1 : 2; //快速锁仓(1确认 2不确认)
	var speediness_locked_open_price = $("input[name = 'clock_price_type']:checked").val(); //快速锁仓下单价格(1对手价 2快速成交价)
	
	console.log(speediness_locked_open_price);
};

//系统参数设置--> 下单板设置--> 下单板 列表 消息提示 系统设置 切换
$(".module18_toptitle > li").click(function() {
	var index = $(this).index();
	$(this).addClass("module18_toptitleActive");
	$(".module18_toptitle > li").not(this).removeClass("module18_toptitleActive");
	$(".module18_top > div:eq(" + index + ")").css("display", "inline-block");
	$(".module18_top > div").not(".module18_top > div:eq(" + index + ")").css("display", "none");
});
//系统参数设置--> 下单板设置 --> 下单前确认
$(".order_confirm").click(function() {
	console.log($(this).prop("checked"));
});
//系统参数设置--> 下单板设置 --> 开仓默认手数
function open_num_addsum(type) {
	var val = Number($(".open_default_num > input").val());
	if(type == 1) {
		//加法
		val += 1;
	} else if(type == 2) {
		//减法
		val -= 1;
	}
	if(val < 1) {
		$(".open_default_num > input").val(1);
	} else {
		$(".open_default_num > input").val(val);
	}
};
//系统参数设置--> 下单板设置 --> 报单最大手数
function max_num_addsum(type) {
	var val = Number($(".max_num > input").val());
	if(type == 1) {
		//加法
		val += 1;
	} else if(type == 2) {
		//减法
		val -= 1;
	}
	if(val < 1) {
		$(".max_num > input").val(1);
	} else {
		$(".max_num > input").val(val);
	}
};
//系统参数设置--> 下单板设置 --> 开仓默认数量
$(".ratio_open_num > input").click(function() {
	var val = $('.ratio_open_num input[name="can_p"]:checked').val();
	$("#open_default_num").attr("data", val);
	console.log($("#open_default_num").attr("data"));
});
//系统参数设置--> 下单板设置 --> 选择合约焦点位置
$(".focus1_address > input").click(function() {
	var val = $('.focus1_address input[name="choice_focus"]:checked').val();
	$("#focus1_address").attr("data", val);
	console.log($("#focus1_address").attr("data"));
});
//系统参数设置--> 下单板设置 --> 下单后焦点位置
$(".focus2_address > input").click(function() {
	var val = $('.focus2_address input[name="order_focus"]:checked').val();
	$("#focus2_address").attr("data", val);
	console.log($("#focus2_address").attr("data"));
});
//系统参数设置--> 下单板设置 --> 下单后清空
$(".clear_data > input").click(function() {
	var val = $('.clear_data input[name="clear"]:checked').val();
	$("#clear").attr("data", val);
	console.log($("#clear").attr("data"));
});
//系统参数设置--> 下单板设置 --> 点击行情买卖方向
$(".buy_sell_direction > input").click(function() {
	var val = $('.buy_sell_direction input[name="reverse"]:checked').val();
	$("#buy_sell_direction").attr("data", val);
	console.log($("#buy_sell_direction").attr("data"));
});
//系统参数设置--> 下单板设置 --> 按Ctrl临时取反
$(".ctrl_reverse > input").click(function() {
	var val = $('.ctrl_reverse input').prop("checked");
	$("#buy_sell_direction").attr("ctrl", val);
	console.log($("#buy_sell_direction").attr("ctrl"));
});
//系统参数设置--> 下单板设置 --> 合约切换开平方向
$(".direction_open_p > input").click(function() {
	var val = $('.direction_open_p input[name="korp"]:checked').val();
	$(".direction_open_p").attr("direction", val);
	console.log($(".direction_open_p").attr("direction"));
});
//系统参数设置--> 下单板设置 --> 合约切换下单价格类型
$(".module18_newstyle1").click(function(event) {
	$(".module18_newstyle").slideToggle(50);
	prevent(event);
});
$(".module18_newstyle > li").click(function() {
	$(".module18_newstyle").attr("type", $(this).attr("type"));
	$(".module18_newstyle1").val($(this).html());
});
//点击module18 隐藏下拉列表
$(".module18_box").click(function() {
	$(".module18_newstyle").slideUp(50);
	$(".deal_tips > ul").slideUp(50);
	$(".order_tips > ul").slideUp(50);
	$(".cancel_tips > ul").slideUp(50);
	$(".condition_tips > ul").slideUp(50);
});
//系统参数设置--> 列表操作 --> 双击持仓平仓 --> 下单价格
$(".make_order_price input").click(function() {
	var val = $('.make_order_price input[name="order_price_type"]:checked').val();
	$(".make_order_price").attr("data", val);
	console.log($(".make_order_price").attr("data"));
});
//系统参数设置--> 列表操作 --> 双击持仓平仓 --> 下单手数
$(".make_order_num input").click(function() {
	var val = $('.make_order_num input[name="order_num"]:checked').val();
	$(".make_order_num").attr("data", val);
	console.log($(".make_order_num").attr("data"));
});
//系统参数设置--> 列表操作 --> 快速反手 --> 下单价格
$(".quick_hand > input").click(function() {
	var val = $('.quick_hand input[name="back_price_type"]:checked').val();
	$(".quick_hand").attr("data", val);
	console.log($(".quick_hand").attr("data"));
});
//系统参数设置--> 列表操作 --> 快速锁仓 --> 下单价格
$(".lock_position_type > input").click(function() {
	var val = $('.lock_position_type input[name="clock_price_type"]:checked').val();
	$(".lock_position_type").attr("data", val);
	console.log($(".lock_position_type").attr("data"));
});
//系统参数设置--> 消息提示 --> 成交提示
$(".deal_tips > input").click(function(event) {
	$(".deal_tips > ul").slideToggle(50);
	prevent(event);
});
$(".deal_tips > ul > li").click(function(event) {
	$(".deal_tips > input").val($(this).html());
	$(".deal_tips").attr("data", $(this).attr("type"));
	console.log($(".deal_tips").attr("data"));
});
//系统参数设置--> 消息提示 --> 下单提示
$(".order_tips > input").click(function(event) {
	$(".order_tips > ul").slideToggle(50);
	prevent(event);
});
$(".order_tips > ul > li").click(function(event) {
	$(".order_tips > input").val($(this).html());
	$(".order_tips").attr("data", $(this).attr("type"));
	console.log($(".order_tips").attr("data"));
});
//系统参数设置--> 消息提示 --> 撤单提示
$(".cancel_tips > input").click(function(event) {
	$(".cancel_tips > ul").slideToggle(50);
	prevent(event);
});
$(".cancel_tips > ul > li").click(function(event) {
	$(".cancel_tips > input").val($(this).html());
	$(".cancel_tips").attr("data", $(this).attr("type"));
	console.log($(".cancel_tips").attr("data"));
});
//系统参数设置--> 消息提示 --> 条件单提示
$(".condition_tips > input").click(function(event) {
	$(".condition_tips > ul").slideToggle(50);
	prevent(event);
});
$(".condition_tips > ul > li").click(function(event) {
	$(".condition_tips > input").val($(this).html());
	$(".condition_tips").attr("data", $(this).attr("type"));
	console.log($(".condition_tips").attr("data"));
});
//系统参数设置--> 系统设置 --> 下单板停靠位置
$(".order_address > input").click(function() {
	var val = $('.order_address input[name="left_right"]:checked').val();
	$(".order_address").attr("data", val);
	console.log($(".order_address").attr("data"));
});
//系统参数设置--> 系统设置 --> 是否展示持仓线
$(".hold_line > input").click(function() {
	$(".hold_line").attr("data", $("#hold_line").prop("checked"));
	console.log($(".hold_line").attr("data"));
});
//止盈止损参数设置
$(".set_table >div:eq(1)").click(function(event) {
	$(".set_up_cont").slideToggle(50);
	$(".system_list2").slideUp(50);
	$(".module20_box").fadeIn(50);
	prevent(event);
});
$(".set_close_parameter > span").click(function() {
	$(".module20_box").fadeOut(50);
});
$(".set_btn").click(function() {
	$(".module20_box").fadeOut(50);
});
$(".module20_box").click(function() {
	$(".set_close_price_list").slideUp(50);
	$(".set_profit_price_list").slideUp(50);
	$(".close_according_list").slideUp(50);
	$(".profit_according_list").slideUp(50);
	$(".close_time_list").slideUp(50);
});
//止盈止损参数设置 --> 连续 x 笔  最新价.. 触发止损
function set_close_addsum(type) {
	var val = Number($(".set_close_num").val());
	if(type == 1) {
		//加法
		val += 1;
	} else if(type == 2) {
		val -= 1;
		if(val <= 0) {
			val = 1;
		}
	}
	$(".set_close_num").val(val);
};
$(".set_close_profit_price").click(function(event) {
	$(".set_close_price_list").slideToggle(50);
	prevent(event);
});
$(".set_close_price_list > li").click(function() {
	$(".set_close_profit_price > input").val($(this).html());
});
//止盈止损参数设置 --> 连续 x 笔  最新价.. 触发止盈
function set_profit_addsum(type) {
	var val = Number($(".set_profit_addsum").val());
	if(type == 1) {
		//加法
		val += 1;
	} else if(type == 2) {
		val -= 1;
		if(val <= 0) {
			val = 1;
		}
	}
	$(".set_profit_addsum").val(val);
};
$(".set_profit_price").click(function(event) {
	$(".set_profit_price_list").slideToggle(50);
	prevent(event);
});
$(".set_profit_price_list > li").click(function() {
	$(".set_profit_price > input").val($(this).html());
});
//止盈止损参数设置 --> 止损时按照
$(".close_according").click(function(event) {
	$(".close_according_list").slideToggle(50);
	prevent(event);
});
$(".close_according_list > li").click(function() {
	$(".close_according > input").val($(this).html());
});
//止盈止损参数设置 --> 止盈时按照
$(".profit_according").click(function(event) {
	$(".profit_according_list").slideToggle(50);
	prevent(event);
});
$(".profit_according_list > li").click(function() {
	$(".profit_according > input").val($(this).html());
});
//止盈止损参数设置 --> 买入调整
$("#buy_adjustment").click(function() {
	var checked = $(this).prop("checked");
	if(checked == true) {
		$(".buy_adjustment > input,.buy_adjustment > button").attr("disabled", false);
	} else if(checked == false) {
		$(".buy_adjustment > input").val(0);
		$(".buy_adjustment > input,.buy_adjustment > button").attr("disabled", true);
	}
});

function buy_adjustment_addsum(type) {
	var val = Number($(".buy_adjustment > input").val());
	if(type == 1) {
		//加法
		val += 1;
	} else if(type == 2) {
		val -= 1;
		if(val <= -1) {
			val = 0;
		}
	}
	$(".buy_adjustment > input").val(val);
};
//止盈止损参数设置 --> 卖出调整
$("#sell_adjustment").click(function() {
	var checked = $(this).prop("checked");
	if(checked == true) {
		$(".sell_adjustment > input,.sell_adjustment > button").attr("disabled", false);
	} else if(checked == false) {
		$(".sell_adjustment > input").val(0);
		$(".sell_adjustment > input,.sell_adjustment > button").attr("disabled", true);
	}
});

function sell_adjustment_addsum(type) {
	var val = Number($(".sell_adjustment > input").val());
	if(type == 1) {
		//加法
		val += 1;
	} else if(type == 2) {
		val -= 1;
		if(val <= -1) {
			val = 0;
		}
	}
	$(".sell_adjustment > input").val(val);
};
//止盈止损参数设置 --> 如发出委托  秒后仍未成交,则撤单
$("input[name='send_entrust']").change(function() {
	var val = Number($('input[name="send_entrust"]:checked').val());
	console.log(val);
	if(val == 1) {
		$(".send_entrust_cancel >input,.send_entrust_cancel > button").attr("disabled", false);
		$(".send_entrust_remake >input,.send_entrust_remake > button").attr("disabled", true);
		$(".send_entrust_remake >input").val(1);
	} else if(val == 2) {
		$(".send_entrust_cancel >input,.send_entrust_cancel > button").attr("disabled", true);
		$(".send_entrust_remake >input,.send_entrust_remake > button").attr("disabled", false);
		$(".send_entrust_cancel >input").val(1);
	}
});

function send_entrust_cancel_addsum(type) {
	var val = Number($(".send_entrust_cancel > input").val());
	if(type == 1) {
		//加法
		val += 1;
	} else if(type == 2) {
		val -= 1;
		if(val <= 0) {
			val = 1;
		}
	}
	$(".send_entrust_cancel > input").val(val);
};

function send_entrust_remake_addsum(type) {
	var val = Number($(".send_entrust_remake > input").val());
	if(type == 1) {
		//加法
		val += 1;
	} else if(type == 2) {
		val -= 1;
		if(val <= 0) {
			val = 1;
		}
	}
	$(".send_entrust_remake > input").val(val);
};
//止盈止损参数设置 --> 止损止盈默认有效期
$(".close_time").click(function(event) {
	$(".close_time_list").slideToggle(50);
	prevent(event);
});
$(".close_time_list > li").click(function() {
	$(".set_hold_time").val($(this).html());
});
//止盈止损点数设置
$(".set_table >div:eq(2)").click(function(event) {
	$(".set_up_cont").slideToggle(50);
	$(".system_list2").slideUp(50);
	$(".module21_box").fadeIn(50);
	prevent(event);
});
$(".module21_box").click(function() {
	$(".condition_list").slideUp(50);
	$(".point_direction > ul").slideUp(50);
	$(".point_direction > ul").slideUp(50);
});
//止盈止损点数设置 --> 选择左侧合约列表
$(".module21_top1_cont >ul> li").click(function() {
	$(this).addClass("module21_top1_cont_act");
	$(".module21_top1_cont >ul> li").not(this).removeClass("module21_top1_cont_act");
});
//止盈止损点数设置 --> 合约代码
$(".set_contact_point").click(function(event) {
	$(".condition_list").slideToggle(50);
	prevent(event);
});
$(".none_li_special > li").click(function() {
	$(".set_contact_val").val($(this).html());
});
//止盈止损点数设置 --> 方向
$(".point_direction").click(function(event) {
	$(".point_direction > ul").slideToggle(50);
	prevent(event);
});
$(".point_direction > ul > li").click(function() {
	$(".point_direction > input").val($(this).html());
});
//止盈止损点数设置 --> 止损点数
function point_close_addsum(type) {
	var val = Number($(".point_close_num").val());
	if(type == 1) {
		//加法
		val += 1;
	} else if(type == 2) {
		val -= 1;
		if(val <= -1) {
			val = 0;
		}
	}
	$(".point_close_num").val(val);
};
//止盈止损点数设置 --> 止盈点数
function point_profit_addsum(type) {
	var val = Number($(".point_profit_num").val());
	if(type == 1) {
		//加法
		val += 1;
	} else if(type == 2) {
		val -= 1;
		if(val <= -1) {
			val = 0;
		}
	}
	$(".point_profit_num").val(val);
};
//止盈止损点数设置 -->状态
$(".point_status").click(function(event) {
	$(".point_status > ul").slideToggle(50);
	prevent(event);
});
$(".point_status > ul > li").click(function() {
	$(".point_status > input").val($(this).html());
});
//止盈止损点数设置 --> 取消
$(".set_close_point > span,.module21_bottom > button:eq(0)").click(function() {
	$(".module21_box").fadeOut(50);
});
//止盈止损点数设置 --> 确定
$(".module21_bottom > button:eq(1)").click(function() {
	$(".module21_box").fadeOut(50);
});

//风格 样式切换
$(".set_style").click(function(event) {
	prevent(event);
});
$(".set_style").hover(function() {
	$(".system_list2").slideDown(50);
});
$(".set_table >div:eq(0),.set_table >div:eq(1),.set_table >div:eq(2)").hover(function() {
	$(".system_list2").slideUp(50);
});
$(".style_list").hover(function() {}, function() {
	$(".system_list2").slideUp(50);
});
$(".style_list > div").click(function() {
	$(".system_list2").slideUp(50);
	$(".set_up_cont").slideUp(50);
});
/*页面顶部功能按钮 end*/
/*选择交易所 功能按钮*/
//交易所切换样式
$(".exchange_list").on("mousedown", "li", function(event) {
	$(this).addClass("exchange_listAct");
	$(".exchange_list li").not(this).removeClass("exchange_listAct");
	$(".exchange_list").attr("type", $(this).attr("type"));
	var exchange_id = $(".exchange_list").attr("type");
	var exchange_name = $(this).html();
	variety(exchange_id, exchange_name);
});
//点击交易所获取交易所下所有合约 和 分级列表
function variety(id, exchange_name) {
	var contact_arr = [];
	$.post(globalUrl, {
		nozzle: "variety",
		id: id,
		token: localStorage.getItem("token")
	}, function(res) {
		//分级数组
		var name_level = res.data.variety;
		$(".contract_list > li").remove();
		for(var i = 0; i < name_level.length; i++) {
			var html = '<li>' + name_level[i] + '</li>'
			$(".contract_list").append(html);
		};
		//合约数组
		var contract_list = res.data.contract;
		//渲染合约列表
		if(exchange_name == "能源中心") {
			for(var i = 0; i < contract_list.length; i++) {
				var arr = [contract_list[i].name, contract_list[i].code, contract_list[i].code, contract_list[i].bourse_code, contract_list[i].bourse_name, i];
				contact_arr.push(arr);
			};
		} else {
			for(var i = 0; i < contract_list.length; i++) {
				var arr = [contract_list[i].name, contract_list[i].code, contract_list[i].short, contract_list[i].bourse_code, contract_list[i].bourse_name];
				contact_arr.push(arr);
			}
		}
		//		else if(exchange_name == "芝期所" || exchange_name == "纽金所" || exchange_name == "欧交所" || exchange_name == "港交所" || exchange_name == "纽商所" || exchange_name == "新加坡") {
		//			for(var i = 0; i < contract_list.length; i++) {
		//				var arr = [contract_list[i].name, contract_list[i].code, contract_list[i].short, contract_list[i].bourse_code, contract_list[i].bourse_name];
		//				contact_arr.push(arr);
		//			}
		//		}
		window.frames["list_table"].price_data(contact_arr, exchange_name);
		contact_length();
	});
};
//合约 切换样式
$(".contract_list").on("mousedown", "li", function(event) {
	$(this).addClass("contract_listAct");
	$(".contract_list li").not(this).removeClass("contract_listAct");
	var name = $(this).html();
	Contract(name);
});
//点击合约分级获取合约列表
function Contract(name) {
	var exchange_id = $(".exchange_list").attr("type");
	var exchange_name = $(".exchange_listAct").html();
	var contact_arr = [];
	$.post(globalUrl, {
		nozzle: "contract",
		id: exchange_id,
		name: name,
		token: localStorage.getItem("token")
	}, function(res) {
		var contract_list = res.data;
		for(var i = 0; i < contract_list.length; i++) {
			var arr = [contract_list[i].name, contract_list[i].code, contract_list[i].short, contract_list[i].bourse_code, contract_list[i].bourse_name];
			contact_arr.push(arr);
		};
		window.frames["list_table"].price_data(contact_arr, exchange_name);
	});
};
//判断交易所按钮是否超出页面
function exchange_length() {
	//width: calc(100% - 60px);
	var li_width = $(".exchange_list > li").width() + 2;
	li_width = li_width * ($(".exchange_list > li").length); //li 的总宽度
	var mar_width = 5 * $(".exchange_list > li").length; //margin 的 总宽度
	var max_length = mar_width + li_width;
	var box_width = $(".list_box").width();
	if(max_length > box_width) {
		$(".exchange_list").css("width", max_length);
		$(".exchange_listbox span").css("display", "inline-block");
		$(".list_box").css({
			"width": "calc(100% - 60px)",
			"left": "30px"
		});
	} else {
		$(".exchange_list").css("width", "100%");
		$(".exchange_listbox span").css("display", "none");
		$(".list_box").css({
			"width": "100%",
			"left": "0"
		});
	}
};
//交易所左滑又滑按钮
var start_left = $(".exchange_list").offset().left;
$(".exchange_listbox > span").click(function() {
	var max_width = $(".exchange_list").width();
	var end_left = start_left - max_width + $(".list_box").width();
	var type = $(this).attr("type");
	var li_width = $(".exchange_list > li").width() + 2 + 5;
	var now_offset_left = $(".exchange_list").offset().left;
	var offset_top = $(".exchange_list").offset().top;
	if(type == "left") {
		var offset_left = $(".exchange_list").offset().left - li_width;
		if(now_offset_left >= end_left) {
			$(".exchange_list").offset({
				top: offset_top,
				left: offset_left
			});
		}
	} else if(type == "right") {
		var offset_left = $(".exchange_list").offset().left + li_width;
		if(now_offset_left < start_left) {
			$(".exchange_list").offset({
				top: offset_top,
				left: offset_left
			});
		}
	}
});
//判断合约分类是否超出页面
function contact_length() {
	//width: calc(100% - 60px);
	var li_width = 0;
	for(var i = 0; i < $(".contract_list > li").length; i++) {
		li_width += $(".contract_list > li:eq(" + i + ")").width();
	}
	var padding_width = 30 * $(".contract_list > li").length;
	var max_length = padding_width + li_width;
	var box_width = $(".contract_box").width();
	if(max_length > box_width) {
		$(".contract_list").css("width", max_length);
		$(".contract_listbox span").css("display", "inline-block");
		$(".contract_box").css({
			"width": "calc(100% - 60px)",
			"left": "30px"
		});
	} else {
		$(".contract_list").css("width", "100%");
		$(".contract_listbox span").css("display", "none");
		$(".contract_box").css({
			"width": "100%",
			"left": "0"
		});
	};
	$(".contract_list").css("left", "0");
};
//分类左滑又滑按钮
var begin_left = $(".contract_list").offset().left;
$(".contract_listbox > span").click(function() {
	var type = $(this).attr("type");
	var max_width = $(".contract_list").width();
	var end_left = start_left - max_width + $(".contract_box").width();
	var li_width = 45;
	var now_offset_left = $(".contract_list").offset().left;
	var offset_top = $(".contract_list").offset().top;
	if(type == "left") {
		var offset_left = $(".contract_list").offset().left - li_width;
		if(now_offset_left >= end_left) {
			$(".contract_list").offset({
				top: offset_top,
				left: offset_left
			});
		}
	} else if(type == "right") {
		var offset_left = $(".contract_list").offset().left + li_width;
		if(now_offset_left < start_left) {
			$(".contract_list").offset({
				top: offset_top,
				left: offset_left
			});
		}
	}
});
//监控 屏幕大小发生变化
$(window).resize(function () {
	exchange_length();
	contact_length();
	rsize_none();
});  
function rsize_none() {
	var width = $("body").width();
	if(width <= 1400) {
		$(".min_none").css("display", "none");
	} else {
		$(".min_none").css("display", "inline-block");
	}
};
rsize_none();
/*选择交易所 功能按钮 end*/
/*底部交易模块按钮*/
//选择投机投保
$(".speculating > button").click(function(event) {
	$(".sleeve_box").slideToggle(50);
	prevent(event);
});
$(".sleeve > li").click(function() {
	$(".speculating > button").html($(this).html());
	$(".speculating").attr("type", $(this).attr("type"));
});
//选择合约输入框
$(".contact_select > input").click(function() {
	$(".contact_type").slideToggle(50);
	$(".contact_val_box").slideUp(1);
	prevent(event);
});
$(".contact_type_list > li").click(function(event) {
	$(".contact_type").slideUp(1);
	$(".contact_val_box").slideDown(1);
	prevent(event);
});
$(".contact_val li").click(function() {
	$(".contact_val_box").slideUp(1);
	$(".contact_select > input").val($(this).html());
});
//投机投保 锁定
$(".clock_click").click(function() {
	$(".clock_click").toggleClass("clock_off");
	$(".clock_click").toggleClass("clock_on");
});
//手数加减
function num_add_sum(type) {
	var num = Number($(".buy_num").val());
	if(type == 0) {
		//手数加
		num += 1;
	} else if(type == 1) {
		//手数减
		num -= 1;
	}
	if(num >= 10000) {
		$(".buy_num").val(10000);
	} else if(num <= 1) {
		$(".buy_num").val(1);
	} else {
		$(".buy_num").val(num);
	}
};
//价格加减
function price_add_sum(type) {
	var price = Number($(".buy_price").val());
	if(isNaN(price)) {
		price = 0
	}
	if(type == 0) {
		//价格++
		price += 0.2;
	} else if(type == 1) {
		//价格--
		price -= 0.2;
	}
	price = parseInt(price * 10) / 10;
	if(price <= 0) {
		$(".buy_price").val(0.2);
	} else {
		$(".buy_price").val(price)
	}
};
//感叹号 hover
$(".notes_box").hover(function() {
	$(".notes").fadeIn(100);
}, function() {
	$(".notes").fadeOut(100);
});
//选择价格的方式 最新价 
$(".buy_price").click(function(event) {
	$(".price_type").slideToggle(50);
	prevent(event);
});
$(".price_type > li").click(function() {
	var type = $(this).attr("type");
	$(".price_type").attr("type", type);
	$(".buy_price").val($(this).html());
	//	if(type == 0) {
	//		$(".buy_price").val($(this).html());
	//	} else {
	//		$(".buy_price").val($(this).html());
	//	}

});
//点击买入 卖出 弹出确认框
var buy_sell_data = {
	code: "", //代码
	pattern: "", //投机
	number: "", //数量
	classify: "", //价格方式
	price: "", //输入价格
	direction: "", //买卖方向
	mode: "" //开平方向
}
$(".module5_cont2 > button:eq(0)").click(function() {
	order_sure(buy_sell_data.code, buy_sell_data.pattern, buy_sell_data.number, buy_sell_data.classify, buy_sell_data.price, buy_sell_data.direction, buy_sell_data.mode);
	$(".module5_box").fadeOut(100);
});
//$(".sell_buy > button:eq(1)").click(function() {
//	//卖出
//	$(".module5_box").fadeIn(100);
//});
$(".confirm_order > span").click(function() {
	$(".module5_box").fadeOut(100);
});
$(".module5_cont2 > button:eq(1)").click(function() {
	$(".module5_box").fadeOut(100);
});
//交易请求  获取参数信息
function trade_data(buy_type) {
	var type = Number($(".make_order_way").attr("type"));
	//type => 1 两键下单 type => 2 三键下单  type => 3 普通下单
	//buy_type => 0买  buy_type => 1卖(仅对 两键 三键下单 有效)
	var code = $(".contact_select > input").val(); //代码短语 code
	var pattern = $(".speculating").attr("type"); //pattern 0 投机 1 套利 2套保
	var number = $(".buy_num").val(); //number 手数
	var classify = $(".price_type").attr("type"); //0 限价 1：最新价 2：对手价 3：挂单价 4:快速价
	if(classify == "0") {
		//限价
		var price = $(".buy_price").val(); //price 价格
	} else {
		//1：最新价 2：对手价 3：挂单价 4:快速价
		var price = 0; //price 价格
	}
	if(type == 1) {
		//两键下单
		var direction = buy_type; //0 买 1卖
		var mode = $("input[name='way']:checked").val(); //0 开仓  1 平仓 2平今

	} else if(type == 2) {
		//三键下单
		var direction = buy_type; //0 买 1卖
		var mode = 0; //0 开仓  1 平仓 2平今
	} else if(type == 3) {
		//普通下单
		var direction = $("input[name='buy_way']:checked").val();; //0 买 1卖
		var mode = $("input[name='way']:checked").val(); //0 开仓  1 平仓 2平今
	}
	buy_sell_data = {
		code: code, //代码
		pattern: pattern, //投机
		number: number, //数量
		classify: classify, //价格方式
		price: price, //输入价格
		direction: direction, //买卖方向
		mode: mode //开平方向
	}
	//操作dom
	if(direction == 0) {
		var direction_text = "买";
	} else {
		var direction_text = "卖";
	}
	if(mode == 0) {
		var mode_text = "开仓";
	} else if(mode == 1) {
		var mode_text = "平仓";
	} else if(mode == 2) {
		var mode_text = "平今";
	}

	if(pattern == 0) {
		var pattern_text = "投机";
	} else if(mode == 1) {
		var pattern_text = "套利";
	} else if(mode == 2) {
		var pattern_text = "套保";
	}
	if(classify == 0) {
		var classify_text = "限价";
	} else if(classify == 1) {
		var classify_text = "最新价";
	} else if(classify == 2) {
		var classify_text = "对手价";
	} else if(classify == 3) {
		var classify_text = "挂单价";
	} else if(classify == 4) {
		var classify_text = "快速价";
	}
	var str = "合约：" + code + "，方向：" + direction_text + "，开平：" + mode_text + "，投保：" + pattern_text + "，手数：" + number + "，价格：" + classify_text;
	if(code != "") {
		$(".module5_cont1").html(str);
		$(".module5_box").fadeIn(100);
	}
};

function order_sure(code, pattern, number, classify, price, direction, mode) {
	console.log(1);
	$.post(globalUrl, {
		nozzle: "trade",
		token: localStorage.getItem("token"),
		code: code,
		pattern: pattern,
		number: number,
		classify: classify,
		price: price,
		direction: direction,
		mode: mode,
	}, function(res) {
		var data = handle_data(res);
		if(data.citify == true) {
			var src = $("#my_views").attr("src");
			console.log(src);
			//			window.frames["my_views"].
		}
	});
};

//点击条件单
$(".reset_condition > button:eq(1)").click(function() {
	$(".module6_box").fadeIn(100);
	var date = new Date();
	var hours = date.getHours();
	if(hours < 10) {
		hours = "0" + hours;
	}
	var minutes = date.getMinutes();
	if(minutes < 10) {
		minutes = "0" + minutes;
	}
	var seconds = date.getSeconds();
	if(seconds < 10) {
		seconds = "0" + seconds;
	}
	$(".time_arrival > button:eq(0)").html(hours);
	$(".time_arrival > button:eq(1)").html(minutes);
	$(".time_arrival > button:eq(2)").html(seconds);
});
$(".condition_set").click(function() {
	$(".module6_box").fadeOut(100);
});
$(".module6_cont3_button > button:eq(0)").click(function() {
	//取消
	$(".module6_box").fadeOut(100);
});
$(".module6_cont3_button > button:eq(1)").click(function() {
	//确认
	$(".module6_box").fadeOut(100);
});
//条件单 -->买入卖出选项
$(".condition_buy_val").click(function(event) {
	$(".condition_buy_type").slideToggle(50);
	prevent(event);
});
$(".condition_buy_type > li").click(function() {
	$(".condition_buy_val").val($(this).html());
});
//条件单 -->点击屏幕收起下拉框
$(".module6_box").click(function() {
	$(".condition_buy_type").slideUp(50);
	$(".condition_sell").slideUp(50);
	$(".price_way").slideUp(50);
	$(".condition_way1").slideUp(50);
	$(".condition_way2").slideUp(50);
});
//条件单 -->开仓平仓选项
$(".condition_sell_val").click(function(event) {
	$(".condition_sell").slideToggle(50);
	prevent(event);
});
$(".condition_sell > li").click(function() {
	$(".condition_sell_val").val($(this).html());
});
//条件单 -->价格选项
$(".price_way_val").click(function(event) {
	$(".price_way").slideToggle(50);
	prevent(event);
});
$(".price_way > li").click(function() {
	$(".price_way_val").val($(this).html());
	var type = $(this).attr("type");
	if(type == 1 || type == 4) {
		$(".up_dow_position > button").attr("disabled", true);
		$(".second_checked").attr("disabled", true);
		$(".order_again").attr("disabled", true);
		$(".again_price").attr("disabled", true);
		$(".second_checked").prop("checked", false);
		$(".order_again").prop("checked", false);
		$(".again_price").prop("checked", false);
		$(".up_dow_style3 > button").attr("disabled", true);
		$(".up_dow_style4 > button").attr("disabled", true);
	} else {
		$(".up_dow_position > button").attr("disabled", false);
		$(".second_checked").attr("disabled", false);
	}
});
//条件单 -->点位加减
function up_dow_position(type) {
	var position = Number($(".beyond_position").val());
	if(type == 1) {
		position += 1;
	} else if(type == 2) {
		position -= 1;
	}
	if(position <= 0) {
		$(".beyond_position").val(1);
	} else {
		$(".beyond_position").val(position);
	}
};
//条件单 -->数量加减
function up_dow_num(type) {
	var num = Number($(".up_dow_num_val").val());
	if(type == 1) {
		num += 1;
	} else if(type == 2) {
		num -= 1;
	}
	if(num <= 0) {
		$(".up_dow_num_val").val(1);
	} else {
		$(".up_dow_num_val").val(num);
	}
};
//条件单 -->系统 -->最新价
$(".condition_input_val").click(function(event) {
	$(".condition_way1").slideToggle(50);
	prevent(event);
});
$(".condition_way1 > li").click(function() {
	$(".condition_input_val").val($(this).html());
});
//条件单 -->系统 -->最新价-->连续几笔
function pens_add_sum(type) {
	var val = Number($(".up_dow_box1_val").val());
	if(type == 0) {
		//加法
		val += 1;
	} else if(type == 1) {
		//减法
		val -= 1;
	}
	if(val <= 1) {
		$(".up_dow_box1_val").val(1);
	} else {
		$(".up_dow_box1_val").val(val);
	}
};
//条件单 -->系统 -->最新价-->连续几笔 --> 关系(≥,=,≤)
$(".condition_val2").click(function(event) {
	$(".condition_way2").slideToggle(50);
	prevent(event);
});
$(".condition_way2 > li").click(function() {
	$(".condition_val2").val($(this).html());
});
//条件单 -->系统 -->最新价-->连续几笔 --> 价格加减
function price_sum_add(type) {
	var val = Number($(".up_dow_val2").val());
	if(type == 0) {
		//加法
		val += 0.2;
	} else if(type == 1) {
		//减法
		val -= 0.2;
	}
	val = parseInt(val * 100) / 100;
	if(val <= 0) {
		$(".up_dow_val2").val(2);
	} else {
		$(".up_dow_val2").val(val);
	}
};
//条件单 -->系统 -->时间到达
$(".time_arrival > button").click(function() {
	$(this).addClass("condition_timeAct");
	$(".time_arrival > button").not(this).removeClass("condition_timeAct");
});
//条件单 -->系统 -->时间到达 -->时间加减
function time_add_sum(type) {
	var time = Number($(".condition_timeAct").html());
	if(type == 0) {
		//加法
		time += 1;
	} else if(type == 1) {
		//减法
		time -= 1;
	}
	if(time < 0) {
		time = 59;
	} else if(time > 59) {
		time = "00";
	} else {
		if(time >= 0 && time < 10) {
			time = "0" + time;
		}
	}
	$(".condition_timeAct").html(time);
};
//条件单 -->系统 -->如果几秒内未提交订单 则撤单 并选择 是否重新下单
function seconds_sum_add(type) {
	var seconds = Number($(".seconds_val").val());
	if(type == 0) {
		//加法
		seconds += 1;
	} else if(type == 1) {
		//减法
		seconds -= 1;
	}
	if(seconds <= 1) {
		seconds = 1;
	}
	$(".seconds_val").val(seconds);
};
$(".second_checked").click(function() {
	var checked = $(".second_checked").prop("checked");
	if(checked == true) {
		$(".order_again").attr("disabled", false);
		$(".is_cancel1").css("color", "#c8c8c8");
		$(".up_dow_style3 > button").attr("disabled", false);
	} else {
		$(".order_again").attr("disabled", true);
		$(".is_cancel1,.is_cancel2").css("color", "#111111");
		$(".up_dow_style3 > button").attr("disabled", true);
		$(".order_again,.again_price").prop("checked", false);
	}
});
//条件单 -->系统 -->如果几秒内未提交订单 则撤单 并选择 -->重新下单
$(".order_again").click(function() {
	var checked = $(".order_again").prop("checked");
	if(checked == true) {
		$(".again_price").attr("disabled", false);
	} else {
		$(".again_price").attr("disabled", true);
	};
});
//条件单 -->系统 -->如果几秒内未提交订单 则撤单 并选择 -->重新下单价格不超过....
function beyond_price_sumadd(type) {
	var seconds = Number($(".up_dow_style4_val").val());
	if(type == 0) {
		//加法
		seconds += 1;
	} else if(type == 1) {
		//减法
		seconds -= 1;
	}
	if(seconds <= 1) {
		seconds = 1;
	}
	$(".up_dow_style4_val").val(seconds);
};
$(".again_price").click(function() {
	var checked = $(".again_price").prop("checked");
	if(checked == true) {
		$(".up_dow_style4 > button").attr("disabled", false);
		$(".is_cancel2").css("color", "#c8c8c8")
	} else {
		$(".up_dow_style4 > button").attr("disabled", true);
		$(".is_cancel2").css("color", "#111111");
	};
});
//复位
$(".reset").click(function() {
	$(".ping_way > input:eq(0)").prop("checked", true);
	$(".ping_way > input").not(".ping_way > input:eq(0)").prop("checked", false);
	$(".buy_num").val(1);
	$(".buy_price").val("限价");
});
//快速按钮
$(".quick_make_order > input").click(function() {
	var checked = $(".quick_make_order > input").prop("checked");
	if(checked == true) {
		$(".no_click").attr("disabled", true);
	} else {
		$(".no_click").attr("disabled", false);
	}
});
//普通下单 -- >买入卖出切换
$(".radio_buy_sell > input").click(function() {
	var val = $('.radio_buy_sell input[name="buy_way"]:checked').val();
	if(val == "buy") {
		$(".ordinary_buy").css("background", "#e15544").html("买&nbsp;&nbsp;入");

	} else if(val == "sell") {
		$(".ordinary_buy").css("background", "#40A310").html("卖&nbsp;&nbsp;出");
	}
});
//条件单 --> 止损止盈风险揭示书
$(".risk_hints").click(function() {
	$(".module_lose").fadeIn(50);
});
$(".lose_title > span").click(function() {
	$(".module_lose").fadeOut(50);
});
//我的持仓 --> 全部清仓
$(".my_btn > button:eq(0)").click(function() {
	$(".module9_box").fadeIn(50);
});
$(".clear_all_hold > span,.module9_cont > button:eq(1)").click(function() {
	$(".module9_box").fadeOut(50);
});
$(".module9_cont > button:eq(0)").click(function() {
	alert("清除全部仓位");
	$(".module9_box").fadeOut(50);
});
//我的持仓 --> 快速平仓
$(".my_btn > button:eq(1)").click(function() {
	var code = $("#my_views").contents().find("#hold_list").attr("code");
	if(code == "") {
		$(".module10_box").fadeIn(50);
	} else {
		alert("平仓成功");
	}
});
$(".quick_ping > span,.module10_btn").click(function() {
	$(".module10_box").fadeOut(50);
});
//我的持仓 --> 快速反手
$(".my_btn > button:eq(2)").click(function() {
	var code = $("#my_views").contents().find("#hold_list").attr("code");
	if(code == "") {
		$(".module11_box").fadeIn(50);
	} else {
		alert("反手成功");
	}
});
$(".quick_back > span,.module11_btn").click(function() {
	$(".module11_box").fadeOut(50);
});
//我的持仓 --> 快速锁仓
$(".my_btn > button:eq(3)").click(function() {
	var code = $("#my_views").contents().find("#hold_list").attr("code");
	if(code == "") {
		$(".module12_box").fadeIn(50);
	} else {
		alert("锁仓成功");
	}
});
$(".quick_clock > span,.module12_btn").click(function() {
	$(".module12_box").fadeOut(50);
});
//交易委托 --> 撤单
$(".entrust_btn > button:eq(0)").click(function() {
	var order = $("#my_views").contents().find("#entrust").attr("order");
	if(order == "") {
		$(".module13_box").fadeIn(50);
	} else {
		$.post(globalUrl, {
			nozzle: "cancel_order",
			order_id: order,
			token: localStorage.getItem("token")
		}, function(res) {
			var data = handle_data(res);
			if(data.citify == true) {
				//撤单成功
				window.frames["my_views"].entrust_info();
			}
		});
	}
});
$(".cancel_entrust > span,.module13_btn").click(function() {
	$(".module13_box").fadeOut(50);
});
//交易委托 --> 全撤
$(".entrust_btn > button:eq(1)").click(function() {
	$(".module88_box").fadeIn(50);

});
$(".cancel_entrust_all > span,.module88_cont2 > button:eq(1)").click(function() {
	$(".module88_box").fadeOut(50);
});
$(".module88_cont2 > button:eq(0)").click(function() {
	$(".module88_box").fadeOut(50);
	$.post(globalUrl, {
		nozzle: "all_entrust",
		token: localStorage.getItem("token")
	}, function(res) {
		console.log(res);
		var data = handle_data(res);
		if(data.citify == true) {
			//撤单成功
			window.frames["my_views"].entrust_info();
		}
	});
});
//我的条件单 --> 发送
$(".condition_btn > button:eq(0)").click(function() {
	alert("条件单触发成功");
});
//我的条件单 --> 删除
$(".condition_btn > button:eq(1)").click(function() {
	var code = $("#my_views").contents().find("#condition").attr("code");
	if(code == "") {
		$(".module14_box").fadeIn(50);
	} else {
		alert("删除成功");
	}
});
$(".dele_condition > span,.module14_btn").click(function() {
	$(".module14_box").fadeOut(50);
});
//我的条件单 --> 全删
$(".condition_btn > button:eq(2)").click(function() {
	$(".module15_box").fadeIn(50);
});
$(".dele_condition_all > span,.module15_cont2 > button:eq(1)").click(function() {
	$(".module15_box").fadeOut(50);
});
$(".module15_cont2 > button:eq(0)").click(function() {
	alert("全部删除成功");
	$(".module15_box").fadeOut(50);
});
//账户资金信息
$(".right_pic > small:eq(0)").click(function() {
	$(".module16_box").fadeIn(50);
});
$(".account_capital > span,.module16_cont2 > button").click(function() {
	$(".module16_box").fadeOut(50);
});
//汇率
$(".right_pic > small:eq(1)").click(function() {
	exchange_rate();
});

function exchange_rate() {
	$.post(globalUrl, {
		nozzle: "exchange_rate",
		token: localStorage.getItem("token")
	}, function(res) {
		var data = handle_data(res);
		if(data.citify == true) {
			console.log(data.data);
			$(".module17_box").fadeIn(50);
		}
	});
};
$(".exchange_rate > span,.module17_cont2 > button").click(function() {
	$(".module17_box").fadeOut(50);
});
/*底部交易模块按钮 end*/
/*
 * 加入自选 删除自选
 */
$(".right_module_1 > div:eq(0)").click(function() {
	var code = $("#list_table").contents().find(".market_border_special").attr("short");
	$.post(globalUrl, {
		nozzle: "join_optional",
		code: code,
		token: localStorage.getItem("token")
	}, function(res) {
		if(res.code == 0) {
			show_tilps("red", res.msg, 1500);
		} else if(res.code == 1) {
			show_tilps("green", res.msg, 1500);
		}
	});
});
/*
 * 加入自选 删除自选 end
 */
//三分钟分时
function make_tgree(type) {
	var symbol = window.frames["list_table"].KlineIns.symbol;
	window.frames["list_table"].change_url(symbol, "getFewMinLine", "type=", "5");
	window.frames["list_table"].switch_period(type);
};