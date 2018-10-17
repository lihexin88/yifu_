<?php

namespace app\common\model;

use think\Model;
use think\Db;
use app\common\model\Account;


class User extends Model
{
    protected $table = 'sn_user';

    public function level()
    {
        return $this->belongsTo('Level', 'level', 'id')->field('id,name');
    }
    public function account()
    {
        return $this->belongsTo('Account', 'id', 'uid')->field('account');
    }

    /**查询会员信息
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

        $arr = $this->where($map)->relation(array('account'))->order('time asc')->page($page, $size)->select();
        foreach ($arr as $key => $value) {
            $arr[$key]['time'] = detail_time($value['time']);

            if ($value['status'] == 1) {
                $value['status'] = "已启用";
            } else {
                $value['status'] = "已禁用";
            }
            if ($value['re_status'] == 1) {
                $value['re_status'] = "已通过";
            } elseif($value['re_status'] == 2) {
                $value['re_status'] = "已拒绝";
            }else{
                $value['re_status'] = "未处理";
            }

        }

        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

}
