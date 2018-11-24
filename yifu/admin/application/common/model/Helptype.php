<?php

namespace app\common\model;

use think\Model;

class Helptype extends Model
{
    protected $table = 'sn_pc_help_type';

   
   
    public function query_log($map, $page, $size)
    {
        // 这个表示拿到的数据库里面的内容
        $arr = $this->where($map)->order('id','asc')->page($page, $size)->select();
        foreach ($arr as $key => &$value) {
            $value['time'] = detail_time($value['time']);
            if($value['pid']==0){
                $value['cate_name']='顶级分类';
            }else{
                $name=$this->where('id',$value['pid'])->find();
                $value['cate_name'] = $name['name'];
            }
        }
        $data['data'] = $arr;
        $data['total'] = $this->where($map)->count();
        $data['num'] = page_num($data['total'], $size);
        return $data;
    }
   
}