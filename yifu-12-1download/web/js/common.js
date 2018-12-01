/*通用方法*/
//请求地址
var globalUrl ='http://yfserve.bjfable.com/';
//var globalUrl = 'http://yf.net/';
//var globalUrl = 'http://inter.yifu.com/';
//阻止事件冒泡  公用函数
function prevent(event) {
	var e = arguments.callee.caller.arguments[0] || event; //若省略此句，下面的e改为event，IE运行可以，但是其他浏览器就不兼容
	if(e && e.stopPropagation) {
		// this code is for Mozilla and Opera
		e.stopPropagation();
	} else if(window.event) {
		// this code is for IE
		window.event.cancelBubble = true;
	}
};
//提示弹出层  参数1:字体颜色  参数2:信息内容 参数3:显示时间
function show_tilps(color, msg, time) {
	var html = '<div class="tilps position0">' +
		'<p class="position0 ' + color + '">' + msg + '</p>' + '</div>';
	$("body").append(html);
	$(".tilps").fadeIn(100);
	setTimeout(function() {
		$(".tilps").fadeOut(100);
		setTimeout(function() {
			$(".tilps").remove();
		}, 100);
	}, time);
};
function hide(name) {
	$(name).fadeOut(100);
	setTimeout(function() {
		$(name).remove();
	}, 100);
};
//验证信息   (手机号,账号,密码,确认密码,验证码)
function verification(phone, name, pass, confirm, code) {
	if(phone != "No") {
		if(!(/^1(3|4|5|6|7|8|9)\d{9}$/.test(phone))) {
			show_tilps("red", "手机号码格式错误", 1500);
			return false;
		}
	}
	if(name != "No") {
		if(name == "") {
			show_tilps("red", "请输入帐号", 1500);
			return false;
		}
	}
	if(pass != "No") {
		if(pass == "") {
			show_tilps("red", "请输入密码", 1500);
			return false;
		}
	}
	if(confirm != "No") {
		if(confirm == "") {
			show_tilps("red", "请输入确认密码", 1500);
			return false;
		}
	}
	if(confirm != "No" && pass != "No") {
		if(pass != confirm) {
			show_tilps("red", "两次密码输入不一致", 1500);
			return false;
		}
	}
	if(code != "No") {
		if(code == "") {
			show_tilps("red", "请输入验证码", 1500);
			return false;
		}
	}
	return true;
};
/*通用方法  end*/
function handle_data(res) {
	if(res.code == 0) {
		show_tilps("red", res.msg, 1500);
		return {
			citify: false
		};
	} else if(res.code == 1) {
		if(res.msg != "") {
			show_tilps("green", res.msg, 1500);
		}
		return {
			citify: true,
			data: res.data
		};
	} else if(res.code == -1) {
		show_tilps("red", res.msg, 1500);
		setTimeout(function() {
			//			window.location.href = "http://192.168.199.188/index.html";
			//			window.parent.location.href = "http://192.168.199.188/index.html"
		}, 1500);
		return {
			citify: false
		};
	}
};
/*
 *显示隐藏
 * 
 * */
$(document).click(function() {
	//父页面显示隐藏
	$(".system").slideUp(50);
	$(".set_up_cont").slideUp(50);
	$(".system_list2").slideUp(50);
	$(".sleeve_box").slideUp(50);
	$(".contact_type").slideUp(50);
	$(".contact_val_box").slideUp(1);
	$(".price_type").slideUp(50);
	$(".right_module1").removeClass("right_module_act1");
	$(".right_module2").removeClass("right_module_act2");
	//子页面点击控制显示隐藏
	$(".system", window.parent.document).slideUp(50);
	$(".set_up_cont", window.parent.document).slideUp(50);
	$(".system_list2", window.parent.document).slideUp(50);
	$(".sleeve_box", window.parent.document).slideUp(50);
	$(".contact_type", window.parent.document).slideUp(50);
	$(".contact_val_box", window.parent.document).slideUp(1);
	$(".price_type", window.parent.document).slideUp(50);
	$(".right_module1", window.parent.document).removeClass("right_module_act1");
	$(".right_module2", window.parent.document).removeClass("right_module_act2");

});
//F11禁止全屏
function updown_toggle() {
	var login_type = Number(window.localStorage.getItem("login_type"));
	if(login_type == 1) {
		//行情登陆
		$(".module19_box", window.parent.document).fadeIn(50);
	} else if(login_type == 2) {
		//交易登录
		$(".wrapper_cont", window.parent.document).toggleClass("height32");
		$(".down_deal", window.parent.document).toggleClass("deal_up");
		$(".foot_cont ,.account_views", window.parent.document).toggleClass("height0");
	}
};
document.onkeydown = function() {
	var oEvent = window.event;
	if(oEvent.keyCode == 122) {
		updown_toggle();
		return false;
	}
};
//右键禁止显示菜单栏
document.oncontextmenu = function() {
	return false
};
/*
 *显示隐藏
 * 
 * */
/*
 *更新每个交易所的数据
 * */
// =>中金所数据模式
function add_zjs(data_arr, name_arr) {
	var color_class = "white";
	$(".updata_element").remove();
	for(var i = 0; i < data_arr.length; i++) {
		var name = i + 1;
		var src = "";
		if(data_arr[i][51] < 0) {
			src = "img/bottom.png";
			color_class = "green";
		} else if(data_arr[i][51] > 0) {
			src = "img/top.png";
			color_class = "red";
		} else if(data_arr[i][51] == 0) {
			src = "";
			color_class = "white";
		};
		if(data_arr[i].length < 5) {
			var html = '<tr class="updata_element" short="' + name_arr[i][2] + '">' +
				'<td class="yellow"><span><img src="' + src + '" alt="" /></span>' + name_arr[i][0] + '</td>' +
				'<td class="' + color_class + '">--</td>' +
				'<td class="green">--</td>' +
				'<td class="green">--</td>' +
				'<td class="yellow">--</td>' +
				'<td class="yellow">--</td>' +
				'<td class="' + color_class + '">--</td>' +
				'<td class="' + color_class + '">--</td>' +
				'<td class="white">--</td>' +
				'<td class="white">--</td>' +
				'<td class="yellow">--</td>' +
				'<td class="yellow">--</td>' +
				'<td class="red">--</td>' +
				'<td class="green">--</td>' +
				'<td class="green">--</td>' +
				'<td class="green">--</td>' +
				'<td class="green">--</td>' +
				'<td class="white">--</td>' +
				'<td class="yellow">--</td>' +
				'<td class="yellow">' + name_arr[i][3] + '</td>' +
				'<td class="yellow">--</td>' +
				'</tr>';
		} else {
			var html = '<tr class="updata_element" short="' + name_arr[i][2] + '">' +
				'<td class="yellow"><span><img src="' + src + '" alt="" /></span>' + name_arr[i][0] + '</td>' +
				'<td class="' + color_class + '">' + data_arr[i][3] + '</td>' +
				'<td class="green">--</td>' +
				'<td class="green">--</td>' +
				'<td class="yellow">--</td>' +
				'<td class="yellow">--</td>' +
				'<td class="' + color_class + '">' + data_arr[i][50] + '</td>' +
				'<td class="' + color_class + '">' + data_arr[i][51] + '</td>' +
				'<td class="white">' + data_arr[i][13] + '</td>' +
				'<td class="white">' + data_arr[i][14] + '</td>' +
				'<td class="yellow">' + data_arr[i][4] + '</td>' +
				'<td class="yellow">' + data_arr[i][6] + '</td>' +
				'<td class="red">' + data_arr[i][9] + '</td>' +
				'<td class="green">' + data_arr[i][10] + '</td>' +
				'<td class="green">' + data_arr[i][1] + '</td>' +
				'<td class="green">' + data_arr[i][0] + '</td>' +
				'<td class="green">' + data_arr[i][2] + '</td>' +
				'<td class="white">' + data_arr[i][14] + '</td>' +
				'<td class="yellow">' + data_arr[i][15] + '</td>' +
				'<td class="yellow">' + name_arr[i][3] + '</td>' +
				'<td class="yellow">' + data_arr[i][37] + '</td>' +
				'</tr>';
		}
		$(".tbody_table").append(html);
	}
};
// =>郑商所数据模式
function add_zss(data_arr, name_arr) {
	var color_class = "white";
	$(".updata_element").remove();
	for(var i = 0; i < data_arr.length; i++) {
		var name = i + 1;
		var src = "";
		if(data_arr[i][28] < 0) {
			src = "img/bottom.png";
			color_class = "green";
		} else if(data_arr[i][28] > 0) {
			src = "img/top.png";
			color_class = "red";
		} else if(data_arr[i][28] == 0) {
			src = "";
			color_class = "white";
		};
		if(data_arr[i].length < 5) {
			var html = '<tr class="updata_element"  short="">' +
				'<td class="yellow"><span><img src="' + src + '" alt="" /></span>' + name_arr[i][0] + '</td>' + //名称
				'<td class="' + color_class + '">--</td>' + //最新
				'<td class="green">--</td>' + //买-价
				'<td class="green">--</td>' + //卖一价
				'<td class="yellow">--</td>' + //买一量
				'<td class="yellow">--</td>' + //卖一量
				'<td class="' + color_class + '">--</td>' + //涨跌
				'<td class="' + color_class + '">--</td>' + //幅度
				'<td class="white">--</td>' + //昨结算
				'<td class="white">--</td>' + //结算价
				'<td class="yellow">--</td>' + //成交量
				'<td class="yellow">--</td>' + //持仓量
				'<td class="red">--</td>' + //涨停
				'<td class="green">--</td>' + //跌停
				'<td class="green">--</td>' + //今开
				'<td class="green">--</td>' + //最高
				'<td class="green">--</td>' + //最低
				'<td class="white">--</td>' + //昨收
				'<td class="yellow">--</td>' + //昨持仓量
				'<td class="yellow">' + name_arr[i][3] + '</td>' + //交易所
				'<td class="yellow">--</td>' + //时间
				'</tr>';
		} else {
			var html = '<tr class="updata_element"  short="' + name_arr[i][2] + '">' +
				'<td class="yellow"><span><img src="' + src + '" alt="" /></span>' + name_arr[i][0] + '</td>' + //名称
				'<td class="' + color_class + '">' + data_arr[i][8] + '</td>' + //最新
				'<td class="green">' + data_arr[i][6] + '</td>' + //买-价
				'<td class="green">' + data_arr[i][7] + '</td>' + //卖一价
				'<td class="yellow">' + data_arr[i][11] + '</td>' + //买一量
				'<td class="yellow">' + data_arr[i][12] + '</td>' + //卖一量
				'<td class="' + color_class + '">' + data_arr[i][28] + '</td>' + //涨跌
				'<td class="' + color_class + '">' + data_arr[i][29] + '</td>' + //幅度
				'<td class="white">' + data_arr[i][10] + '</td>' + //昨结算
				'<td class="white">' + data_arr[i][9] + '</td>' + //结算价
				'<td class="yellow">' + data_arr[i][14] + '</td>' + //成交量
				'<td class="yellow">' + data_arr[i][13] + '</td>' + //持仓量
				'<td class="red">--</td>' + //涨停
				'<td class="green">--</td>' + //跌停
				'<td class="green">' + data_arr[i][2] + '</td>' + //今开
				'<td class="green">' + data_arr[i][3] + '</td>' + //最高
				'<td class="green">' + data_arr[i][4] + '</td>' + //最低
				'<td class="white">' + data_arr[i][5] + '</td>' + //昨收
				'<td class="yellow">' + data_arr[i][13] + '</td>' + //昨持仓量
				'<td class="yellow">' + name_arr[i][3] + '</td>' + //交易所
				'<td class="yellow">' + data_arr[i][17] + '</td>' + //时间
				'</tr>';
		}
		$(".tbody_table").append(html);
	}
};
//国外交易所信息
function add_zqs(data_arr) {
	var color_class = "white";
	$(".updata_element").remove();
	for(var i = 0; i < data_arr.length; i++) {
		var name = i + 1;
		var src = "";
		if(data_arr[i][51] < 0) {
			src = "img/bottom.png";
			color_class = "green";
		} else if(data_arr[i][51] > 0) {
			src = "img/top.png";
			color_class = "red";
		} else if(data_arr[i][51] == 0) {
			src = "";
			color_class = "white";
		};
		var html = '<tr class="updata_element"  short="' + name_arr[i][2] + '">' +
			'<td class="yellow"><span><img src="' + src + '" alt="" /></span>' + name_arr[i][0] + '</td>' + //名称
			'<td class="' + color_class + '">' + data_arr[i][8] + '</td>' + //最新
			'<td class="green">' + data_arr[i][6] + '</td>' + //买-价
			'<td class="green">' + data_arr[i][7] + '</td>' + //卖一价
			'<td class="yellow">' + data_arr[i][11] + '</td>' + //买一量
			'<td class="yellow">' + data_arr[i][12] + '</td>' + //卖一量
			'<td class="' + color_class + '">--</td>' + //涨跌
			'<td class="' + color_class + '">--</td>' + //幅度
			'<td class="white">' + data_arr[i][10] + '</td>' + //昨结算
			'<td class="white">' + data_arr[i][9] + '</td>' + //结算价
			'<td class="yellow">' + data_arr[i][14] + '</td>' + //成交量
			'<td class="yellow">' + data_arr[i][13] + '</td>' + //持仓量
			'<td class="red">--</td>' + //涨停
			'<td class="green">--</td>' + //跌停
			'<td class="green">' + data_arr[i][2] + '</td>' + //今开
			'<td class="green">' + data_arr[i][3] + '</td>' + //最高
			'<td class="green">' + data_arr[i][4] + '</td>' + //最低
			'<td class="white">' + data_arr[i][5] + '</td>' + //昨收
			'<td class="yellow">' + data_arr[i][13] + '</td>' + //昨持仓量
			'<td class="yellow">' + name_arr[i][3] + '</td>' + //交易所
			'<td class="yellow">' + data_arr[i][17] + '</td>' + //时间
			'</tr>';
		$(".tbody_table").append(html);
	}
};

function add_abroad(data_arr) {
	var color_class = "white";
	$(".updata_element").remove();
	for(var i = 0; i < data_arr.length; i++) {
		//		var name = i + 1;
		var src = 'img/bottom.png';
		//		if(data_arr[i][51] < 0) {
		//			src = "img/bottom.png";
		//			color_class = "green";
		//		} else if(data_arr[i][51] > 0) {
		//			src = "img/top.png";
		//			color_class = "red";
		//		} else if(data_arr[i][51] == 0) {
		//			src = "";
		//			color_class = "white";
		//		};
		var html = '<tr class="updata_element"  short="' + data_arr[i][1] + '">' +
			'<td class="yellow"><span><img src="' + src + '"/></span>' + data_arr[i][0] + '</td>' + //名称
			'<td class="' + color_class + '">--</td>' + //最新
			'<td class="green">--</td>' + //买-价
			'<td class="green">--</td>' + //卖一价
			'<td class="yellow">--</td>' + //买一量
			'<td class="yellow">--</td>' + //卖一量
			'<td class="' + color_class + '">--</td>' + //涨跌
			'<td class="' + color_class + '">--</td>' + //幅度
			'<td class="white">--</td>' + //昨结算
			'<td class="white">--</td>' + //结算价
			'<td class="yellow">--</td>' + //成交量
			'<td class="yellow">--</td>' + //持仓量
			'<td class="red">--</td>' + //涨停
			'<td class="green">--</td>' + //跌停
			'<td class="green">--</td>' + //今开
			'<td class="green">--</td>' + //最高
			'<td class="green">--</td>' + //最低
			'<td class="white">--</td>' + //昨收
			'<td class="yellow">--</td>' + //昨持仓量
			'<td class="yellow">' + data_arr[i][4] + '</td>' + //交易所
			'<td class="yellow"></td>' + //时间
			'</tr>';
		$(".tbody_table").append(html);
	}
};