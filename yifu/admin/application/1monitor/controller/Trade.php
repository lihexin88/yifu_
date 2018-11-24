<?php

namespace app\agent\controller;

use think\Db;
use think\Session;

//交易统计
class Trade extends Common {

    private $User;
    private $QueryLog;
    private $TradeLog;
    private $Agent;

    public function __construct(\think\Request $request = null) {
        parent::__construct($request);
        $this->User = new \app\agent\model\User();
        $this->TradeLog = new \app\agent\model\TradeLog();
        $this->QueryLog = new \app\agent\model\QueryLog();
        $this->Agent = new \app\agent\model\Agent();
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

//当天询价
    public function index() {
        $new = $this->news();
        $start = date(mktime(0, 0, 0, date("m"), date("d"), date("Y"))); //当天开始时间
        $end = date(mktime(23, 59, 59, date("m"), date("d"), date("Y"))); //当天结束时间
        $where = [
            'uid' => ['in', $new],
            'q.status' => 0,
            'q.time' => ['between', "$start,$end"]
        ];

        $list = $this->QueryLog->getList($where);

        $this->assign('list', $list);
        return $this->fetch();
    }

//历史询价
    public function query() {
        $new = $this->news();
        $start = date(mktime(0, 0, 0, date("m"), date("d"), date("Y"))); //当天开始时间
        $where = [
            'uid' => ['in', $new],
            'q.status' => 0,
            'q.time' => ['<', "$start"]
        ];
        $list = $this->QueryLog->getList($where);
        $this->assign('list', $list);
        return $this->fetch();
    }

    //当天交易
    public function trade() {
        $new = $this->news();
        $start = date(mktime(0, 0, 0, date("m"), date("d"), date("Y"))); //当天开始时间
        $end = date(mktime(23, 59, 59, date("m"), date("d"), date("Y"))); //当天结束时间
        $where = [
            'uid' => ['in', $new],
            't.status' => 0,
            't.time' => ['between', "$start,$end"]
        ];
        $list = $this->TradeLog->getList($where);
        $this->assign('list', $list);
        return $this->fetch();
    }

    //历史交易
    public function tra() {
        $new = $this->news();
        $start = date(mktime(0, 0, 0, date("m"), date("d"), date("Y"))); //当天开始时间
        $where = [
            'uid' => ['in', $new],
            't.status' => 0,
            't.time' => ['<', "$start"]
        ];
        $list = $this->TradeLog->getList($where);
        $this->assign('list', $list);
        return $this->fetch();
    }

    //结算信息
    public function close() {
        $new = $this->news();
        $where = [
            'uid' => ['in', $new],
            't.status' => 1
        ];
        $list = $this->TradeLog->getList($where);
        $this->assign('list', $list);
        return $this->fetch();
    }

    //历史交易
    public function settle() {
        $new = $this->news();
        $where = [
            'uid' => ['in', $new],
            't.status' => 2
        ];
        $list = $this->TradeLog->getList($where);
        $this->assign('list', $list);
        return $this->fetch();
    }

}
