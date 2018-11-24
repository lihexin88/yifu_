<?php

namespace app\index\controller;

use app\common\model\Admin;
use think\captcha\Captcha;
use think\Controller;
use think\Request;
use app\common\model\Relation;

class Index extends Controller
{
    private $Admin;
    private $Captcha;
    private $Relation;
    public function __construct(Request $request = null)
    {
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
        $this->Admin = new Admin();
        $this->Relation = new Relation();
    }

    public function index()
    {
        /*session('admin', null);
        session('admin_id', null);*/
        return $this->fetch();
    }

    public function login()
    {
//        elseif (!$this->check_verify($data['code'])) {
//        $r = msg_handle('验证码错误', 0);
//        }
        if (request()->isAjax()) {
            $data = $_POST;
            if (empty($data['name'])) {
                $r = msg_handle('请输入登录用户名', 0);
            } elseif (empty($data['password'])) {
                $r = msg_handle('请输入登录密码', 0);
            } elseif (empty($data['code'])) {
                $r = msg_handle('请输入验证码', 0);
            } else {
                $user = $this->Admin->where(array('name' => $data['name'], 'password' => md5($data['password'])))->find();
                if (empty($user)) {
                    $r = msg_handle('用户名或密码错误', 0);
                } else if($user['status'] == 0){
                    $r = msg_handle('该账号已被禁用',0);
                }else {
                    $r = msg_handle('登录成功', 1);
                    $map['roid']=$user['ro_id'];
                    $left=$this->Relation->query_all($map);
                    session('left',$left);
                    session('admin', $user);
                    session('admin_id', $user['id']);
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
    private function check_verify($code)
    {
        return $this->Captcha->check($code);
    }

    public function self_verify()
    {
        ob_clean();
        return $this->Captcha->entry();
    }
}
