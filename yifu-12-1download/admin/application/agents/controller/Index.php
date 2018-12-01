<?php

namespace app\agents\controller;


use app\common\model\Relation;
use app\agents\model\Agent;
use app\agents\model\Flow;
use app\agents\model\User;
use app\agents\model\UserAccount;
use app\agents\model\Recharge;
use app\agents\model\Withdrawinfo;
use app\agents\model\Capital;
use app\agents\model\StaffAccount;
use think\Request;

class Index extends Common {

    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->Agent = new Agent();
        $this->Flow = new Flow();
        $this->User = new User();
        $this->UserAccount = new UserAccount();
        $this->Recharge = new Recharge();
        $this->Withdrawinfo = new Withdrawinfo();
        $this->Capital = new Capital();
        $this->StaffAccount = new StaffAccount();

        $user = session('ygadmin');
        $map['roid'] =$user['ro_id'];
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

    public function index() {
        /**/

        $url = "";
        $level = 3;
        $size = 3;
        $name = $_SERVER['SERVER_NAME'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $user = session('ygadmin');
        $id = $user['id'];
        /**
         * 邀请二维码
         * @return mixed
         */
        $path = config('HOST');
        $httpurl = 'http://' . $_SERVER['SERVER_NAME'];
        vendor('phpqrcode.phpqrcode');
        $url = $path . '/Register/agents_register?staff_id=' . $id; //网址或者是文本内容
        $errorCorrectionLevel = 'L';
        $matrixPointSize = 8;
        $object = new \QRcode();
        $address = 'qrcode1/' . $id . '.jpg';
        $object->png($url, $address, $errorCorrectionLevel, $matrixPointSize, 2);
        $img_path = $httpurl . '/' . $address; //二维码地址

        $recom_path = $url; //注册地址
            // dump($recom_path);
                // dump($img_path);die();
        $this->assign('img', $img_path);
        $this->assign('recom', $recom_path);
        /**/
        $this->assign('name', $name);
        $this->assign('ip', $ip);
        $this->assign('user', $user);
        return $this->fetch();
    }
        //今日数据
    public function today() {
        $user = session('ygadmin');
        $today = strtotime(date("Y-m-d"), time());
        $ma['time'] = ['>', $today];
        $ma['staff'] = $user['id'];

        $list = $this->User->where($ma)->count(); //注册人数
        $number = $this->Recharge->where($ma)->sum('number'); //充值金额
        $num = $this->Withdrawinfo->where($ma)->sum('number'); //提现金额
        $account = $this->StaffAccount->where('uid', $user['id'])->find(); //余额
        $capital = $this->Capital->where($ma)->sum('total'); //配资     
        $bond = $this->Capital->where($ma)->sum('bond'); //保证金
        $bonds = $this->Capital->where($ma)->sum('increase_bond'); //追加保证金

        $this->assign('account', $account);
        $this->assign('list', $list);
        $this->assign('number', $number);
        $this->assign('num', $num);
        $this->assign('capital', $capital);
        $this->assign('bond', $bond);
        $this->assign('bonds', $bonds);
        return $this->fetch();
    }
    
    public function user_agent(){
        $user = session('ygadmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['staff'] = $user['id'];
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->User->query_log($map, $current_page, $this->num);
        $account = $this->UserAccount->where('staff', $user['id'])->sum('account');
        $total = $this->UserAccount->where('staff', $user['id'])->sum('total');
        $this->assign('account', $account);
        $this->assign('total', $total);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
    public function flow(){
        $user = session('ygadmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['staff'] = $user['id'];
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Flow->query_log($map, $current_page, $this->num);
        $number = $this->Flow->where(['mold' => 0, 'staff' => $user['id']])->sum('number');
        $money = $this->Flow->where(['mold' => 1, 'staff' => $user['id']])->sum('number');
        $this->assign('number', $number);
        $this->assign('money', $money);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
}
