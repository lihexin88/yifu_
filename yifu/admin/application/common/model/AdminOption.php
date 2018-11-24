<?php

namespace app\common\model;

use think\Model;

class AdminOption extends Model
{
    protected $table = 'sn_admin_option';

    public function query_log($map)
    {
        return $this->where($map)->find();
    }
}