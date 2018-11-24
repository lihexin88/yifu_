<?php

namespace app\agent\model;
use think\Model;

class Deal extends Model {

	protected $table = 'sn_capital';

    public function user() {
        return $this->belongsTo('User', 'uid', 'id');
    }

    public function query_log($map, $page, $size,$ratio) {
        $res = $this->where($map)->relation('user')->order('id asc')->page($page, $size)->select();
                foreach ($res as $k => $v) {
            $res[$k]['plat'] = $v['profit_loss'] * (1 - $ratio);
            $res[$k]['customer'] = $v['profit_loss'] * $ratio;
        }
        $list['data'] = $res;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}
