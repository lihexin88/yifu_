<?php

namespace app\agent\model;
use think\Model;

class Flow extends Model {

	protected $table = 'sn_flow_log';

    public function user()
    {
        return $this->belongsTo('Agent', 'uid', 'id');
    }
   
   public function query_log($map, $page, $size) {
        $list = $this->where($map)->relation(array('user'))->order('id asc')->page($page, $size)->select()->toArray();
        foreach ($list as $key => $value) {
            $value['time'] = detail_time($value['time']);
            switch ($value['type']) {
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
        switch ($value['mold']) {
            case '0':
                    $value['mold'] = "加钱";
                break;
            case '1':
                    $value['mold'] = "减钱";
                break;
        }
        }
        $list['data'] = $list;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}
