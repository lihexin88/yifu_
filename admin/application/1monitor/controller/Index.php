<?php

namespace app\agent\controller;

use app\common\model\Relation;
use app\agent\model\Agent;
use app\agent\model\AgentAccount;
use app\agent\model\Withdraw;
use app\agent\model\Flow;
use app\agent\model\Capital;
use app\agent\model\Bond;
use app\agent\model\Bonds;
use app\agent\model\Recharge;
use app\agent\model\User;
use app\agent\model\UserAccount;
use think\Request;

class Index extends Common
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Agent = new Agent();
        $this->AgentAccount = new AgentAccount();
        $this->Flow = new Flow();
        $this->Capital = new Capital();
        $this->Recharge = new Recharge();
        $this->Bond = new Bond();
        $this->Bonds = new Bonds();
        $this->Withdraw = new Withdraw();
        $this->User = new User();
        $this->UserAccount = new UserAccount();
        $user = session('dladmin');
        $map['roid'] = $user['ro_id'];
        $map['roid'] = 8;
        $left = $this->Relation->query($map);
        $this->assign('left', $left);
        $this->assign('user', $user);
    }

    /*首页   管理中心
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        echo 234;exit;
        /**/
        $url = "";
        $level = 3;
        $size = 3;
        $name = $_SERVER['SERVER_NAME'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $user = session('dladmin');
        $id = $user['id'];
//        $value = "http://$httpurl/agent/login/login/?id=$id"; //邀请链接
        /**
         * 邀请二维码
         * @return mixed
         */
        $path = config('HOST');
        $httpurl = 'http://' . $_SERVER['SERVER_NAME'];
        vendor('phpqrcode.phpqrcode');
        $url = $path . '/Register/agent_register?agent_id=' . $id;//网址或者是文本内容
        $errorCorrectionLevel = 'L';
        $matrixPointSize = 8;
        $object = new \QRcode();
        $address = 'qrcode2/' . $id . '.jpg';
        $object->png($url, $address, $errorCorrectionLevel, $matrixPointSize, 2);
        $img_path = $httpurl . '/' . $address;//二维码地址
        $recom_path = $url;//注册地址
        $this->assign('img', $img_path);
        $this->assign('recom', $recom_path);
        /**/
        $this->assign('name', $name);
        $this->assign('ip', $ip);
        $this->assign('user', $user);
        return $this->fetch();
    }

    //今日数据
    public function today()
    {
        $user = session('dladmin');
        $map['uid'] = $user['id'];
        $account = $this->AgentAccount->where($map)->find();//余额
        $today = strtotime(date("Y-m-d"), time());
        $data['time'] = ['>', $today];
        $list = $this->Agent->where($data)->count();//注册人数
        $today = strtotime(date("Y-m-d"), time());
        $ma['agent'] = $user['id'];
        $ma['time'] = ['>', $today];
        $number = $this->Recharge->where($ma)->sum('number');//充值金额
        $num = $this->Withdraw->where($ma)->sum('number');//提现金额
        $capital = $this->Capital->where($ma)->sum('number');//配资
        $ma['type'] = 2;
        $bond = $this->Bond->where($ma)->sum('number');//保证金
        $mas['type'] = 5;
        $today = strtotime(date("Y-m-d"), time());
        $mas['agent'] = $user['id'];
        $mas['time'] = ['>', $today];
        $bonds = $this->Bonds->where($mas)->sum('number');//追加保证金
        $this->assign('account', $account);
        $this->assign('list', $list);
        $this->assign('number', $number);
        $this->assign('num', $num);
        $this->assign('capital', $capital);
        $this->assign('bond', $bond);
        $this->assign('bonds', $bonds);
        return $this->fetch();
    }

    public function user_agent()
    {
        $user = session('dladmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['agent'] = $user['id'];
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->User->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $account = $this->UserAccount->where('agent', $user['id'])->sum('account');
        $total = $this->UserAccount->where('agent', $user['id'])->sum('total');
        $this->assign('account', $account);
        $this->assign('total', $total);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    public function flow()
    {
        $user = session('dladmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['agent'] = $user['id'];
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Flow->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $number = $this->Flow->where(['mold' => 0, 'agent' => $user['id']])->sum('number');
        $money = $this->Flow->where(['mold' => 1, 'agent' => $user['id']])->sum('number');
        $this->assign('number', $number);
        $this->assign('money', $money);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
}
