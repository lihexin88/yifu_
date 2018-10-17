<?php

namespace app\common\model;

class Recharge extends \think\Model
{

    public function user()
    {
        return $this->hasOne('User', 'id', 'uid');
    }

    public function query_log($map, $page, $size)
    {
        $arr = $this->where($map)->relation('user')->order('time desc')->page($page, $size)->select()->toArray();
        foreach ($arr as $key =>&$value) {
            if ($value['time'] > 1000) {
                $value['time'] = date('Y-m-d H:i:s', $value['time']);
            }else{
                $value['time'] = '/';
            }
            if ($value['pay_time'] > 1000) {
                $value['pay_time'] = date('Y-m-d H:i:s', $value['pay_time']);
            }else{
                $value['pay_time'] = '/';
            }
            if ($value['pay_type'] == 1) {
                $value['pay_type'] = '网银支付';
            }if ($value['pay_type'] == 2) {
                $value['pay_type'] = '认证支付';
            }else{
                $value['pay_type'] = '其他';
            }
        }
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

}
