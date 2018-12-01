//配置项
function get_config() {
	$.post(globalUrl, {
		nozzle: "find_user_config",
		token: localStorage.getItem("token")
	}, function(res) {
		/*
		 * istrue_placeorder : 下单前确认(1 为确认 2为不确认)
		 * open_transaction_number 开仓默认手数 
		 * customs_max_number 报单最大手数
		 * close_number 平仓默认数量(1可平持仓量  2开仓默认量)
		 * select_order_focus 选择合约焦点位置(1 合约 2 价格 3 手数 4不处理)
		 * place_order_focus 下单后焦点位置(1 合约 2 价格 3手数 4 不处理)
		 * place_order_close 下单后清空(1清空所有 2清空价格 3清空手数 4清空价格和手数 5不清空)
		 * click_market_direction 单击行情买卖方向(1反向 2同向 3按ctrl临时取反)
		 * order_cut_kaiping_direction 合约切换开平方向(1总是开仓 2自动开平)
		 * order_cut_market_type 合约切换下单价格类型(1限价 2最新价 3对手价 4挂单价 5快速价)
		 * list_operation_is_true 列表操作前确认(1确认 2不确认)
		 * double_click_order  双击挂单撤单(1确认 2不确认)
		 * double_click_open_close  双击持仓平仓(1确认 2取消确认)
		 * double_open_price 双击持仓平仓下单价格(1对手价 2快速成交价)
		 * double_open_num 双击持仓平仓下单手数(1可平持仓量 2开仓默认量)
		 * speediness_backhand  快速反手(1确认 2不确认)
		 * speediness_backhand_open_price 快速反手下单价格(1对手价 2快速成交价)
		 * speediness_locked_position  快速锁仓(1确认 2不确认)
		 * speediness_locked_open_price 快速锁仓下单价格(1对手价 2快速成交价)
		 * shortcut_operation 快捷操作锁定下单板(1是 2否)
		 * shortcut_operation_is_sure 快捷操作前确认(1是 2否)
		 * shortcut_buy_open 快捷操作买开
		 * shortcut_sell_open 快捷操作卖开
		 * shortcut_buy_close 快捷操作买平
		 * shortcut_sell_close 快捷操作卖平
		 * shortcut_operation_cancellations 快捷操作撤单
		 * shortcut_operation_inventory 快捷操作清仓
		 * shortcut_operation_buy_open 快捷操作买开价(1最新价 2对手价 3挂单价 4快速价)
		 * shortcut_operation_sell_open 快捷操作卖开价(1最新价 2对手价 3挂单价 4快速价)
		 * shortcut_operation_buy_close 快捷操作买平价(1最新价 2对手价 3挂单价 4快速价)
		 * shortcut_operation_sell_close 快捷操作卖平价(1最新价 2对手价 3挂单价 4快速价)
		 * message_alert_bargain 成交提示消息提示(1不提示 2窗口提示 3声音提示)
		 * message_alert_buy 下单提示(1不提示 2窗口提示 3声音提示)
		 * message_alert_cancellations 撤单提示(1不提示 2窗口提示 3声音提示)
		 * message_alert_condition 条件单提示(1不提示 2窗口提示 3声音提示)
		 * deriva_path 导出路径
		 * berth_location 下单板停靠位置(1靠左 2靠右)
		 * show_position_wire 是否展示持仓线(1显示 2不显示)
		 * */
		//下单版设置
		var config = res.data;
		var istrue_placeorder = config.istrue_placeorder; //下单前确认(1 为确认 2为不确认)
		var open_transaction_number = config.open_transaction_number; //开仓默认手数
		var customs_max_number = config.customs_max_number; //报单最大手数
		var close_number = config.close_number; //平仓默认数量(1可平持仓量  2开仓默认量)
		var select_order_focus = config.select_order_focus; //选择合约焦点位置(1 合约 2 价格 3 手数 4不处理)
		var place_order_focus = config.place_order_focus; //下单后焦点位置(1 合约 2 价格 3手数 4 不处理)
		var place_order_close = config.place_order_close; //下单后清空(1清空所有 2清空价格 3清空手数 4清空价格和手数 5不清空)
		var click_market_direction = config.click_market_direction; //单击行情买卖方向(1反向 2同向 3按ctrl临时取反)
		var order_cut_kaiping_direction = config.order_cut_kaiping_direction; //合约切换开平方向(1总是开仓 2自动开平)
		var order_cut_market_type = config.order_cut_market_type; //合约切换下单价格类型(1限价 2最新价 3对手价 4挂单价 5快速价)
		//列表操作
		var list_operation_is_true = config.list_operation_is_true; //列表操作前确认(1确认 2不确认)
		var double_click_order = config.double_click_order; //双击挂单撤单(1确认 2不确认)
		var double_click_open_close = config.double_click_open_close; //双击持仓平仓(1确认 2取消确认)
		var double_open_price = config.double_open_price; //双击持仓平仓下单价格(1对手价 2快速成交价)
		var double_open_num = config.double_open_num; //双击持仓平仓下单手数(1可平持仓量 2开仓默认量)
		var speediness_backhand = config.speediness_backhand; //快速反手(1确认 2不确认)
		var speediness_backhand_open_price = config.speediness_backhand_open_price; //快速反手下单价格(1对手价 2快速成交价)
		var speediness_locked_position = config.speediness_locked_position; //快速锁仓(1确认 2不确认)
		var speediness_locked_open_price = config.speediness_locked_open_price; //快速锁仓下单价格(1对手价 2快速成交价)
		//消息提示
		var message_alert_bargain = config.message_alert_bargain; //成交提示消息提示(1不提示 2窗口提示 3声音提示)
		var message_alert_buy = config.message_alert_buy; //下单提示(1不提示 2窗口提示 3声音提示)
		var message_alert_cancellations = config.message_alert_cancellations; //撤单提示(1不提示 2窗口提示 3声音提示)
		var message_alert_condition = config.message_alert_condition; //条件单提示(1不提示 2窗口提示 3声音提示)
		//系统设置
		var berth_location = config.berth_location; //下单板停靠位置(1靠左 2靠右)
		var show_position_wire = config.message_alert_condition; //是否展示持仓线(1显示 2不显示)
		/*
		 * 根据值操控dom 元素
		 * */
		//下单版设置 --> 下单前确认
		if(istrue_placeorder == 1) {
			$("#order_confirm").prop("checked", true);
		} else {
			$("#order_confirm").prop("checked", false);
		}
		//下单版设置 --> 开仓手数 --> 开仓默认手数
		$("#open_transaction_number").val(open_transaction_number);
		//下单版设置 --> 开仓手数 --> 报单最大手数
		$("#customs_max_number").val(customs_max_number);
		//下单版设置 --> 平仓默认数量
		if(close_number == 1) {
			$("[name='can_p']:eq(0)").attr("checked", "");
			$("[name='can_p']").not("[name='can_p']:eq(0)").removeAttr("checked");
		} else if(close_number == 2) {
			$("[name='can_p']:eq(1)").attr("checked", "");
			$("[name='can_p']").not("[name='can_p']:eq(1)").removeAttr("checked");
		}
		//下单版设置 --> 选择合约焦点位置
		var select_order_focus_eq = select_order_focus-1;
		$("[name='choice_focus']:eq(" + select_order_focus_eq + ")").attr("checked", "");
		//下单版设置 --> 选择合约焦点位置
		var place_order_focus_eq = place_order_focus-1;
		$("[name='order_focus']:eq(" + place_order_focus_eq + ")").attr("checked", "");
		//下单版设置 --> 下单后清空
		var place_order_close_eq = place_order_close-1;
		$("[name='clear']:eq(" + place_order_close_eq + ")").attr("checked", "");
		//下单版设置 --> 点击行情买卖方向
//		var click_market_direction = config.click_market_direction; //单击行情买卖方向(1反向 2同向 3按ctrl临时取反)
		//下单版设置 --> 合约切换开平方向
		var order_cut_kaiping_direction_eq = order_cut_kaiping_direction-1;
		$("[name='korp']:eq(" + order_cut_kaiping_direction_eq + ")").attr("checked", "");
		//下单版设置 --> 合约切换下单价格类型
		var order_cut_market_type_val = order_cut_market_type-1;
		$("#order_cut_market_type").val($(".module18_newstyle > li:eq(" + order_cut_market_type_val + ")").html());
		$(".module18_newstyle").attr("type",order_cut_market_type_val);
		//列表操作 --> 列表操作前确认
		if(list_operation_is_true == 1){
			$("#list_operation_confirm").prop("checked",true);
		}else{
			$("#list_operation_confirm").prop("checked",false);
		}
		//列表操作 --> 双击挂单撤单
//		console.log(list_operation_is_true);
	});
};
//console.log(customs_max_number)
get_config();