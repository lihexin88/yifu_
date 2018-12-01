<?php

namespace app\common\model; 

use think\Db;
use think\Model;
use app\common\model\Agent;

class Deal extends Model {

	protected $table = 'sn_deal';

     public function user()
    {
        return $this->belongsTo('User', 'uid', 'id');
    }

   public function query_log($map, $page, $size) {
        $list = $this->where($map)->order('id asc')->page($page, $size)->select()->toArray();
//        outpause($list);
//        echo Db::name('sn_deal')->getLastSql();
        foreach ($list as $key => &$value) {
            $value['time'] = detail_time($value['time']);
            switch ($value['status']) {
                case '0':
                        $value['status'] = "已购买";
                    break;
                case '1':
                        $value['status'] = "已结算";
                    break;
            }
            switch ($value['type']){
                case '0':
                    $value['type'] = "限价";
                    break;
                case '1':
                    $value['type'] = "最新价";
                    break;
                case '2':
                    $value['type'] = "对手价";
                    break;
                case '3':
                    $value['type'] = "挂单价";
                    break;
                case '4':
                    $value['type'] = "快速价";
                    break;
            }
        }
        $list['data'] = $list;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}
