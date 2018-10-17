<?php
/**
 * 计算递延费用(利息)
 * @param $total
 * @param $fee
 * @return float|int
 */
function reckon_ratio($total, $fee)
{
    if ($total <= 10000) {
        $ratio = 1;
    } else {
        $ratio = floor($total / 10000) + 1;
    }
    return $ratio * 10000 * $fee;
}
