<?php

namespace app\agent\controller;

use app\common\model\Relation;
use app\agent\model\Flow;
use app\agent\model\Withdrawinfo;
use app\common\model\UserBanks;
use app\common\model\Bank;
use app\agent\model\Sion;
use app\agent\model\Agent;
use app\common\model\City;
use app\agent\model\Capital;
use think\Request;

class Agentinfo extends Common
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Sion = new Sion();
        $this->Agent = new Agent();
        $this->Withdrawinfo = new Withdrawinfo();
        $this->UserBanks = new UserBanks();
        $this->Bank = new Bank();
        $this->City = new City;
        $this->Capital = new Capital();
    }

    public function agent()
    {
        $user = session('dladmin');
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['province'])) {
                $r = msg_handle('请选择开户省份', 0);
            } elseif (empty($data['city'])) {
                $r = msg_handle('请选择所属城市', 0);
            } elseif (empty($data['country'])) {
                $r = msg_handle('请选择所属县城', 0);
            } elseif (empty($data['address'])) {
                $r = msg_handle('请输入开户支行', 0);
            } elseif (empty($data['account'])) {
                $r = msg_handle('请输入开户姓名', 0);
            } elseif (empty($data['phone'])) {
                $r = msg_handle('请输入手机号', 0);
            } elseif (!preg_match(REG_PHONE, $data['phone'])) {
                $r = msg_handle('手机号格式错误', 0);
            } elseif (empty($data['number'])) {
                $r = msg_handle('请输入身份证号码', 0);
            } elseif (!preg_match(REG_CARD, $data['number'])) {
                $r = msg_handle('身份证号码格式错误', 0);
            } elseif (empty($data['card'])) {
                $r = msg_handle('请输入银行卡号', 0);
            } elseif (!preg_match(REG_BANKCARD, $data['card'])) {
                $r = msg_handle('银行卡号格式错误', 0);
            } else {
                $b = $this->UserBanks->where('agent', $user['id'])->find();
                if ($b) {
                    $r = msg_handle('银行信息已存在', 0);
                    return $r;
                }
                if ($user['bank_card'] != $data['card']) {
                    $r = msg_handle('绑定银行卡号与合同不符,请认真确认或联系客服', 0);
                    return $r;
                }
                $bankname = $this->Bank->where(['id' => $data['bank'], 'status' => 1])->find();
                $admin = [
                    'bank' => $data['bank'],
                    'name' => $bankname['name'],
                    'address' => $data['address'],
                    'province' => $data['province'],
                    'city' => $data['city'],
                    'country' => $data['country'],
                    'account' => $data['account'],
                    'card' => $data['card'],
                    'number' => $data['number'],
                    'phone' => $data['phone'],
                    'agent' => $user['id'],
                    'status' => 1,
                ];
                $r = $this->UserBanks->insert($admin);
                if ($r) {
                    $r = msg_handle('添加成功', 1);
                } else {
                    $r = msg_handle('内容无修改', 0);
                }
                return json($r);
            }
            return json($r);
        }
        $list = $this->UserBanks->where('agent', $user['id'])->find();
        $info = $this->Bank->getList();
        $where = ['id' => $list['province']];
        $province = $this->City->getLi($where);
        $admin = ['id' => $list['city']];
        $city = $this->City->getLi($admin);
        $ab = ['id' => $list['country']];
        $country = $this->City->getLi($ab);
        $province_lixt = $this->City->getListById(0);
        $city_lixt = $this->City->getCity($province['id']);
        $data = array();
        foreach ($city_lixt as $key => $value) {
            $arr['id'] = $value['id'];
            array_push($data, $arr);
        }
        $ac = array_map('current', $data);
        $country_lixt = $this->City->getCountry($ac);
        $this->assign('province_lixt', $province_lixt);
        $this->assign('city_lixt', $city_lixt);
        $this->assign('country_lixt', $country_lixt);
        $this->assign('info', $info);
        $this->assign('province', $province);
        $this->assign('city', $city);
        $this->assign('country', $country);
        $this->assign('list', $list);
        $a = Request::instance()->param('a');
        $b = Request::instance()->param('b');
        $this->assign('a', $a);
        $this->assign('b', $b);
        return $this->fetch();
    }

    //城市联动
    public function getregion()
    {
        $req = \think\Request::instance();
        if ($req->isPost()) {
            $parent_id = $req->post('parent_id');
            $list = $this->City->getListById($parent_id);
            if ($list) {
                $r['list'] = $list;
                $r['status'] = 1;
            } else {
                $r['status'] = 0;
            }
            echo json_encode($r);
            exit();
        }
    }

    //提现记录
    public function withdraw()
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
        $list = $this->Withdrawinfo->query_log($map, $current_page, $this->num);
        $number = $this->Withdrawinfo->where($map)->sum('number');
        $fee = $this->Withdrawinfo->where($map)->sum('fee');
        $this->assign('number', $number);
        $this->assign('fee', $fee);
        $this->assign('total', $number - $fee);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    //代理佣金明细
    public function sion()
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
        $list = $this->Sion->query_log($map, $current_page, $this->num);
        $ping = $this->Sion->where($map)->sum('number');
        $where = [
            'agent' => $user['id'],
            'status' => ['in', '1,2'],
        ];
        $total = $this->Capital->where($where)->sum('total');
        $profit = $this->Capital->where($where)->sum('profit_loss');
        $this->assign('total', $total);
        $this->assign('profit', $total + $profit);
        $this->assign('ping', $ping);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    //代理佣金统计
    public function sioncount()
    {
        $user = session('dladmin');
        $map['agent'] = $user['id'];
        $pid = $this->Agent->where($map)->select()->toArray();
        if (empty($pid)) {
            $m = array();
            $map = $this->query_time($m, input('get.start_query'), input('get.end_query'));
            $current_page = page_judge(input('get.page'));
            $list = $this->Agent->log($map, $current_page, $this->num);
            $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
            $this->assign('arr', $this->arr_info(input('get.')));
            $this->assign('empty', $this->null_html(12));
            $this->assign('page', $page);
            $this->assign('list', $list['data']);
            return $this->fetch();
        }
        $data = array();
        foreach ($pid as $key => $value) {
            $arr['agent'] = $value['id'];
            array_push($data, $arr);
        }
        $p = array_map('current', $data);
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Agent->query($p, $map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
}
