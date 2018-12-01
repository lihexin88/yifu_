<?php
function zerofill($num)
{
    return number_format(sprintf("%01.2f", round($num, 2)), 2);
}

function priceFill($num)
{
    return sprintf("%01.2f", $num);
}

/**
 * 股票判断
 * @param $r array
 * @param $stock  array 股票信息
 * @return array
 */
function trade_judge($r, $stock)
{
    if ($r['code'] == 1 && $stock['close'] == 0) {
        $r = array('msg' => '此股票暂停交易', 'code' => 0);
    }
    if ($r['code'] == 1 && $stock['stop'] == 0) {
        $r = array('msg' => '此股票已经停牌', 'code' => 0);
    }
    if ($r['code'] == 1 && $stock['buy'] == 0) {
        $r = array('msg' => '此股票暂时禁止购买', 'code' => 0);
    }
    if ($r['code'] == 1 && $stock['sell'] == 0) {
        $r = array('msg' => '此股票暂时禁止出售', 'code' => 0);
    }
    return $r;
}

/**
 * 时间处理
 * @param $data
 * @return array
 */
function time_handle($data)
{
    $arr = explode('|', $data);
    foreach ($arr as $key => $value) {
        $arr[$key] = explode(':', $value);
    }
    return $arr;
}

function price_percent($order)
{
    $arr = sina_stock_real($order);
    $list['price'] = round($arr[3], 2);
    $list['percent'] = round((($arr[3] - $arr[2]) / $arr[2] * 100), 2);
    return $list;
}

//获取实时价格
function sina_stock_real($code)
{
    $url = "http://hq.sinajs.cn/list=" . $code;
    $html = file_get_contents($url);
    $html = substr($html, 0, strlen($html) - 3);
    $html = substr($html, 21, strlen($html));
    return explode(',', $html);
}

function inside_day($sell_time, $buy_time, $inside_day)
{
    $time = strtotime(date('Ymd')) + 15 * 60 * 60;
    if ($sell_time > $time) {
        $end = strtotime(date('Ymd', $sell_time)) + 24 * 60 * 60;
    } else {
        $end = strtotime(date('Ymd', $sell_time));
    }
    $day = intval(($end - strtotime(date('Ymd', $buy_time))) / 24 / 60 / 60);
    $weekend = $work = 0;
    for ($i = 1; $i <= $day; $i++) {
        $section = $sell_time - $i * 24 * 60 * 60;
        if (date("w", $section) == 6 || date("w", $section) == 0) {
            $weekend += 1;
        } else {
            $work += 1;
        }
    }
    return $work . '/' . $inside_day;
}

/**
 * 新浪历史数据
 * @param $code string 股票代码 000001
 * @return mixed
 */
function getTagData($code)
{
    $url = 'http://money.finance.sina.com.cn/corp/go.php/vMS_MarketHistory/stockid/' . $code . '.phtml';
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $contents = curl_exec($ch);
    curl_close($ch);
    $regex = '/<td><div align="center">(.*?)<\/div><\/td>/';
    $reg = '/<td class="tdr"><div align="center">(.*?)<\/div><\/td>/';
    preg_match_all($regex, $contents, $matches, PREG_PATTERN_ORDER);
    preg_match_all($reg, $contents, $mat, PREG_PATTERN_ORDER);
    $arr['yes']['open'] = floatval($matches[1][4]);
    $arr['yes']['height'] = floatval($matches[1][5]);
    $arr['yes']['close'] = floatval($matches[1][6]);
    $arr['yes']['low'] = floatval($mat[1][3]);
    $arr['before']['open'] = floatval($matches[1][7]);
    $arr['before']['height'] = floatval($matches[1][8]);
    $arr['before']['close'] = floatval($matches[1][9]);
    $arr['before']['low'] = floatval($mat[1][6]);
    $arr['ago']['open'] = floatval($matches[1][10]);
    $arr['ago']['height'] = floatval($matches[1][11]);
    $arr['ago']['close'] = floatval($matches[1][12]);
    $arr['ago']['low'] = floatval($mat[1][9]);
    $list[0] = ($arr['yes']['height'] - $arr['yes']['low']) / $arr['before']['close'] * 100;
    $list[0] = abs(round($list['0'], 2));
    $list[1] = ($arr['before']['height'] - $arr['before']['low']) / $arr['ago']['close'] * 100;
    $list[1] = abs(round($list['1'], 2));
    $list[2] = ($arr['yes']['close'] - $arr['before']['close']) / $arr['before']['close'] * 100;
    $list[2] = abs(round($list['2'], 2));
    $list[3] = ($arr['before']['close'] - $arr['ago']['close']) / $arr['ago']['close'] * 100;
    $list[3] = abs(round($list['3'], 2));
    return $list;
}

/**
 * 搜狐历史数据
 * @param $code string 股票代码 000001
 * @return mixed
 */
function soHu_tag_data($code)
{
    $start = date('Ymd', (time() - 7 * 86400));
    $end = date('Ymd', time());
    $url = "http://q.stock.sohu.com/hisHq?code=cn_" . $code . "&start=" . $start . "&end=" . $end . "&stat=1&order=D&period=d&callback=historySearchHandler&rt=jsonp";
    $html = file_get_contents($url);
    $str = str_replace(array("historySearchHandler([", "])"), "", $html);
    $str = iconv("EUC-CN", "UTF-8", $str);
    $arr = json_decode($str, true);
    $list = $arr['hq'];
    $yes['open'] = floatval($list[0][1]);
    $yes['close'] = floatval($list[0][2]);
    $yes['low'] = floatval($list[0][5]);
    $yes['height'] = floatval($list[0][6]);
    $before['open'] = floatval($list[1][1]);
    $before['close'] = floatval($list[1][2]);
    $before['low'] = floatval($list[1][5]);
    $before['height'] = floatval($list[1][6]);
    $ago['open'] = floatval($list[2][1]);
    $ago['close'] = floatval($list[2][2]);
    $ago['low'] = floatval($list[2][5]);
    $ago['height'] = floatval($list[2][6]);
    $array[0] = ($yes['height'] - $yes['low']) / $before['close'] * 100;
    $array[0] = abs(round($array['0'], 2));
    $array[1] = ($before['height'] - $before['low']) / $ago['close'] * 100;
    $array[1] = abs(round($array['1'], 2));
    $array[2] = ($yes['close'] - $before['close']) / $before['close'] * 100;
    $array[2] = abs(round($array['2'], 2));
    $array[3] = ($before['close'] - $ago['close']) / $ago['close'] * 100;
    $array[3] = abs(round($array['3'], 2));
    return $array;
}

/**
 *  翻页处理
 * @param $total
 * @param $pageSize
 * @param $currPage
 * @return mixed
 */
function page_handel($total, $pageSize, $currPage)
{
    $total_page = floatval($total / $pageSize);
    if ($total_page != intval($total_page)) {
        $arr['num'] = intval($total_page) + 1;
    } else {
        $arr['num'] = intval($total_page);
    }
    $arr['currPage'] = $currPage;
    $arr['upPage'] = $currPage - 1;
    $arr['upPage'] = $arr['upPage'] <= 0 ? 1 : $arr['upPage'];
    $arr['downPage'] = $currPage + 1;
    $arr['downPage'] = $arr['downPage'] >= $arr['num'] ? $arr['num'] : $arr['downPage'];
    $arr['pageSize'] = $pageSize;
    return $arr;
}

/**
 * 涨幅和振幅
 * @param $data
 * @return mixed
 */
function stock_percent($data)
{
    $arr['up_down'] = round(($data[4] - $data[3]), 2);
    $arr['percent'] = round((($data[4] - $data[3]) / $data[3] * 100), 2);
    $arr['amplitude'] = round((($data[5] - $data[6]) / $data[3] * 100), 2);
    return $arr;
}

/**
 * 股票是否禁止交易
 * @param $stop
 * @param $code
 * @param $type
 * @param $list
 * @return mixed
 */
function stock_status($stop, $code, $type, $list)
{
    $arr['isTrade'] = 1;
    if ($arr['isTrade'] == 1 && $stop == 1) {
        $arr['isTrade'] = 0;
    }
    if ($arr['isTrade'] == 1 && trade_time() == 0) {
        $arr['isTrade'] = 0;
    }
    if ($arr['isTrade'] == 1 && $type == 0) {
        if (abs($list['percent']) > 7 || abs($list['amplitude']) > 7) {
            $arr['isTrade'] = 0;
        }
        if ($arr['isTrade'] == 1) {
            $list = getTagData($code);
            $total = $list[0] + $list[1];
            $total_x = $list[2] + $list[3];
            if ($list[0] > 7 || $list[2] > 7 || $total > 8 || $total_x > 8) {
                $arr['isTrade'] = 0;
            }
        }
        if ($arr['isTrade'] == 1) {
            $soHu = soHu_tag_data($code);
            $total_soHu = $soHu[0] + $soHu[1];
            $total_soHu_x = $soHu[0] + $soHu[1];
            if ($soHu[0] > 7 || $soHu[2] > 7 || $total_soHu > 8 || $total_soHu_x > 8) {
                $arr['isTrade'] = 0;
            }
        }
    }
    return $arr;
}

//是否为交易时间
function trade_time()
{
    $isTrade = 0;
    $res=closed_arrange();
    if (empty($res)) {
        $initial = strtotime(date('Ymd'));
        $am_start = $initial + 9.5 * 3600;
        $am_end = $initial + 11.5 * 3600;
        $pm_start = $initial + 13 * 3600;
        $pm_end = $initial + 15 * 3600;
        $current_time = time();
        if (date('w') != 6 && date('w') != 0) {
            if ($am_start <= $current_time && $am_end >= $current_time) {
                $isTrade = 1;
            }
            if ($pm_start <= $current_time && $pm_end >= $current_time) {
                $isTrade = 1;
            }
        };
    }
    return $isTrade;
}

/**
 *  休市安排
 */
function closed_arrange()
{
    $map['closed_time'] = strtotime(date('Ymd'));
    return \think\Db::name('closed')->where($map)->find();
}

/**
 * 获取单支股票实时价格
 * @param $code
 * @return array
 */
function stock_real($code)
{
    $url = "http://hq.sinajs.cn/list=" . $code;
    $contents = file_get_contents($url);
    $contents = str_replace(array("var hq_str_", "\""), "", $contents);
    $contents = str_replace(array("="), ",", $contents);
    $list = explode(',', $contents);
    foreach ($list as $k => $v) {
        if ($k < 2) {
            $list[$k] = trim(iconv('GBK', 'UTF-8', $v));
        } else if ($k < 9) {
            $list[$k] = sprintf("%01.2f", round($v, 2));
        } else {
            $list[$k] = floatval($v);
        }
    }
    return $list;
}

/**
 * 批量获取新浪股票实时价位
 * @param $code
 * @return array
 */
function stocks_real($code)
{
    $url = "http://hq.sinajs.cn/list=" . $code;
    $contents = file_get_contents($url);
    $list = explode(';', $contents);
    $arr = array();
    foreach ($list as $key => $value) {
        if (trim($value)) {
            $value = str_replace(array("var hq_str_", "\""), "", $value);
            $value = str_replace(array("="), ",", $value);
            $arr[$key] = explode(',', $value);
        }
    }
    foreach ($arr as $key => $value) {
        foreach ($value as $k => $v) {
            if ($k < 2) {
                $value[$k] = trim(iconv('GBK', 'UTF-8', $v));
            } else if ($k < 9) {
                $value[$k] = sprintf("%01.2f", round($v, 2));
            } else {
                unset($value[$k]);
            }
        }
        $arr[$key] = $value;
    }
    return $arr;
}

/**
 * 单个获取腾讯股票实时价位
 * @param $code
 * @return array
 */
function stock_tencent($code)
{
    $url = "http://sqt.gtimg.cn/utf8/q=" . $code;
    $contents = file_get_contents($url);
    $list = explode('v_', $contents);
    $arr = array();
    foreach ($list as $key => $value) {
        if (trim($value)) {
            $value = preg_replace("/=(.*?)~/", "~", $value);
            $value = str_replace(array("\";"), "", $value);
            $arr = explode('~', $value);
        }
    }
    return $arr;
}

/**
 * 批量获取腾讯股票实时价位
 * @param $codes
 * @return array
 */
function stocks_tencent($codes)
{
    $url = "http://sqt.gtimg.cn/utf8/q=" . $codes;
    $contents = file_get_contents($url);
    $list = explode('v_', $contents);
    $arr = array();
    foreach ($list as $key => $value) {
        if (trim($value)) {
            $value = preg_replace("/=(.*?)~/", "~", $value);
            $value = str_replace(array("\";"), "", $value);
            $arr[$key] = explode('~', $value);
        }
    }
    return $arr;
}
