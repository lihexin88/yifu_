<?php

namespace app\index\model;

use think\Model;

class Protocol extends Model
{
    protected $table = 'sn_protocol';

    /**
     * 查询协议
     * @param $type
     * @return mixed
     */
    public function query_log($type)
    {
        return $this->where(array('id' => $type))->value('content');
    }
}
