<?php

namespace app\index\model;

use think\Model;

class Contract extends Model
{
    protected $table = 'sn_contract';

    public function query_log($map)
    {
        $list = $this->where($map)->select();
        $data = array();
        foreach ($list as $key => $value) {
            $data[$key]['id'] = $value['id'];
            $data[$key]['short'] = $value['short'];
            $data[$key]['code'] = $value['code'];
            $data[$key]['name'] = $value['name'];
            $data[$key]['bourse_name'] = $value['bourse_name'];
            $data[$key]['bourse_code'] = $value['bourse_code'];
            $data[$key]['industry'] = $value['industry'];
        }
        return $data;
    }

    /**
     * 验证股票是否存在
     * @param $map array 条件
     * @return int
     */
    public function validate_stock($map)
    {
        $map['status'] = 1;
        return $this->where($map)->find() ? 1 : 0;
    }

}