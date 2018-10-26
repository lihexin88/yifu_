<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/17
 * Time: 14:54
 */

namespace app\common\model;


use think\Model;

class Feedback extends Model
{
    public function query_log($map, $page, $size)
    {
        $arr = $this->where($map)->order('question_create_time asc')->page($page, $size)->select();
        foreach ($arr as $key => $value) {
            $arr[$key]['question_create_time'] = detail_time($value['question_create_time']);

            if ($value['question_type'] == 1) {
                $value['question_type'] = "交易";
            } else if($value['question_type'] == 2) {
                $value['question_type'] = "出入金";
            } else {
                $value['question_type'] = "其他";
            }

            if ($value['question_headle'] == 1) {
                $value['question_headle'] = "已处理";
            } else {
                $value['question_headle'] = "待处理";
            }
        }

        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}