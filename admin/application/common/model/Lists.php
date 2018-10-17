<?php

namespace app\common\model;

use think\Model;

class Lists extends Model
{
    protected $table = 'sn_pc_list';

   
 
    public function query_log($map, $page, $size)
    {
        // 这个表示拿到的数据库里面的内容
        $arr = $this->where($map)->order('id','asc')->page($page, $size)->select();
        foreach ($arr as $key => &$value) {
            $value['time'] = detail_time($value['time']);
            switch ($value['type']) {
                case 1:
                    $value['type'] = '策略周榜';
                    break;
                case 2:
                    $value['type'] = '策略动态';
                    break;
               
            }
        }
        $data['data'] = $arr;
        $data['total'] = $this->where($map)->count();
        $data['num'] = page_num($data['total'], $size);
        return $data;
    }
    
}