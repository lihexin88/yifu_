<?php

namespace app\common\model; 
use think\Model;

class Userdeal extends Model
{

    protected $table = 'sn_user_deal';

    public function query_log($map, $page, $size) {
        $list = $this->where($map)->relation(array('user'))->order('id asc')->page($page, $size)->select()->toArray();
        foreach ($list as $key => &$value) {
            switch ($value['type']) {
                case '1':
                        $value['type'] = "买";
                    break;
                case '2':
                        $value['type'] = "卖";
                    break;
            }
        }
        $list['data'] = $list;
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
        foreach ($arr as $key => &$value) {
            switch ($value['type']) {
                case '1':
                        $value['type'] = "买";
                    break;
                case '2':
                        $value['type'] = "卖";
                    break;
            }
        }
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }



}
