<?php

namespace app\index\model;

use think\Model;

class Deal extends Model
{
    protected $table = 'sn_deal';

    /**
     * 合约：IC1812，方向：买，开平：开仓，投保：投机，手数：15，价格：挂单价
     * @param $map
     * @return array
     */
    public function query_log($map)
    {
        $list = $this->where($map)->order(array('time desc'))->select();
        $data = array();
        foreach ($list as $key => $value) {
            $data[$key]['contract'] = trim($value['name']);
            $data[$key]['direction'] = $value['direction'] == 0 ? '买' : '卖';
            $data[$key]['mold'] = $value['mold'] == 0 ? '开仓' : '平仓';
            $data[$key]['number'] = $value['number'];
            $data[$key]['price'] = number_format($value['price'], 2);
            $data[$key]['fee'] = num_data($value['fee']);
            $data[$key]['id'] = $value['id'];
            $data[$key]['entrust'] = $value['entrust'];
            $data[$key]['date'] = date('Y.m.d', $value['time']);
            $data[$key]['time'] = date('H:i:s', $value['time']);
            $data[$key]['pattern'] = $value['pattern'] == 0 ? '投机' : ($value['pattern'] == 1 ? '套利' : '套保');
            $data[$key]['name'] = $value['name'];
        }
        return $data;
    }


}