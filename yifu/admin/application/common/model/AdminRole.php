<?php

namespace app\common\model;

use think\Model;

class AdminRole extends Model
{
    /**
     * @var string 角色管理模型
     */
    protected $table = 'sn_admin_role';


    public function query_log($map, $page, $size)
    {
        // 这个表示拿到的数据库里面的内容
        $arr = $this->where($map)->order('id','asc')->page($page, $size)->select();
        foreach ($arr as $key => $value) {
            $value['time_text'] = detail_time($value['time']);
        }
        $data['data'] = $arr;
        $data['total'] = $this->where($map)->count();
        $data['num'] = page_num($data['total'], $size);
        return $data;
    }
    // 添加修改角色
    public function add($map){
        $data['name'] = $map['name'];
        $data['time'] = time();
        if(empty($map['id'])){
            $this->insert($data);
            $userid = $this->getLastInsID();
            return $userid;
        }else{
            return $this->where('id='.$map['id'])->update($data);
        }
    }
}