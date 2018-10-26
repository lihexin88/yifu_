<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/26
 * Time: 13:31
 */

namespace app\common\model;


use think\Model;

class Entrust extends Model
{
    public function query_log($map, $page, $size)
    {

// 这个表示拿到的数据库里面的内容
        $arr = $this->where($map)->order('id','asc')->page($page, $size)->select();

        $data['data'] = $arr;
        $data['total'] = $this->where($map)->count();
        $data['num'] = page_num($data['total'], $size);
        return $data;
    }

}