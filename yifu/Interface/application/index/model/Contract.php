<?php

namespace app\index\model;

use think\Model;

class Contract extends Model
{
    protected $table = 'sn_contract';

    /**
     * 增加账户余额
     * @param $id
     * @param $number
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add_account($id, $number)
    {
        return $this->where(array('uid' => $id))->find();
    }
}
