<?php

namespace app\common\model;

use think\Model;

class Account extends Model
{
    protected $table = 'sn_user_account';
    public function user()
    {
        return $this->belongsTo('User', 'uid', 'id');
    }
 
    public function query_log($map, $page, $size)
    {
        // 这个表示拿到的数据库里面的内容
        $arr = $this->where($map)->relation(array('user'))->order('uid','asc')->page($page, $size)->select();
        foreach ($arr as $key => &$value) {
            $value['time'] = detail_time($value['time']);
            switch ($value['user']['status']) {
                case 1:
                    $value['user']['status'] = '操盘中';
                    break;
                case 0:
                    $value['user']['status'] = '冻结';
                    break;
               
            }
        }
        $data['data'] = $arr;
        $data['total'] = $this->where($map)->count();
        $data['num'] = page_num($data['total'], $size);
        return $data;
    }

    public function query_logs($map, $page, $size)
    {
        // 这个表示拿到的数据库里面的内容
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
            $value['time'] = detail_time($value['time']);
            switch ($value['user']['status']) {
                case 1:
                    $value['user']['status'] = '操盘中';
                    break;
                case 0:
                    $value['user']['status'] = '冻结';
                    break;
               
            }
        }
        $data['data'] = $arr;
        $data['total'] = count($arr);
        $data['num'] = page_num($data['total'], $size);
        return $data;
    }
    
}