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
    /** 有下级员工
     * @param $map
     * @param $page
     * @param $size
     * @return mixed
     */
    public function query($id, $map, $page, $size) {
        $res = $this->where($map)->relation('user')->order('id asc')->page($page, $size)->select();
        $arr = $this->whereIn('agent', $id)->relation('user')->order('id asc')->page($page, $size)->select()->toArray();
        $list['data'] = array_merge($res, $arr);
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

    /** 无下级员工
     * @param $map
     * @param $page
     * @param $size
     * @return mixed
     */
    public function log($map, $page, $size) {
        $arr = $this->where('agent', '<', '0')->order('id asc')->page($page, $size)->select();
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

}
