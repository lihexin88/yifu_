<?php
use think\Route;

Route::rule('login', 'Login/login', 'GET|POST');//登录账户 userName password

Route::rule('get_verify', 'Index/self_verify', 'POST|GET');//图片验证
Route::rule('short_msg', 'Index/register_code', 'POST|GET');//注册短信验证 phone
Route::rule('register_next', 'Index/register_next', 'POST|GET');
Route::rule('register', 'Index/register', 'POST|GET');
Route::rule('register_two', 'Index/register_two', 'POST|GET');
Route::rule('check_name', 'Index/check_name', 'POST|GET');//检测昵称是否存在 name


Route::rule('get_code', 'Index/get_code', 'POST|GET');//忘记密码短信验证 phone
Route::rule('forgot_pwd', 'Index/forgot_password', 'POST|GET');//忘记密码短信验证 phone code phone_code password confirm

Route::rule('account_center', 'Home/index', 'POST|GET'); //账号中心
Route::rule('home', 'Home/home', 'POST|GET'); //首页信息

Route::rule('prepaid_withdrawal', 'CapitalDetail/in_out_log', 'POST|GET'); //充值提款 page
Route::rule('loan_detail', 'CapitalDetail/loan_log', 'POST|GET'); //借款明细 page
Route::rule('service_fee', 'CapitalDetail/fee_log', 'POST|GET'); //服务费 page
Route::rule('profit_extraction', 'CapitalDetail/extract_log', 'POST|GET'); //利润提取 page
Route::rule('promotion_income', 'CapitalDetail/extension_log', 'POST|GET'); //推广收益 page

Route::rule('personal', 'Users/index', 'POST|GET'); //个人资料
Route::rule('modify_name', 'Users/modify_name', 'POST|GET'); //修改昵称 name
Route::rule('real_info', 'Users/certified_info', 'POST|GET');//实名认证信息
Route::rule('real_certify', 'Users/real_certify', 'POST|GET'); //实名认证操作 name card

Route::rule('modify_img_code', 'Users/get_code', 'POST|GET'); //修改登陆密码获取图片验证码
Route::rule('modify_phone_code', 'Users/phone_code', 'POST|GET'); //修改登陆密码获取手机验证码 phone
Route::rule('next_step', 'Users/next_step', 'POST|GET'); //修改登陆密码下一步操作 code msg_code

Route::rule('modify_login', 'Users/modify_login', 'POST|GET'); //修改登陆密码 new_pwd  confirm_pwd

Route::rule('modify_pay', 'Users/modify_pay', 'POST|GET'); //修改提款密码 new_pwd  confirm_pwd

Route::rule('bank_info', 'Banks/index', 'POST|GET'); //银行卡信息获取
Route::rule('bank', 'Banks/bind_info', 'POST|GET'); //银行信息
Route::rule('bind_bank', 'Banks/bind_bank', 'POST|GET'); //绑定银行操作 name bank_id province city open_bank card confirm;
Route::rule('modify_bank', 'Banks/modify_bank', 'POST|GET'); //修改银行操作 name bank_id province city open_bank card confirm;

Route::rule('join_us', 'Introduce/join_us', 'POST|GET'); //加入我们
Route::rule('contact_us', 'Introduce/contact_us', 'POST|GET'); //联系我们
Route::rule('about_us', 'Introduce/about_us', 'POST|GET'); //关于我们
Route::rule('safety_guarantee', 'Introduce/safety_guarantee', 'POST|GET'); //安全保障
Route::rule('media_coverage', 'Introduce/media_coverage', 'POST|GET'); //媒体报道

Route::rule('join_optional', 'Concern/join_optional', 'GET|POST');  //加入自选股 code
Route::rule('optional', 'Concern/index', 'GET|POST');  //自选股信息
Route::rule('delete_optional', 'Concern/delete_optional', 'GET|POST');  //取消自选股信息

Route::rule('message_center', 'MessageCenter/index', 'POST|GET'); //消息中心

Route::rule('news_center', 'NewsCenter/index', 'POST|GET');//新闻中心
Route::rule('look_news', 'NewsCenter/news_details', 'POST|GET');//查看新闻 id

Route::rule('capital_history', 'CapitalHistory/index', 'GET|POST');  //历史配资
Route::rule('capital_detailed', 'CapitalHistory/detailed', 'GET|POST');  //单个历史配资 id
Route::rule('trade_detailed', 'CapitalHistory/trade_detailed', 'GET|POST');  //历史配资交易详细 id

Route::rule('capital_current', 'CapitalCurrent/index', 'GET|POST');//当前配资
Route::rule('current_detailed', 'CapitalCurrent/detailed', 'GET|POST');//合约详情 id

Route::rule('increase_capital', 'Cooperate/capital_info', 'GET|POST');//追加配资 id
Route::rule('increase_operate', 'Cooperate/capital_operate', 'GET|POST');//追加配资操作 id number
Route::rule('increase_bond', 'Cooperate/bond_info', 'GET|POST');//追加保证金 id
Route::rule('bond_operate', 'Cooperate/bond_operate', 'GET|POST');//追加保证金操作 id  number
Route::rule('profit_info', 'Cooperate/profit_out', 'GET|POST');//利润提取 id
Route::rule('profit_operate', 'Cooperate/profit_operate', 'GET|POST');//利润提取操作 id number
Route::rule('apply_settlement', 'Cooperate/apply_settlement', 'GET|POST');//申请结算 id

Route::rule('capital_day', 'CapitalWith/capital_day', 'GET|POST');//每日配资
Route::rule('capital_month', 'CapitalWith/capital_month', 'GET|POST');//每月配资
Route::rule('apply_day', 'CapitalWith/apply_day', 'GET|POST'); //每日配资  bond 保证金 multiple配资倍数number配资金额
Route::rule('apply_month', 'CapitalWith/apply_month', 'GET|POST');//每月配资 bond 保证金 multiple配资倍数number配资金额

Route::rule('entrust_trade', 'Trade/index', 'GET|POST');//委托买卖 id
Route::rule('entrust_buy', 'TradeBuy/index', 'GET|POST');//购买 id code price type 1 买入 2市价买入 number
Route::rule('entrust_sell', 'TradeSell/index', 'GET|POST');//出售 id code price type 1 买入 2市价买入 number
Route::rule('usable_number', 'Trade/usable_number', 'GET|POST');//出售 id code

Route::rule('depot_stock', 'DepotStock/index', 'GET|POST');//持仓数据  id
Route::rule('cancel_order', 'DepotStock/cancel_order', 'GET|POST');//撤单 id
Route::rule('cancel_operate', 'DepotStock/cancel_operate', 'GET|POST');//撤单操作 id order

Route::rule('day_deal', 'CapitalRecord/day_deal', 'GET|POST');//当日成交  id
Route::rule('day_entrust', 'CapitalRecord/day_entrust', 'GET|POST');//当日委托 id
Route::rule('capital_flow', 'CapitalRecord/capital_flow', 'GET|POST');//资金流水 id
Route::rule('history_deal', 'CapitalRecord/history_deal', 'GET|POST');//历史成交  id
Route::rule('history_entrust', 'CapitalRecord/history_entrust', 'GET|POST');//历史委托 id

Route::rule('withdraw_info', 'Finance/withdraw_info', 'GET|POST');//提现充值信息
Route::rule('withdraw_operate', 'Finance/withdraw', 'GET|POST');//提现操作 number password
Route::rule('recharge_operate', 'Finance/recharge', 'GET|POST');//充值操作  number phone type

Route::rule('activity_info', 'ActivityInfo/index', 'GET|POST');//免息体验
Route::rule('next_stop', 'ActivityInfo/next_stop', 'GET|POST');//免息体验下一步
Route::rule('activity_operate', 'ActivityInfo/activity_operate', 'GET|POST');//立即体验

Route::rule('limit_buy', 'CapitalWith/buy_stock', 'GET|POST');

Route::rule('auto', 'Auto/auto', 'GET|POST');

Route::rule('scan_pay', 'ScanPay/recharge', 'GET|POST');
Route::rule('scan_callback', 'ScanPay/callback', 'GET|POST');
Route::rule('b2c_pay', 'B2C/recharge', 'GET|POST');
Route::rule('b2c_callback', 'B2C/callback', 'GET|POST');
Route::rule('recharge_other', 'Finance/website_set', 'GET|POST');
Route::rule('recharge_two', 'Finance/recharge_two', 'GET|POST');

Route::rule('quick_pay', 'NewPay/recharge', 'GET|POST');
Route::rule('quick_info', 'NewPay/info', 'GET|POST');
Route::rule('quick_sms', 'NewPay/sms_send', 'GET|POST');
Route::rule('quick_callback', 'NewPay/callback', 'GET|POST');
//Route::rule('aa', 'CyberBank/index', 'GET|POST');