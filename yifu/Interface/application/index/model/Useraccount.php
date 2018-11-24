<?php

namespace app\index\model;

use think\Model;

class Useraccount extends Model
{
    protected $table = 'sn_user_account';

    public function add_account($id, $number)
    {
        return $this->where(array('uid' => $id))->find();
    }
}
