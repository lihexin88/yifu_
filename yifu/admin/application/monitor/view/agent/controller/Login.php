<?php

namespace app\agent\controller;

use think\Controller;
use app\common\model\Agent;
use think\Db;
use think\Session;

class Login extends Controller {

    private $Agent;

    public function __construct(\think\Request $request = null) {
        parent::__construct($request);
        $this->Agent = new Agent();
    }

    public function login() {
        //加载模板
        return $this->fetch();
    }

    public function dologin() {
        /* elseif (!$this->check_verify($data['code'])) {
          $r = msg_handle('验证码错误', 0);
          } */
        if (request()->isAjax()) {
            $data = $_POST;
            if (empty($data['name'])) {
                $r = msg_handle('请输入登录用户名', 0);
            } elseif (empty($data['password'])) {
                $r = msg_handle('请输入登录密码', 0);
            } elseif (empty($data['code'])) {
                $r = msg_handle('请输入验证码', 0);
            } else {
                $user = $this->Agent->where(array('name' => $data['name'], 'password' => md5($data['password'])))->find();
                if (empty($user)) {
                    $r = msg_handle('用户名或密码错误', 0);
                } elseif ($user['status'] == 0) {
                    $r = msg_handle('账户锁定', 0);
                } else {
                    $r = msg_handle('登陆成功', 1);
                    session('dladmin', $user);
                    session('dladmin_id', $user['id']);
                }
            }
        } else {
            return 32;
            $r = msg_handle('登陆失败', 0);
        }
        return json($r);
    }

    /**
     * 验证码验证
     * @param $code
     * @return bool
     */
    private function check_verify($code) {
        return $this->Captcha->check($code);
    }

    public function self_verify() {
        ob_clean();
        return $this->Captcha->entry();
    }

    //退出
    public function loginout() {
        Session::delete('dladmin');
        $this->redirect('/agent/login/login');
    }

}
