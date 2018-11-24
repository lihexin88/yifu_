<?php
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

function page_judge($page)
{
    return $page ? $page : 1;
}

/**
 * @param $num int 总页数
 * @param $page int 当前页数
 * @param $total int 总条数
 * @param $show int 展示个数
 * @return string
 */
function page_handling($num, $page, $show, $total)
{
    $num = $num == 0 ? 1 : $num;
    $html = '<ul class="am-pagination">' . first_page() . previous_page($page);
    if ($num >= $show) {
        if ($page <= $show) {
            $html .= for_page(1, $show, $page);
        } else {
            $html .= for_page($page - $show + 1, small_num($page + $show - 1, $num), $page);
        }
    } else {
        $html .= for_page(1, $num, $page);
    }
    $html .= next_page($num, $page) . end_page($num) . '</ul>';
    if ($total) {
        $html .= '<span>共&nbsp;' . $num . '&nbsp;页&nbsp;&nbsp;共' . $total . '条记录</span>';
    }
    return $html;
}

function for_page($i, $num, $page)
{
    $html = '';
    for (; $i <= $num; $i++) {
        if ($i == $page) {
            $li = '<li class="am-active"><a>' . $i . '</a></li>';
        } else {
            $li = '<li onclick="jumpPage(' . $i . ')"><a href="#">' . $i . '</a></li>';
        }
        $html .= $li;
    }
    return $html;
}

function previous_page($page)
{
    if ($page <= 1) {
        $pre = '<li class="am-disabled"><a href="#">&laquo;</a></li>';
    } else {
        $i = $page - 1;
        $pre = '<li onclick="jumpPage(' . $i . ')"><a href="#">&laquo;</a></li>';
    }
    return $pre;
}

function next_page($num, $page)
{
    if ($num <= 1 || $page >= $num) {
        $pre = '<li class="am-disabled"><a href="#">&raquo;</a></li>';
    } else {
        $i = $page + 1;
        $pre = '<li onclick="jumpPage(' . $i . ')"><a href="#">&raquo;</a></li>';
    }
    return $pre;
}

function first_page()
{
    return '<li onclick="jumpPage(' . 1 . ')"><a href="#"> 首页</a></li>';
}

function end_page($num)
{
    return '<li onclick="jumpPage(' . $num . ')"><a href="#">尾页</a></li>';
}

/**
 * 小返回
 * @param $num1
 * @param $num2
 * @return mixed
 */
function small_num($num1, $num2)
{
    return $num1 > $num2 ? $num2 : $num1;
}
