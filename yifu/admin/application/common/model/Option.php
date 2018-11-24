<?php

namespace app\common\model;

class Option extends \think\Model {

    protected $table = 'sn_pc_admin_option';

    public function admin() {
        return $this->belongsTo('Admin', 'uid', 'id');
    }

    public function query_log($map, $page, $size) {
        $arr = $this->where($map)->relation('admin')->order('id asc')->page($page, $size)->select()->toArray();
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

}
