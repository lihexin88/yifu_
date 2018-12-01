<?php

namespace app\agent\model;
use think\Db;
class Recharge extends \think\Model {
	protected $table = 'sn_recharge';
	public function user()
    {
        return $this->belongsTo('User', 'uid', 'id'); 
    }
    public function account()
    {
        return $this->belongsTo('UserAccount', 'uid', 'uid'); 
    }
    public function getList($where) {
        return Db::table('sn_recharge')->alias('r')
                        ->join('sn_user u', 'r.uid=u.id')
                        ->where($where)
                        ->field('u.real_name,r.number,r.pay_type,r.time')
                        ->order('r.time desc')
                        ->select();
    }
    public function query_log($map, $page, $size) {
        $list = $this->where($map)->relation('user,account')->order('id asc')->page($page, $size)->select()->toArray();
        foreach ($list as $key => &$value) {
            $value['time'] = detail_time($value['time']);
            $value['pay_time'] = detail_time($value['pay_time']);
        }
        $list['data'] = $list;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}
