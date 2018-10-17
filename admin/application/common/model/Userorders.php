<?php

namespace app\common\model;

use think\Model;
use think\Db;
class Userorders extends Model
{

    protected $table = 'sn_order';

    public function query_log($map, $page, $size) {
        $arr = $this->where($map)->relation('user')->order('time desc')->page($page, $size)->select()->toArray();
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

    public function query_logs($map, $page, $size) {
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
        //$arr = $this->where($map)->relation('user')->order('time desc')->page($page, $size)->select()->toArray();
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

    public function add_account($id, $number)
    {
        return $this->where(array('uid' => $id))->find();
    }

}
