<?php
define('ACCESS_KEY', 'RYZItP7w1mIvbaiO');
define('SECRET', '8AwoUsexe7PSNzKcYVFahGh1M7pCJ7eY');
function msg_code($code, $phone)
{
    $url = 'http://api.1cloudsp.com/api/v2/single_send';
    $data['accesskey'] = ACCESS_KEY;
    $data['secret'] = SECRET;
    $data['sign'] = '【鼎泰盈】';
    $data['templateId'] = '6620';
    $data['mobile'] = $phone;
    $data['content'] = $code;
    return json_decode(postForm($url, $data), true);
}

/**
 * 8854
 * 验证码
 * 釜影策略注册\改密
 * 您的验证码是：{1}，请不要把验证码泄露给其他人。
 * @param $code
 * @param $phone
 * @return mixed  type==1
 */
function register_code($code, $phone)
{
    $url = 'http://api.1cloudsp.com/api/v2/single_send';
    $data['accesskey'] = ACCESS_KEY;
    $data['secret'] = SECRET;
    $data['sign'] = '【釜影策略】';
    $data['templateId'] = '8854';//釜影策略注册\改密
    $data['mobile'] = $phone;
    $data['content'] = $code;
    return json_decode(postForm($url, $data), true);
}
/**
 * 8857
 * 通知&订单
 * 釜影策略通知2
 * 尊敬的客户，您的合约号{1}已经跌破平仓线，依合同约定，我方有权择机平仓，请及时关注！
 * @param $code
 * @param $phone
 * @return mixed type==2
 */
function notice_close_code($order, $phone)
{
    $url = 'http://api.1cloudsp.com/api/v2/single_send';
    $data['accesskey'] = ACCESS_KEY;
    $data['secret'] = SECRET;
    $data['sign'] = '【釜影策略】';
    $data['templateId'] = '8857';//釜影策略注册\改密
    $data['mobile'] = $phone;
    $data['content'] = $order;
    return json_decode(postForm($url, $data), true);
}
/**
 * 8858
 * 通知&订单
 * 釜影策略短信通知3
 * 你已充值成功{1}元，请登陆账户，在我的账户中查询。
 * @param $code
 * @param $phone
 * @return mixed type==3
 */
function recharge_code($code, $phone)
{
    $url = 'http://api.1cloudsp.com/api/v2/single_send';
    $data['accesskey'] = ACCESS_KEY;
    $data['secret'] = SECRET;
    $data['sign'] = '【釜影策略】';
    $data['templateId'] = '8858';//釜影策略注册\改密
    $data['mobile'] = $phone;
    $data['content'] = $code;
    return json_decode(postForm($url, $data), true);
}
/**
 * 8859
 * 通知&订单
 * 釜影策略通知4
 * 你的提现资金{1}元系统已在处理中，请注意查收。当前账户余额{2}元，请确保余额能支付你近期的合约管理费。
 * @param $code
 * @param $phone
 * @return mixed type==4
 */
function with_code($phone,$money, $account)
{
    $url = 'http://api.1cloudsp.com/api/v2/single_send';
    $data['accesskey'] = ACCESS_KEY;
    $data['secret'] = SECRET;
    $data['sign'] = '【釜影策略】';
    $data['templateId'] = '8859';//釜影策略通知4
    $data['mobile'] = $phone;
    $data['content'] = $money.'##'.$account;
    return json_decode(postForm($url, $data), true);
}
/**
 * 8860
 * 通知&订单
 * 釜影策略认证通知
 * 恭喜您已通过实名认证
 * @param $code
 * @param $phone
 * @return mixed  type==5
 */
function real_name_code($phone)
{
    $url = 'http://api.1cloudsp.com/api/v2/single_send';
    $data['accesskey'] = ACCESS_KEY;
    $data['secret'] = SECRET;
    $data['sign'] = '【釜影策略】';
    $data['templateId'] = '8860';
    $data['mobile'] = $phone;
    $data['content'] = '';
    return json_decode(postForm($url, $data), true);
}
/**
 * 8868
 * 通知&订单
 * 釜影策略通知1
 * 会员账户不足：{1},离预警线：{2}，离平仓线{3}
 * 先生##9:40##快递公司##1234567 （示例模板：{1}您好，您的订单于{2}已通过{3}发货，运单号{4}）
 * @param $code
 * @param $phone
 * @return mixed type==6
 */
function account_code($code, $phone)
{
    $url = 'http://api.1cloudsp.com/api/v2/single_send';
    $data['accesskey'] = ACCESS_KEY;
    $data['secret'] = SECRET;
    $data['sign'] = '【釜影策略】';
    $data['templateId'] = '8868';//釜影策略注册\改密
    $data['mobile'] = $phone;
    $data['content'] = $code;
    return json_decode(postForm($url, $data), true);
}


/**
 * POST请求
 * @param $url
 * @param $data
 * @return mixed
 */
function postForm($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}