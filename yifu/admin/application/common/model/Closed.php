<?php

namespace app\common\model;

use think\Model;

class Closed extends Model
{
    protected $table = 'sn_closed';

   
   
    public function query_log($map, $page, $size)
    {
        // 这个表示拿到的数据库里面的内容
        $arr = $this->where($map)->order('id','asc')->page($page, $size)->select();
        foreach ($arr as $key => &$value) {
            if (empty($value['time'])) {
                $value['time']  = '/';
            }else{
                $value['time'] = detail_time($value['time']);
            }

            if (empty($value['closed_time'])) {
                $value['closed_time']  = '/';
            }else{
                $value['closed_time'] = detail_time($value['closed_time']);
            }

            if (empty($value['add_time'])) {
                $value['add_time']  = '/';
            }else{
                $value['add_time'] = detail_time($value['add_time']);
            }
            //$value['pic'] =config('HOSTADMIN').$value['pic'] ;
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
    public function getList(){
        $map['status']=1;
        $arr = $this->where($map)->order('id','asc')->select();
        return $arr;
    }
   
}