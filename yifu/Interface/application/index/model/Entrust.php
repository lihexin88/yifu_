<?php

namespace app\index\model;

use think\Model;

class Entrust extends Model
{
    protected $table = 'sn_entrust';

    public function add_account($id, $number)
    {
        return $this->where(array('uid' => $id))->find();
    }
}
