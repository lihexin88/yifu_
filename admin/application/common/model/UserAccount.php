<?php

namespace app\common\model;

use think\Model;

class UserAccount extends Model
{
    protected $table = 'sn_user_account';

 
	/**
     * 增加账号金额
     * @param $id  int 用户id
     * @param $number  float 金额
     * @return int|true
     * @throws \think\Exception
     */
    public function add_account($id, $number)
    {
        return $this->where(array('uid' => $id))->setInc('account', $number);
    }
    
    
}