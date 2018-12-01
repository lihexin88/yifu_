<?php

namespace app\index\controller;

use app\index\model\Agent;
use app\index\model\User;
use app\index\model\UserAccount;
use think\Controller;
use think\Request;

class Register extends IndexController
{
    private $Agent;
    private $User;
    private $UserAccount;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->User = new User();
        $this->UserAccount = new UserAccount();
        $this->Agent = new Agent();
    }

    /**
     * 注册信息
     * @param $data
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index($data)
    {
        if (!reg_phone($data['phone'])) {
            $r = msg_handle('手机号码格式不正确', 0);
        } elseif (empty($data['password'])) {
            $r = msg_handle('登录密码不能为空', 0);
        } elseif (empty($data['repassword'])) {
            $r = msg_handle('确认密码不能为空', 0);
        } elseif ($data['password'] != $data['repassword']) {
            $r = msg_handle('两次密码不一致', 0);
        } elseif (!reg_password($data['repassword'])) {
            $r = msg_handle('请输入6-12位数字字母混合密码', 0);
        } elseif (empty($data['code'])) {
            $r = msg_handle('推荐码不能为空', 0);
        } elseif (!$this->User->verify_phone($data['phone'])) {
            $r = msg_handle('手机号码已占使用', 0);
        } else {
            $user = $this->User->where(array('phone' => $data['phone']))->find();
            if ($user) {
                $r = msg_handle('手机号码已占使用', 0);
                return $r;
            }
            $phone = $data['phone'];
            $code = $data['code'];
            $map_agent['code|phone'] = $code;
            $map_agent['status'] = 1;
            $reid_user = $this->User->where($map_agent)->find();
            if (!$reid_user) {
                $r = msg_handle('代理信息有误', 0);
                return $r;
            } elseif ($reid_user['is_agent'] == 0) {
                $r = msg_handle('代理推荐码有误', 0);
                return $r;
            }elseif ($reid_user['status'] == 0) {
                $r = msg_handle('该代理已被禁用,请联系客服', 0);
                return $r;
            }
            $this->User->startTrans();
            $res1 = $this->User->register($phone, $data['password'], $reid_user['id']);
            $res2 = $this->UserAccount->add_log($res1, $reid_user['id']);
            $res3 = $this->User->where(array('id' => $reid_user['id']))->setInc('push_team', 1);
            if ($res1 && $res2 && $res3) {
                $this->User->commit();
                $r = msg_handle('注册成功!', 1);
            } else {
                $this->User->rollback();
                $r = msg_handle('注册失败！', 0);
            }
        }
        return $r;
    }

    /**
     * 获取短信
     * @param $data array 信息
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function msg_code($data)
    {
        $phone = $data['phone'];
        if (empty($phone)) {
            $r = msg_handle('输入注册手机号码', 0);
        } elseif (!reg_phone($phone)) {
            $r = msg_handle('手机号码格式不正确', 0);
        } else {
            switch ($data['type']) {
                case '1'://注册验证码
                    $user = $this->User->where(array('phone' => $phone))->find();
                    break;
                case '2':
                    $user = '';
                    break;
                case '3':
                    $user = '';
                    break;
                default:
                    $user = $this->User->where(array('phone' => $phone))->find();
                    break;
            }
            if ($user) {
                $r = msg_handle('手机号码已被注册', 0);
            } else {
                $vcode = rand(100000, 999999);
                $res = register_code($vcode, $phone);
                if ($res['code'] == 0) {
                    session('vcode', $vcode);
                    session('phone', $phone);
                    $r = msg_handle('发送成功,请注意查收!', 1);
                } else {
                    $r = msg_handle('发送失败,请稍后重试!', 0);
                }
            }
        }
        return $r;
    }
}