<?php

namespace app\index\controller;

use app\index\model\User;
use think\Controller;
use think\Request;

class Login extends Controller
{
    private $User;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->User = new User();
    }

    /**
     * 登录验证
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function quotation_login($data)
    {
        $name = $data['phone'];
        $password = $data['password'];
        if (empty($name)) {
            $r = msg_handle('用户信息不能为空', 0);
        } elseif (empty($password)) {
            $r = msg_handle('用户密码不能为空', 0);
        } else {
            $map['phone|name'] = $name;
            $map['password'] = md5($password);
            $user = $this->User->query_info($map);
            if (empty($user)) {
                $r = msg_handle('用户信息或密码错误', 0);
            } elseif ($user['status'] == 0) {
                $r = msg_handle('此账号已被禁用.请联系管理员', 0);
            } else {
                session('uid', $user['id']);
                $r = msg_handle('登录成功', 1, array('uid' => $user['id']));
            }
        }
        return $r;
    }

    /**
     * 修改密码
     * @param $data id old_password 旧密码 new_password 新密码 password 确认密码
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit_pass()
    {
        // $data = $_POST;//打开即能用
        // $map['id'] = $data['id']; //打开即能用
        $map['id'] = '1';//测试
        // $old_password = MD5($data['old_password']); //打开即能用
        $old_password = MD5('111111');//测试
        // $data['new_password'] = MD5($data('new_password'));//打开即能用
        $data['new_password'] = MD5('123456');//测试
        // $data['password'] = MD5($data('password'));//打开即能用
        $data['password'] = MD5('123456');//测试
        $list = $this->User->where($map)->find();
        if ($old_password != $list['password']) {
            $r = msg_handle('旧密码不正确', 0);
        } elseif ($data['new_password'] != $data['password']) {
            $r = msg_handle('两次密码输入不一致', 0);
        } elseif ($list['password'] == $data['password']) {
            $r = msg_handle('新旧密码不能一样', 0);
        } else {
            $da['password'] = $data['password'];
            $lis = $this->User->where('id=' . $map['id'])->update($da);
            if ($lis) {
                session(null);
                $r = msg_handle('修改成功', 1);
            } else {
                $r = msg_handle('修改失败', 0);
            }
        }
        return json($r);
    }

    /**
     * 交易登录
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login($data)
    {
        $name = $data['phone'];
        $password = $data['password'];
        if (empty($name)) {
            $r = msg_handle('用户信息不能为空', 0);
        } elseif (empty($password)) {
            $r = msg_handle('用户密码不能为空', 0);
        } else {
            $map['phone|name'] = $name;
            $map['trade_password'] = md5($password);
            $user = $this->User->query_info($map);
            if (empty($user)) {
                $r = msg_handle('用户信息或密码错误', 0);
            } elseif ($user['status'] == 0) {
                $r = msg_handle('此账号已被禁用.请联系管理员', 0);
            } else {
                $token = createToken();
                if ($this->User->login_data($user['id'], $token)) {
                    session('id', $user['id']);
                    session('token', $token);
                    $r = msg_handle('登录成功', 1, array('token' => $token));
                } else {
                    $r = msg_handle('登录失败', 0);
                }
            }
        }
        return $r;
    }

    /**
     * 退出登录
     * @return array
     */
    public function logout()
    {
        session(null);
        $r = msg_handle('退出成功', 1);
        return $r;
    }
}