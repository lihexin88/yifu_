/*
 * istrue_placeorder : 下单前确认(1 为确认 2为不确认)
 * open_transaction_number 开仓默认手数 
 * customs_max_number 报单最大手数
 * close_number 平仓默认数量(1可平持仓量  2开仓默认量)
 * select_order_focus 选择合约焦点位置(1 合约 2 价格 3 手数 4不处理)
 * place_order_focus 下单后焦点位置(1 合约 2 价格 3手数 4 不处理)
 * place_order_close 下单后清空(1清空所有 2清空价格 3清空手数 4清空价格和手数 5不清空)
 * click_market_direction 单击行情买卖方向(1反向 2同向)
 * click_market_negation 单击行情按CRTL临时取反(1是 2否)
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
var data = {
	"istrue_placeorder": 1,
	"open_transaction_number": 1000
}