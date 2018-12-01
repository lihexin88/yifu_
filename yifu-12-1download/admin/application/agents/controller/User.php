<?php

namespace app\agents\controller;

use think\Session;
use think\Db;

class User extends Common {

    private $User;
    private $Withdraw;
    private $Recharge;
    private $CapitalFlow;
    private $Agent;

    public function __construct(\think\Request $request = null) {
        parent::__construct($request);
        $this->Withdraw = new \app\agents\model\Withdraw();
        $this->User = new \app\agents\model\User();
        $this->Recharge = new \app\agents\model\Recharge();
        $this->CapitalFlow = new \app\agents\model\CapitalFlow();
        $this->Agent = new \app\agents\model\Agent();
    }

    public function news() {
        $se = Session::get('login');
        $phone = ['userphone' => $se['name']];
        $agent = $this->Agent->getAgent($phone);
        $where = ['level' => $agent['id']];
        $user = $this->User->getUser($where);
        $new = [];
        foreach ($user as $v) {
            $new[] = $v['id'];
        }
        return $new;
    }

//提现信息
    public function withdraw() {
        $new = $this->news();
        $where = [
            'uid' => ['in', $new],
            'w.status' => 5
        ];
        $list = $this->Withdraw->getList($where);
        $this->assign('list', $list);
        return $this->fetch();
    }

    //充值信息
    public function recharge() {
        $new = $this->news();
        $where = [
            'uid' => ['in', $new],
        ];
        $list = $this->Recharge->getList($where);
        $this->assign('list', $list);
        return $this->fetch();
    }

    //流水信息
    public function capital() {
        $new = $this->news();
        $where = [
            'uid' => ['in', $new],
        ];
        $list = $this->CapitalFlow->getList($where);
        $this->assign('list', $list);
        return $this->fetch();
    }

    //员工信息
    public function user() {
        $se = Session::get('login');
        $phone = ['userphone' => $se['name']];
        $agent = $this->Agent->getAgent($phone);
        $where = ['level' => $agent['id']];
        $list = $this->User->count($where);
        $this->assign('list', $list);
        return $this->fetch();
    }

}
