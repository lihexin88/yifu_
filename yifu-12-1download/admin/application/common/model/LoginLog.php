<?php

namespace app\common\model;

use think\Model;
use think\Db;

class LoginLog extends Model
{
    protected $table = 'sn_login_log';

    /**
     * 查询信息
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function query_logs($map, $page, $size)
    {
        $arr=array();
        //$all_arr = $this->where($map)->relation(array('user'))->order('time desc')->page($page, $size)->select()->toArray();
        $this->User=new User();
        $id = session('admin_id');
        $agents=$this->User->where(array("agent"=>$id))->select();
        if(isset($map['uid'])){
                $all_arr = $this->where($map)->order('id asc')->page($page, $size)->select();
                foreach($all_arr as $k=>$v){
                    $arr[]=$all_arr[$k];
                }
        }else{
            foreach($agents as $key=>$val){
                $map["uid"]=$val["id"];
                $all_arr = $this->where($map)->order('id asc')->page($page, $size)->select();
                foreach($all_arr as $k=>$v){
                        $arr[]=$all_arr[$k];
                }
            }
        }
        $list['data'] = $arr;
        $list['total'] = count($arr);
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}