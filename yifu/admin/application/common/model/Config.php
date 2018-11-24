<?php

namespace app\common\model;

use think\Model;
use think\Db;

class Config extends Model {

    protected $table = 'sn_user_config';

    public function AgentAcc() {
        return $this->belongsTo('AgentAcc', 'id', 'uid');
    }

    /** 首页
     * @param $map
     * @param $page
     * @param $size
     * @return mixed
     */
    public function query_log($map, $page, $size)
    {
        $arr = $this->where($map)->order('time asc')->page($page, $size)->select();
        foreach ($arr as $key => $value) {
            $arr[$key]['time'] = detail_time($value['time']);
           
            if ($value['status'] == 1) {
                $value['status'] = "已启用";
            } else {
                $value['status'] = "已禁用";
            }

        }

        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

}
