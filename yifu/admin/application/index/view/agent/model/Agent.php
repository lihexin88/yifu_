<?php

namespace app\agent\model;
use think\Model;

class Agent extends Model {

	protected $table = 'sn_agent';

    public function getAgent($id) {
        return $this->alias('u')
                        ->join('sn_agent_account a', 'u.id=a.uid')
                        ->where($id)
                        ->find();
    }
   public function query_log($map, $page, $size) {
        $list = $this->where($map)->order('id asc')->page($page, $size)->select()->toArray();
        foreach ($list as $key => $value) {
            $value['time'] = detail_time($value['time']);
            switch ($value['status']) {
                case 0:
                    $value['status'] = '已禁用';
                    break;
                case 1:
                    $value['status'] = "已启用";
                    break;
            }
        }
        $list['data'] = $list;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}
