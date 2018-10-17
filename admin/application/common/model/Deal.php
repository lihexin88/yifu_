<?php

namespace app\common\model; 
use think\Model;

class Deal extends Model {

	protected $table = 'sn_capital';

     public function user()
    {
        return $this->belongsTo('User', 'uid', 'id');
    }

   public function query_log($map, $page, $size) {
        $list = $this->where($map)->relation(array('user'))->order('id asc')->page($page, $size)->select()->toArray();
        foreach ($list as $key => &$value) {
            $value['time'] = detail_time($value['time']);
            $value['clear_time'] = detail_time($value['clear_time']);
            switch ($value['status']) {
                case '0':
                        $value['status'] = "已购买";
                    break;
                case '1':
                        $value['status'] = "已结算";
                    break;
            }
        }
        $list['data'] = $list;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}
