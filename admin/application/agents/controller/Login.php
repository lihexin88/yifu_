<?php

namespace app\agents\controller;

use think\Controller;
use think\Db;
use think\captcha\Captcha;
use think\Session;

class Login extends Controller {

    private $Agent;

    public function __construct(\think\Request $request = null) {
        parent::__construct($request);
        $config = array(
            'length' => 4,
            'codeSet' => '0123456789',
            'useCurve' => false,            // 是否画混淆曲线
            'useNoise' => false,            // 是否添加杂点
            'reset' => true,           // 验证成功后是否重置
            'bg' => [255, 255, 255],
        );
        $this->Captcha = new Captcha($config);
        $this->Agent = new \app\agents\model\Agent();
    }

    public function login() {

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
            }elseif (!$this->check_verify($data['code'])) {
                $r = msg_handle('验证码错误', 0);
            } else {
                $user = $this->Agent->where(array('num_name|cont_name|name' => $data['name'], 'password' => md5($data['password'])))->find();
                if (empty($user)) {
                    $r = msg_handle('用户名或密码错误', 0);
                } elseif ($user['status'] == 0) {
                    $r = msg_handle('账户锁定', 0);
                } else {
                    $r = msg_handle('登陆成功', 1);
                    session('ygadmin', $user);
                    session('ygadmin_id', $user['id']);
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
        Session::delete('ygadmin');
        $this->redirect('/agents/login/login');
    }

}
