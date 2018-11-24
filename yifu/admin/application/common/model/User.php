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
                $value['status'] = "已处理";
            } else {
                $value['status'] = "未处理";
            }if ($value['is_real'] == 1) {
                $value['real_status'] = "已实名";
            } else {
                $value['real_status'] = "未实名";
            }

        }
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

    /**
     * 用户注册信息
     * @param $data
     * @return int|string
     */
    public function register($data)
    {
        $res["name"] = $data["real_name"];
        $res["phone"] = $data["phone"];
        $res["password"] = md5($data["password"]);
        $res["safety_password"] = md5($data["trade_password"]);
        $res["time"] = time();
        $res["type"] = $data['type'];
        $res["card"] = $data["card"];
        $res["code"] = createOrderNum(2);
        $res["login_ip"] = $_SERVER["REMOTE_ADDR"];
        $res["token"] = md5($data["phone"] . time());
        $res["agent"] = $data["agent"];
        $res["reid"] = $data["reid"];
        $res["status"] = 1;
        return $this->insertGetId($res);
    }

}
