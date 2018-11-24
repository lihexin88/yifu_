<?php

namespace app\common\model;

use think\Model;
use think\Db;

class Strategyconfig extends Model
{
    protected $table = 'sn_strategy_config';
     /**查询股票配置信息
     * @param $map
     * @param $page
     * @param $size 
     */
    public function query_log($map, $page, $size)
    {
        $arr = $this->where($map)->select()->toArray();
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
    //执行修改
    public function updates($data)
    {
          return $this->where('id',1)->update($data);
    }
}