<?php

namespace app\agents\model;

use think\Model;

class Flow extends Model {

    protected $table = 'sn_flow_log';

    public function user() {
        return $this->belongsTo('User', 'uid', 'id');
    }

    public function query_log($map, $page, $size) {
        $res = $this->where($map)->relation('user')->order('id asc')->page($page, $size)->select()->toArray();
        $data = array();
        foreach ($res as $key => $value) {
            $arr['id'] = $value['id'];
            $arr['phone'] = $value['user']['phone'];
            $arr['real_name'] = $value['user']['real_name'];
            $arr['type'] = $this->type($value['type']);
            $arr['number'] = $value['number'];
            $arr['balance'] = $value['balance'];
            $arr['mold'] = $value['mold'];
            $arr['time'] = detail_time($value['time']);
            $arr['desc'] = $value['desc'];
            array_push($data, $arr);
        }
        $list['data'] = $data;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

    public function type($type) {
        switch ($type) {
            case 1:
                $value['type'] = '网银支付';
                break;
            case 2:
                $value['type'] = "认证支付";
                break;
            case 3:
                $value['type'] = "申请提现";
                break;
            case 4:
                $value['type'] = "激活策略";
                break;
            case 5:
                $value['type'] = "追加保证金";
                break;
            case 6:
                $value['type'] = "追加策略金";
                break;
            case 7:
                $value['type'] = "减少策略金";
                break;
            case 8:
                $value['type'] = "结算策略金";
                break;
        }
        return $value['type'];
    }

}
