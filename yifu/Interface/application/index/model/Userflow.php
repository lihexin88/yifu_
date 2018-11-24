<?php

namespace app\index\model;

use think\Model;

class Userflow extends Model
{
    protected $table = 'sn_flow_log';

    public function add_account($id, $number)
    {
        return $this->where(array('uid' => $id))->find();
    }
}
