<?php

namespace app\index\model;

use think\Model;

class Agree extends Model
{
    protected $table = 'sn_agree';
    
    /**
     * 查询信息
     * @param $map
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function query_info($map)
    {
        return $this->where($map)->find();
    }
}