<?php

namespace app\common\model;
use think\Model;

class Agree extends Model {

	protected $table = 'sn_agree';

   
   public function query_log($map, $page, $size) {
        $list = $this->where($map)->order('id asc')->page($page, $size)->select()->toArray();
        foreach ($list as $key => &$value) {
            $value['time'] = detail_time($value['time']);
            // switch ($value['mold']) {
            //     case '0':
            //             $value['mold'] = "加钱";
            //         break;
            //     case '1':
            //             $value['mold'] = "减钱";
            //         break;
            // }
        }
        $list['data'] = $list;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}
