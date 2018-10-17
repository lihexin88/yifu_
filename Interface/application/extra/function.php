<?php
define('REG_PASSWORD', '/^[0-9A-Za-z]{6,12}$/');
define('REG_NUMBER', '/^(?![^a-zA-Z]+$)(?!\D+$).{6,12}$/');
define('REG_PHONE', '/^1[3|4|5|8|7|6|9][0-9]\d{8}$/');
define('REG_QQ', '/^[1-9][0-9]{4,9}$/');
define('REG_EMAIL', '/^[a-zA-Z0-9][a-zA-Z0-9._-]*\@[a-zA-Z0-9]+\.[a-zA-Z0-9\.]+$/A');
define("REG_CARD", '/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/');
define("REG_BANKCARD", '/^(\d{16}|\d{19})$/');
define('REG_NAME', '/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$/');
define('REG_NAME_CHAIN', '/^[\u4e00-\u9fa5]{4,10}|[0-9A-Za-z]{6,12}$/');
define('CHINESE_NAME', '/[\x80-\xff]{6,30}/');
define('POSITIVE_INTEGER', '/^[1-9]\d*$/');

/**
 * 信息处理
 * @param $msg  string 提示内容
 * @param $code string 状态码
 * @param $data array 内容
 * @return array
 */
function msg_handle($msg, $code, $data = array())
{
    return array('msg' => $msg, 'code' => $code, 'data' => $data);
}

function admin_url()
{
    return 'http://' . $_SERVER['HTTP_HOST'];
}

function num_data($num)
{
    return sprintf("%.2f", $num);
}

/**
 * 当日时间
 * @return false|int
 */
function day_time()
{
    return strtotime(date('Ymd', time()));
}
/**
 * 获取创建策略时续费时间
 */
function  get_renew_time(){
    if (date('w', day_time()) == 5) {
        $map['renew_time'] = day_time() + 86400 * 4;
    } else {
        $map['renew_time'] = day_time() + 2 * 86400;
    }
    return $map['renew_time'];
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
    return date("Y.m.d H:i:s", $time);
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
 * * 生成唯一的订单号 G20180328140950929538067
 * @param $type int
 * @return string
 */
function createOrderNum($type = 1)
{
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'k', 'L', 'M', 'N', 'O', 'P', 'Q');
    list($usec, $sec) = explode(" ", microtime());
    $usec = substr(str_replace('0.', '', $usec), 0, 4);
    $str = rand(10000, 99999);
    if ($type == 2) {
        return $yCode[rand(0, 15)] . rand(100, 999) . $yCode[rand(0, 15)] . rand(0, 9) . $yCode[rand(0, 15)] . $yCode[rand(0, 15)];
    } elseif ($type == 3) {
        return $yCode[rand(0, 15)] . $yCode[rand(0, 15)] . rand(100, 999) . $str;
    } else {
        return $yCode[rand(0, 15)] . $usec . date("YmdHis") . $usec . $str;
    }
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
 * 加密TOKEN
 * @param $str
 * @return string
 */
function authCode($str)
{
    $key = "andiamon";
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

/**
 * 数组转json2
 * @param $array
 * @return string
 */
function je($array)
{
    return json_encode($array);
}

/**
 * json转数组
 * @param $str
 * @return array
 */
function jd($str)
{
    return json_decode($str, true);
}

/**
 * 数字处理
 * @param $money
 * @return string
 */
function format_money($money)
{
    if ($money >= 10 && $money < 100) {
        $str = ($money / 10) . '十';
    } else if ($money >= 100 && $money < 1000) {
        $str = ($money / 100) . '百';
    } else if ($money >= 1000 && $money < 10000) {
        $str = ($money / 1000) . '千';
    } else if ($money >= 10000) {
        $str = ($money / 10000) . '万';
    } else {
        $str = $money;
    }
    return $str;
}

/**
 * 随机单号
 * @param $id int 用户id
 * @return string
 */
function rand_order($id)
{
    $number = time() . str_shuffle(sprintf('%05d', $id));
    $number = str_shuffle($number);
    $order = substr($number, rand(0, strlen($number) - 5), 5) . '-' . substr($number, rand(0, strlen($number) - 5), 5);
    return $order;
}

/**
 * 日期处理
 * @param $start int 开始时间
 * @param $end int 结束时间
 * @return array
 */
function dateQuery($start, $end)
{
    if ($start && $end) {
        $end = $end + 24 * 60 * 60;
        $map = array(array('egt', $start), array('elt', $end));
    } else if (empty($start) && $end) {
        $end = $end + 24 * 60 * 60;
        $map = array('elt', $end);
    } else if ($start && empty($end)) {
        $map = array('egt', $start);
    } else {
        $map = array();
    }
    return $map;
}
/**
 * 可买股数
 * 资金利用率
 * @param $total
 * @param $stock_price
 * @return float|int
 */
function shares_number($total, $stock_price)
{
    $a = $total / $stock_price;
    $b = fmod($a, 100);
    $now_price = ($a - $b) * $stock_price;
    $ratio = $now_price / $total;
    $data['shares_number']=$a-$b;
    $data['funds_use_ratio']=$ratio*100;
    $data['funds_use_ratio']=$data['funds_use_ratio'].'%';
    return $data;
}
function get_bond($total,$multiple){
    $a = $total / $multiple;
    $b = fmod($a, 10);
    if ($b != 0){
        $bond = ($a-$b)+10;
    }else{
        $bond=$a;
    }
    return $bond;
}