<?php

namespace app\common\model;

use think\Model;

class Admin extends Model
{
    protected $table = 'sn_admin';

    public function user()
    {
        return $this->belongsTo('User', 'id', 'id');
    }
    public function admin()
    {
        return $this->belongsTo('Admin_role', 'ro_id', 'id');
    }
    public function query_log($map, $page, $size)
    {
        // 这个表示拿到的数据库里面的内容
        $arr = $this->relation(array('user','admin'))->where($map)->order('id','asc')->page($page, $size)->select();
        foreach ($arr as $key => &$value) {
            $value['time'] = detail_time(
                $value['time']);
            switch ($value['status']) {
                case 0:
                    $value['status'] = '已禁用';
                    break;
                case 1:
                    $value['status'] = "已启用";
                    break;
            }
        }
        $data['data'] = $arr;
        $data['total'] = $this->where($map)->count();
        $data['num'] = page_num($data['total'], $size);
        return $data;
    }
    // 添加修改管理员
    public function add_edit($map){
        $data['name']= $map['name'];
        $data['password']= MD5($map['password']);
        $data['ro_id']= $map['ro_id'];
        $data['status']= $map['status'];
        $data['time'] = time();
        if(empty($map['id'])){
            return $this->insert($data);
        }else{
            $data['id'] = $map['id'];
            return  $this->update($data);
        }
    }   
}