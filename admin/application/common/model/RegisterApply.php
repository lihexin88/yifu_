<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/17
 * Time: 10:28
 */

namespace app\common\model;


use think\Model;

class RegisterApply extends Model
{
    /**
     * 查询记录信息
     * @param $map
     * @param $page
     * @param $size
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function query_log($map, $page, $size)
    {
        $arr = $this->where($map)->order('time desc')->page($page, $size)->select();
        foreach ($arr as $key => &$value) {
            $value['time'] = detail_time($value['time']);
            $value['hand_time'] = detail_time($value['hand_time']);
            if ($value['status'] == 0) {
                $value['status'] = "未处理";
            } elseif ($value['status'] == 1) {
                $value['status'] = "已处理";
            } else {
                $value['status'] = "已处理";
            }
            switch ($value['type']) {
                case '1':
                    $value['type'] = '真实账户';
                    break;
                case '2':
                    $value['type'] = '模拟账户';
                    break;
                default:
                    $value['type'] = '模拟账户';
                    break;
            }
        }
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}