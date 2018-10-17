<?php

namespace app\index\controller;

use app\index\model\User;
use think\Controller;
use think\Request;

class Login extends Controller
{

    public function index()
    {
     return   $this->fetch('login');
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
        $this->User = new User();
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
     * 交易登录
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login()
    {
        $this->User = new User();
//        print_r($_POST);
//        exit;
        $user_id = $_POST['uid'];
        $password = $_POST['password'];
//        print_r($password);
//        exit();
        if (empty($user_id)) {
            $r = msg_handle('用户信息不能为空', 0);
        } else if (empty($password)) {
            $r = msg_handle('用户密码不能为空', 0);
        } else {
            $map['phone'] = $user_id;
            $map['password'] = md5($password);
//            print_r($map);
//            exit();
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
    public function loginpassword()
    {
        return $this->fetch();
    }
}