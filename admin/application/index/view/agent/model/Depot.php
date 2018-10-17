<?php

namespace app\agent\model;
use think\Model;

class Depot extends Model {

	protected $table = 'sn_depot';

    public function user()
    {
        return $this->belongsTo('Agent', 'uid', 'id');
    }
   
   public function query_log($map, $page, $size) {
        $list = $this->where($map)->relation(array('user'))->order('id asc')->page($page, $size)->select()->toArray();
        foreach ($list as $key => $value) {
            $value['time'] = detail_time($value['time']);
            $value['modify_time'] = detail_time($value['modify_time']);
            
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
