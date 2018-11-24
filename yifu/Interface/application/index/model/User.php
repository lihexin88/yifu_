<?php

namespace app\index\model;

use think\Model;

class User extends Model
{
    protected $table = 'sn_user';

    public function account()
    {
        return $this->belongsTo('UserAccount', 'id', 'uid');
    }

    /**
     * 查询用户信息
     * @param $map array 条件
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function query_info($map)
    {
        return $this->where($map)->find();
    }

    /**
     * 注册用户
     * @param $phone  string 手机号码
     * @param $reid int 推荐人id
     * @param $agent int 代理id
     * @param $staff int 员工id
     * @return int|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function register($phone, $password, $reid)
    {
        $map['phone'] = $phone;
        $map['name'] = $phone;
        $map['code'] = $this->number();
        $map['reid'] = $reid;
        $map['password'] = md5($password);
        $map['time'] = time();
        return $this->insertGetId($map);
    }

    /**
     * 随机编号
     * @param string $number
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function number(&$number = '')
    {
        $str = substr(time(), -2, 2);
        $number = $str . chr(mt_rand(65, 90)) . rand(10, 99) . chr(mt_rand(65, 90)) . rand(0, 9);
        $r = $this->where(array('number' => $number))->find();
        if ($r) {
            $this->number($number);
        }
        return $number;
    }

    /**
     * 验证token是否正确
     * @param $token
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function verify_token($token)
    {
        return $this->where(array('token' => $token, 'status' => 1))->find();
    }

    /**
     * 修改登录密码
     * @param $id int 用户id
     * @param $password string 密码
     * @return $this
     */
    public function modify_login($id, $password)
    {
        $map['id'] = $id;
        $map['password'] = md5($password);
        $map['modify_time'] = time();
        return $this->update($map);
    }

    /**
     * 登录处理
     * @param $id int 用户id
     * @param $token
     * @return $this
     */
    public function login_data($id, $token)
    {
        $map['id'] = $id;
        $map['login_time'] = time();
        $map['login_ip'] = request()->ip();
        $map['token'] = $token;
        return $this->update($map);
    }

    /**
     * 修改用户是否绑定银行卡信息
     * @param $id
     * @param $real_name
     */
    public function modify_bank($id, $real_name)
    {
        $this->where('id', $id)->setField(array(
            'bank' => 1,
            'real_name' => $real_name
        ));
    }

    public function verify_phone($phone)
    {
        return 1;
    }
}