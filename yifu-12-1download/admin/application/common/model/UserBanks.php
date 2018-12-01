<?php

namespace app\common\model;

class UserBanks extends \think\Model {

    protected $table = 'sn_user_bank';
    public function user() {
        return $this->hasOne('User', 'id', 'uid');
    }

    public function query_log($map, $page, $size) {
        $arr = $this->where($map)->relation('user')->order('uid asc')->page($page, $size)->select()->toArray();
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

    public function getOne($id) {
        $map = ['id' => $id];
        return $this->where($map)->order('uid asc')->find()->toArray();
    }

}
