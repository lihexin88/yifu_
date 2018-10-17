<?php

namespace app\agent\model;
use think\Model;

class Withdrawinfo extends Model {

	protected $table = 'sn_agent_withdraw';

    public function user()
    {
        return $this->belongsTo('Agent', 'uid', 'id');
    }
   
   public function query_log($map, $page, $size) {
        $list = $this->where($map)->relation(array('user'))->order('id asc')->page($page, $size)->select()->toArray();
        foreach ($list as $key => $value) {
            $value['time'] = detail_time($value['time']);
            $value['pay_time'] = detail_time($value['pay_time']);
        }
        $list['data'] = $list;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}
