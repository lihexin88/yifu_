<?php

namespace app\common\model;

class AgentWith extends \think\Model {

    protected $table = 'sn_agent_withdraw';

    public function user() {
        return $this->hasOne('Agent', 'id', 'uid');
    }

    public function query_log($map, $page, $size) {
        $arr = $this->where($map)->relation('user')->order('id asc')->page($page, $size)->select()->toArray();
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

}
