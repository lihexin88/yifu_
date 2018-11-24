<?php

namespace app\agents\controller;

use think\session;
use think\Db;

//查询统计
class Performance extends Common {

    private $User;
    private $UserAccount;
    private $Agent;
    private $CapitalFlow;
    private $AgentAccount;

    public function __construct(\think\Request $request = null) {
        parent::__construct($request);
        $this->User = new \app\agents\model\User;
        $this->UserAccount = new \app\agents\model\UserAccount();
        $this->Agent = new \app\agents\model\Agent();
        $this->CapitalFlow = new \app\agents\model\CapitalFlow();
        $this->AgentAccount = new \app\agents\model\AgentAccount();
    }

    //代理的所有下级用户信息
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

    //日报表
    public function day() {
        $new = $this->news();
        $start = date(mktime(0, 0, 0, date("m"), date("d"), date("Y"))); //当天开始时间
        $end = date(mktime(23, 59, 59, date("m"), date("d"), date("Y"))); //当天结束时间
        $where = [
            'uid' => ['in', $new],
            'r.time' => ['between', "$start,$end"]
        ];
        $list = $this->CapitalFlow->getList($where);
        $this->assign('list', $list);
        return $this->fetch();
    }

    //总报表
    public function index() {
        $new = $this->news();
        $where = [
            'uid' => ['in', $new],
        ];
        $list = $this->UserAccount->getLi($where);
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function pory() {
        $se = Session::get('login');
        $phone = ['userphone' => $se['name']];
        $agent = $this->Agent->getAgent($phone);
        $where = [
            'uid' => $agent['id'],
        ];
        $list = $this->AgentAccount->getLi($where);
        $this->assign('vo', $list);
        return $this->fetch();
    }

}
