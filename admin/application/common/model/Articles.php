<?php

namespace app\common\model;

use think\Model;

class Articles extends Model
{
    protected $table = 'sn_new';

    public function article()
    {
        return $this->belongsTo('Articletype', 'type_id', 'id')->field('id,name');
    }
     public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,name');
    }
   public function query_log($map, $page, $size)
    {
        // 这个表示拿到的数据库里面的内容
        $arr = $this->where($map)->order('id','asc')->page($page, $size)->select();
        foreach ($arr as $key => $value) {
            $arr[$key]['time'] = detail_time($value['time']);
            if ($value['status'] == 1) {
                $value['status'] = "正常";
            } else {
                $value['status'] = "禁用";
            }
        }
        $data['data'] = $arr;
        $data['total'] = $this->where($map)->count();
        $data['num'] = page_num($data['total'], $size);
        return $data;
    }
    
}
