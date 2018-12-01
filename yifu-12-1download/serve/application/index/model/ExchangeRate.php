<?php

namespace app\index\model;

use think\Model;

class ExchangeRate extends Model
{
    protected $table = 'sn_exchange_rate';

    /**
     * 查询协议
     * @return mixed
     */
    public function query_log()
    {
        $list = $this->where(array('status' => 1))->select();
        foreach ($list as $key => $value) {
            $data[$key]['name'] = $value['name'];
            $data[$key]['base'] = $value['base'];
            $data[$key]['ratio'] = $value['ratio'];
        }
        return;
    }
}


