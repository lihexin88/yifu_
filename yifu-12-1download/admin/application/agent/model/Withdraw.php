<?php

namespace app\agent\model;
use think\Model;
use think\Db;

class Withdraw extends model
{
	public function bank()
    {
        return $this->belongsTo('UserBanks', 'uid', 'uid');
    }

    public function user()
    {
        return $this->belongsTo('User', 'uid', 'id');//->field('account');
    }

    protected $table = 'sn_withdraw';
    public function query_log($map, $page, $size) {
        $arr = $this->where($map)->relation('user,bank')->order('time desc')->page($page, $size)->select()->toArray();
        foreach ($arr as $key => &$value) {
            $value['time'] = detail_time($value['time']);
            $value['pay_time'] = detail_time($value['pay_time']);
        }
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

}
