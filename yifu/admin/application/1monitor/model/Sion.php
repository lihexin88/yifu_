<?php

namespace app\agent\model;

use think\Model;

class Sion extends Model {

    protected $table = 'sn_agent_fee';

    public function user() {
        return $this->belongsTo('User', 'uid', 'id');
    }

    public function capi() {
        return $this->belongsTo('Capital', 'capital', 'id');
    }

    public function query_log($map, $page, $size) {
        $res = $this->where($map)->relation('user')->relation('capi')->order('id asc')->page($page, $size)->select();
        $data = array();
        foreach ($res as $key => $value) {
            $arr['uid'] = $value['user']['id'];
            $arr['id'] = $value['id'];
            $arr['type'] = $this->type($value['type']);
            $arr['real_name'] = $value['user']['real_name'];
            $arr['phone'] = $value['user']['phone'];
            $arr['code'] = $value['capi']['code'];
            $arr['name'] = $value['capi']['name'];
            $arr['total'] = $value['capi']['total'];
            $arr['sell'] = $value['capi']['selling_price'] * $value['capi']['num'] + $value['capi']['surplus_price'];
            $arr['clear_time'] = detail_time($value['time']);
            $arr['number'] = $value['number'];
            $arr['remark'] = $value['remark'];
            array_push($data, $arr);
        }
        $list['data'] = $data;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

    public function type($type) {
        switch ($type) {
            case '1':
                $value['type'] = "递延费提成";
                break;
        }
        return $value['type'];
    }

}
