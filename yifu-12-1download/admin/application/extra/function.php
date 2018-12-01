<?php
define('REG_PASSWORD', '/^[0-9A-Za-z]{6,12}$/');
define('REG_NUMBER', '/^(?![^a-zA-Z]+$)(?!\D+$).{6,12}$/');
define('REG_PHONE', '/^1[3|4|5|8|7][0-9]\d{8}$/');
define('REG_QQ', '/^[1-9][0-9]{4,9}$/');
define('REG_EMAIL', '/^[a-zA-Z0-9][a-zA-Z0-9._-]*\@[a-zA-Z0-9]+\.[a-zA-Z0-9\.]+$/A');
define("REG_CARD", '/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/');
define("REG_BANKCARD", '/^(\d{16}|\d{19})$/');
define('REG_NAME', '/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$/');
define('REG_NAME_CHAIN', '/^[\u4e00-\u9fa5]{4,10}|[0-9A-Za-z]{6,12}$/');
define('CHINESE_NAME', '/[\x80-\xff]{6,30}/');
define('POSITIVE_INTEGER', '/^[1-9]\d*$/');
define('REG_PASS', '/^[0-9A-Za-z]{6,32}$/');

/**
 * 信息处理
 * @param $msg
 * @param $code
 * @return array
 */
function msg_handle($msg, $code, $data = array() )
{
    return array('msg' => $msg, 'code' => $code ,'data'=>$data);
}
/**
 * 今日时间
 * @return false|int
 */
function day_time()
{
    return strtotime(date('Ymd', time()));
}
/**
 * 分页处理
 * @param $total int 总量
 * @param $num int 分页数量
 * @return int
 */
function page_num($total, $num)
{
    if ($total % $num) {
        $page = intval($total / $num) + 1;
    } else {
        $page = intval($total / $num);
    }
    return $page;
}


/**
 * 详细时间
 * @param $time
 * @return false|string
 */
function detail_time($time)
{
    if ($time >10000){
        return date("Y.m.d H:i:s", $time);
    }else{
        return '/';
    }
}

/**
 * 省略时间
 * @param $time
 * @return false|string
 */
function omit_time($time)
{
    return date("Y.m.d", $time);
}

/**
 * 创建TOKEN
 * @return string
 */
function createToken()
{
    $code = chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE)) .
        chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE)) .
        chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE));
    $token = authCode($code);
    session('token', $token);
    return $token;
}

/**
 * 输出当前变量的数值，并暂停方法
 */

function outpause($data,$position='',$type = '1')
{
    if($position){
        echo "now position:_____".$position."   ______<br>";
    }
    dump($data);
    if($type == 1){
        echo "___________________program stoped";
        echo "<br>_____________________________";
        exit;
    }

}/**
 *输出lastSQL
 */

function sql($sqlname,$position='',$type = '1')
{
    if($position){
        echo "当前位置：".$position."   ______<br>";
    }
    echo \think\Db::name($sqlname)->getLastSql();
    echo " _____";
    if($type == 1){
        echo "暂停程序";
        echo "<br>";
        exit;
    }

}


/**
 * 加密TOKEN
 * @param $str
 * @return string
 */
function authCode($str)
{
    $key = "AQUARIUM";
    $str = substr(md5($str), 8, 10);
    return md5($key . $str);
}

/**
 * 判断TOKEN
 * @param $token
 * @return bool
 */
function checkToken($token)
{
    if ($token == session('token')) {
        return true;
    } else {
        return false;
    }
}

function phone_handling($phone)
{
    return substr($phone, 0, 3) . '****' . substr($phone, 7);
}


/*
 * 状态转换
 * $status 1正常0禁用
 */
function statusType($status){
	if($status==1){
		return $status='正常';
	}else{
		return $status='禁用';
		
	}
	
}
/**
 * * 生成唯一的订单号 G20180328140950929538067
 * @param $type int
 * @return string
 */
function createOrderNum($type=1)
{
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'k', 'L', 'M', 'N', 'O', 'P', 'Q');
    list($usec, $sec) = explode(" ", microtime());
    $usec = substr(str_replace('0.', '', $usec), 0, 4);
    $str = rand(10000, 99999);
    if ($type == 2) {
        return $yCode[rand(0, 15)].rand(100,999).$yCode[rand(0, 15)]  . rand(0,9).$yCode[rand(0, 15)] . $yCode[rand(0, 15)];
    } else {
        return $yCode[rand(0, 15)] . $usec . date("YmdHis") . $usec . $str;
    }
}