<?php

namespace app\monitor\controller;
use think\Request;
use app\common\model\UserBanks;
use app\common\model\Bank;
use app\common\model\City;

class UserBank extends Common {

    private $UserBanks;
    private $Bank;
    private $City;

    public function __construct(\think\Request $request = null) {
        parent::__construct($request);
        $this->UserBanks = new UserBanks;
        $this->Bank = new Bank;
        $this->City = new City;
    }

    public function index() {
        $map = array();
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->UserBanks->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    public function edit() {
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
                ];
                $r = $this->UserBanks->where('uid', $data['id'])->update($admin);
                if ($r) {
                    $r = msg_handle('修改成功', 1);
                } else {
                    $r = msg_handle('内容无修改', 0);
                }
                return json($r);
            }
            return json($r);
        }
        $id = Request::instance()->param('id');
        $list = $this->UserBanks->getOne($id);
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
        $a=Request::instance()->param('a');
        $b=Request::instance()->param('b');
        /*dump($a);
        dump($b);
        dump($id);
        exit;*/
        $this->assign('a', $a);
        $this->assign('b', $b);
        return $this->fetch();
    }

    //城市联动
    public function getregion() {

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

}
