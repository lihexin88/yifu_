<?php

namespace app\common\model;

use think\Model;

class Bank extends Model
{
    protected $table = 'sn_bank';


    public function query_log($map, $page, $size)
    {
        // 这个表示拿到的数据库里面的内容
        $arr = $this->where($map)->order('id', 'asc')->page($page, $size)->select();
        foreach ($arr as $key => &$value) {
            $value['time'] = detail_time($value['time']);
//            原地址拼接
//            $value['pic'] =config('HOSTADMIN').$value['pic'].'.jpg' ;
//            若为空，则显示default.png
//            $value['pic'] =!empty($value['pic'])?"http://www.admin.com".$value['pic'].'.'.$value['pic_extension']:"http://www.admin.com/uploads/default.png";
            $value['pic'] = $value['pic'];
            switch ($value['status']) {
                case '1':
                    $value['status'] = '正常';
                    break;
                case '0':
                    $value['status'] = '禁用';
                    break;
            }
        }
        $data['data'] = $arr;
        $data['total'] = $this->where($map)->count();
        $data['num'] = page_num($data['total'], $size);
        return $data;
    }

    public function getList()
    {
        $map['status'] = 1;
        $arr = $this->where($map)->order('id', 'asc')->select();
        return $arr;
    }

}