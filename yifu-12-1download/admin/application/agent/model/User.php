<?php

// namespace app\common\model;

// use think\Model;
use think\Db;
namespace app\agent\model;
class User extends \think\Model
{
    protected $table = 'sn_user';

    public function level()
    {
        return $this->belongsTo('Level', 'level', 'id')->field('id,name');
    }

    public function account()
    {
        return $this->belongsTo('UserAccount', 'id', 'uid');//->field('account');
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
        $this->Order = new Deal();
        $arr = $this->where($map)->relation(array('account'))->order('time desc')->page($page, $size)->select();
        foreach ($arr as $key => $value) {
            $arr[$key]['time'] = detail_time($value['time']);
            // $arr[$key]['login_time'] = detail_time($value['login_time']);
            // $value['day_count'] = $this->Order->where(array('uid'=>$value['id'],'time'=>['gt',date('Y-m-d',time())]))->count();//当日订单
            // $value['all_count'] = $this->Order->where(array('uid'=>$value['id']))->count();//历史订单
            if ($value['status'] == 1) {
                $value['status'] = "已启用";
            } else {
                $value['status'] = "已禁用";
            }
            // if ($value['is_agent'] == 1) {
            //     $value['is_agent'] = "代理商";
            // } else {
            //     $value['is_agent'] = "客户";
            // }
            // if ($value['is_forward'] == 1) {
            //     $value['is_forward'] = "打开提现";
            // } else {
            //     $value['is_forward'] = "关闭提现";
            // }
            // if ($value['frozen'] == 1) {
            //     $value['frozen'] = "解冻";
            // } else {
            //     $value['frozen'] = "冻结";
            // }
        }

        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

}
