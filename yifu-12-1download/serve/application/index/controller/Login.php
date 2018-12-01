<?php

namespace app\index\controller;

use app\index\model\User;
use think\Controller;
use think\Request;

class Login extends Controller
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->User = new User();
    }
    /**
     * 登录验证
     * @param $data array 介绍信息
     * @return array
     */
    public function index($data)
    {
        $name = empty($data['phone']) ? '' : $data['phone'];
        $password = empty($data['password']) ? '' : $data['password'];
        $type = empty($data['type']) ? 0 : $data['type'];//0 行情
        if (empty($name)) {
            $r = msg_handle('请输入登录账号', 0);
        } elseif (empty($password)) {
            $r = msg_handle('用户密码不能为空', 0);
        } elseif ($type != 0 && $type != 1) {
            $r = msg_handle('登录类型选择错误', 0);
        } else {
            $map['phone|name|number'] = $name;
            $map['password'] = md5($password);
            $user = $this->User->query_info($map);
            if (empty($user)) {
                $r = msg_handle('登录账号或密码错误', 0);
            } elseif ($user['status'] == 0) {
                $r = msg_handle('此用户已被暂停使用', 0);
            } else {
                $token = createToken();
                if ($this->User->login_data($user, $token)) {
                    session('id', $user['id']);
                    session('token', $token);
                    $r = msg_handle('', 1, array('token' => $token, 'type' => $type));
                } else {
                    $r = msg_handle('登录失败', 0);
                }
            }
        }
        return $r;
    }


    public function login_index()
    {
     return   $this->fetch('login_index');
    }

    /**
     * 登录验证
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function quotation_login()
    {
        $this->User = new User();
        $name = $_POST["phone"];
        $password = $_POST["password"];
        if (empty($name)) {
            $r = msg_handle('用户信息不能为空', 0);
        } elseif (empty($password)) {
            $r = msg_handle('用户密码不能为空', 0);
        } else {
            $map['phone|name'] = $name;
            $map['password'] = md5($password);
            $user = $this->User->query_info($map);
            if (empty($user)) {
                $r = msg_handle('用户信息或密码错误', -1);
            } elseif ($user['status'] == 0) {
                $r = msg_handle('此账号已被禁用.请联系管理员', 0);
            } else {
                session('id', $user['id']);
                $r = msg_handle('登录成功', 1);
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

    
    /**
     * 修改安全密码
     * @param $id int 用户id
     * @param $data array 用户传入数据
     * @return array array json数组
     */
    public function edit_pass($id, $data)
    {
        $user = $this->User->where('id', $id)->field('safety_password')->find();
        if (empty($data['old_pass'])) {
            $r = msg_handle('旧密码不能为空', 0);
        } elseif (empty($data['new_pass'])) {
            $r = msg_handle('新密码不能为空', 0);
        } elseif (empty($data['con_pass'])) {
            $r = msg_handle('确认密码不能为空', 0);
        } elseif ($data['con_pass'] != $data['new_pass']) {
            $r = msg_handle('两次密码不一致', 0);
        }  elseif ($data['old_pass'] == $data['new_pass']) {
            $r = msg_handle('新密码与旧密码一致', 0);
        } elseif (!reg_password($data['con_pass'])) {
            $r = msg_handle('新密码格式有误', 0);
        } elseif (md5($data['old_pass']) != $user['safety_password']) {
            $r = msg_handle('原密码错误', 0);
        } else {
            $res = $this->User->where('id', $id)->update(array(
                'safety_password' => md5($data['con_pass'])
            ));
            if ($res) {
                session(null);
                $r = msg_handle('修改成功', 1);
            } else {
                $r = msg_handle('修改失败', 0);
            }
        }
        return $r;
    }
}