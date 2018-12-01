<?php

namespace app\common\model;
use think\Model;

class Plat extends Model {

	protected $table = 'sn_piatfund';

    // public function user() 
    // {
    //     return $this->belongsTo('Agent', 'uid', 'id');->relation(array('user'))
    // }
   
   public function query_log($map, $page, $size) {
        $list = $this->where($map)->order('time desc')->page($page, $size)->select()->toArray();
        foreach ($list as $key =>&$value) {
            $value['time'] = detail_time($value['time']);
        }
        $list['data'] = $list;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}
