<?php

namespace app\index\controller;

use app\index\model\User;
use think\Controller;
use think\Request;

class Index extends IndexController
{



//    模板继承
    public function base()
    {
        return $this->fetch();
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $nozzle = input('post.nozzle');
        switch ($nozzle) {
            case 'quotation_login'://行情登录
                $data = input('post.'); //phone password
                $r = $this->quotation_login($data);
                break;
            case 'login'://交易登录
                $data = input('post.'); //phone password
                $r = $this->login($data);
                break;
            case 'logout'://退出登录
                $r = $this->logout();
                break;
            case 'agree'://各种协议
                $data = input('post.'); //phone code reid ;
                $r = $this->agree($data);
                break;
            case 'ordinary_order'://普通下单
                $data = input('post.');
                $r = $this->ordinary_order($data);
                break;


            case 'recharge':
                $data = input('post.'); //phone code reid ;
                $r = $this->register($data);
                break;

            default:
                $r = msg_handle('没有传递接口名称', 0);
                break;
        }
        return json($r);
    }

    /**
     * 行情登录
     * @param $data
     * @return mixed
     */
    private function quotation_login($data)
    {
        $this->Login = new Login();
        $r = $this->Login->quotation_login($data);
        return $r;
    }

    /**
     * 交易登录
     * @param $data
     * @return array
     */
    private function login($data)
    {
        $this->Login = new Login();
        $r = $this->Login->login($data);
        return $r;
    }

    /**
     * 退出登录
     * @return array
     */
    private function logout()
    {
        $this->Login = new Login();
        return $this->Login->logout();
    }

    /**
     * 各种协议
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function agree($data)
    {
        $this->Home = new Home();
        return $this->Home->agree($data);
    }

    private function ordinary_order($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Orders = new Orders();
            $r = $this->Orders->create_order($r['data']['id'], $data,1);
        }
        return $r;
    }

    private function login1($data)
    {
        return 123;
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Member = new Member();
            $r = $this->Member->recom_code($r['data']['id']);
        }
        return $r;
    }

    /**
     * 验证 token
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function verify_token($data)
    {
        $this->User = new User();
        if (empty($data['token'])) {
            $r = msg_handle('登录超时，请重新登录', -1);
        } else {
            $user = $this->User->verify_token($data['token']);
            if (!$user) {
                $r = msg_handle('异常登录，请重新登录', -1);
            } else {
                $r = msg_handle('数据正规', 1, $user);
            }
        }
        return $r;
    }

}
