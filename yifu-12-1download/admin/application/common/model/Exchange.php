<?php

namespace app\common\model;

use think\Model;

class Exchange extends Model
{
    protected $table = 'sn_exchange';


    public function query_log($map, $page, $size)
    {
        // 这个表示拿到的数据库里面的内容
        $arr = $this->where($map)->order('sort asc ,time desc')->page($page, $size)->select();
        foreach ($arr as $key => &$value) {
            if (empty($value['time'])) {
                $value['time'] = '/';
            } else {
                $value['time'] = detail_time($value['time']);
            }
            if ($value['type'] == 1) {
                $value['type'] = '国外';
            } else {
                $value['type'] = '国内';
            }
            if ($value['status'] == 0) {
                $value['status_text'] = '已禁用';
            } else {
                $value['status_text'] = '已启用';
            }
        }
        $data['data'] = $arr;
        $data['total'] = $this->where($map)->count();
        $data['num'] = page_num($data['total'], $size);
        return $data;
    }

}