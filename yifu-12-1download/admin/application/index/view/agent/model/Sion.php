<?php

namespace app\agent\model;
use think\Model;

class Sion extends Model {

	protected $table = 'sn_commission_log';

    public function user()
    {
        return $this->belongsTo('Agent', 'uid', 'id');
    }
    public function users()
    {
        return $this->belongsTo('Agent', 'reid', 'id');
    }
   public function query_log($map, $page, $size) {
        $list = $this->where($map)->relation(array('user'))->order('id asc')->page($page, $size)->select()->toArray();
        foreach ($list as $key => $value) {
            $value['time'] = detail_time($value['time']);
            switch ($value['type']) {
                case '1':
                        $value['type'] = "充值";
                    break;
                case '2':
                        $value['type'] = "保证金";
                    break;
                case '3':
                        $value['type'] = "策略结算";
                    break;
            }
        }
        $list['data'] = $list;
        
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
    
}
