<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/26
 * Time: 14:47
 */

namespace app\common\model;


use think\Model;

class CapitalFlow extends Model
{
    public function query_log($map, $page, $size)
    {
        $arr = $this->where($map)->order('time asc')->page($page, $size)->select();
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}