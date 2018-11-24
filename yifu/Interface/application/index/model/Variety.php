<?php

namespace app\index\model;

use think\Model;

class Variety extends Model
{
    protected $table = 'sn_variety';

    public function add_account($id, $number)
    {
        return $this->where(array('uid' => $id))->find();
    }
}
