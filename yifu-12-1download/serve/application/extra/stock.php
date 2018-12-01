<?php
/**
 * 波动值处理
 * @param int $min
 * @param int $max
 * @return string
 */
function randomFloat($min = 0, $max = 10)
{
    $num = $min + mt_rand() / mt_getrandmax() * ($max - $min);
    return round($num, 5);
}

/**
 * 开盘价格波动
 * @param $open float 开盘价格
 * @param $lowest float 最低价格
 * @param $highest float 最高价格
 * @param $wave float 波动价格
 * @return float|int
 */
function newest_open($open, $lowest, $highest, $wave)
{
    if ($open < $lowest) {
        $open = $open + abs($wave / 2);
    } else if ($open > $highest) {
        $open = $open - abs($wave / 2);
    }
    return $open;
}


/**
 * 计算最高价格和最低价格
 * @param $list array 数组信息
 * @return array
 */
function highest_lowest($list)
{
    $volume = 0;
    $highest = array();
    $lowest = array();
    foreach ($list as $k => $v) {
        $volume += $v[4];
        array_push($highest, $v[3]);
        array_push($lowest, $v[2]);
    }
    return array('volume' => $volume, 'highest' => max($highest), 'lowest' => min($lowest));
}


/**
 * 分时转换合并处理
 * @param $list array 数据信息
 * @param $times int 分割数量
 * @return array
 */
 function data_minute($list, $times)
{
    $list = array_chunk($list, $times);
    $data = array();
    foreach ($list as $key => $value) {
        $arr = array();
        $array = highest_lowest($value);
        array_push($arr, floatval($value[0][0]));
        array_push($arr, floatval($value[count($value) - 1][1]));
        array_push($arr, floatval($array['lowest']));
        array_push($arr, floatval($array['highest']));
        array_push($arr, floatval($array['volume']));
        array_push($arr, floatval($value[count($value) - 1][5] + 60));
        array_push($data, $arr);
    }
    return $data;
}






/**
 * 取文件最后$n行
 * @param string $filename 文件路径
 * @param int $line 最后几行
 * @param $tag
 * @return mixed false表示有错误，成功则返回字符串
 */
function FileLastLines($filename, $line, $tag = "")
{
    $handle = fopen($filename, 'r+');//读写模式打开文件，指针指向文件起始位置
    $pos = -1;
    $eof = "";
    $str = "";
    $string = 1;
    while ($line > 0 && $string) {
        while ($eof != $tag) {
            if (!fseek($handle, $pos, SEEK_END)) {
                $eof = fgetc($handle);
                $pos--;
            } else {
                break;
            }
        }
        $string = fgets($handle);
        if ($string) {
            $str = $string;
        }
        $eof = "";
        $line--;
    }
    return $str;
}


function read_file($filename, $count = 20, $tag = "")
{
    $content = "";//最终内容
    $current = "";//当前读取内容寄存
    $step = 1;//每次走多少字符
    $tagLen = strlen($tag);
    $start = 0;//起始位置
    $i = 0;//计数器
    $handle = fopen($filename, 'r+');//读写模式打开文件，指针指向文件起始位置
    while ($i < $count && !feof($handle)) {
        fseek($handle, $start, SEEK_SET);//指针设置在文件开头
        $current = fread($handle, $step);//读取文件
        $content .= $current;//组合字符串
        $start += $step;//依据步长向前移动
        $substrTag = substr($content, -$tagLen);//依据分隔符的长度截取字符串最后免得几个字符
        if ($substrTag == $tag) { //判断是否为判断是否是换行或其他分隔符
            $i++;
        }
    }
    fclose($handle);//关闭文件
    return $content;//返回结果
}

function format_time($time, $type)
{
    if (in_array($type, array(1, 2, 3))) {
        $time = date('Y.m.d', $time);
    } elseif (in_array($type, array(5, 6, 7, 8))) {
        $time = date('Y.m.d H:i', $time);
    } else {
        $time = date('Y.m.d H:i', $time);
    }
    return $time;
}