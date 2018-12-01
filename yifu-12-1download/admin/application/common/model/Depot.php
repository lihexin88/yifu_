<?php

namespace app\common\model;
use think\Model;

class Depot extends Model {

	protected $table = 'sn_depot';

    public function user() 
    {
        return $this->belongsTo('User', 'uid', 'id');
    }
   
   public function query_log($map, $page, $size) {
        $list = $this->where($map)->relation(array('user'))->order('time desc')->page($page, $size)->select()->toArray();
        foreach ($list as $key =>&$value) {
//            outpause($list);
//            $value['time'] = detail_time($value['time']);
//            $value['buy_total']=$value['num']*$value['buy_price'];
//            $value['sell_total']=$value['num']*$value['selling_price'];
            if (!$value['sell_total']){
                $value['sell_total']='/';
            }
//            $value['mother']='156156';
            switch ($value['status']) {
                case '0':
                    $value['status'] = "开盘";
                    break;
                case '1':
                    $value['status'] = "停止";
                    break;
            }
        }
        $list['data'] = $list;
        
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}
