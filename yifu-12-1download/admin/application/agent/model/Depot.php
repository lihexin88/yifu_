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
        $list = $this->where($map)->relation(array('user'))->order('time asc')->page($page, $size)->select()->toArray();
        foreach ($list as $key => &$value) {
            $value['time'] = detail_time($value['time']);
            $value['modify_time'] = detail_time($value['modify_time']);
            $value['clean_time'] = detail_time($value['clean_time']);
            switch ($value['status']) {
                case '0':
                        $value['status'] = "持仓";
                    break;
                case '1':
                        $value['status'] = "平仓";
                    break;
                case '2':
                        $value['status'] = "停止";
                    break;
            }
            switch ($value['pattern']) {
                case '0':
                        $value['pattern'] = "投机";
                    break;
                case '1':
                        $value['pattern'] = "套利";
                    break;
                case '2':
                        $value['pattern'] = "套保";
                    break;
            }
            switch ($value['direction']) {
                case '0':
                        $value['direction'] = "买";
                    break;
                case '1':
                        $value['direction'] = "卖";
                    break;
            }
        }
        $list['data'] = $list;
        
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}
