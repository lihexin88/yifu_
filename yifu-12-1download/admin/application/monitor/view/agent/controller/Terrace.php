<?php

namespace app\agent\controller;

use think\Session;

//平台管理
class Terrace extends Common {

    private $AgentBank;
    private $Agent;
    private $AgentWithdraw;
    private $AgentAccount;

    public function __construct(\think\Request $request = null) {
        parent::__construct($request);
        $this->AgentBank = new \app\agent\model\AgentBank();
        $this->Agent = new \app\agent\model\Agent();
        $this->AgentWithdraw = new \app\agent\model\AgentWithdraw();
        $this->AgentAccount = new \app\agent\model\AgentAccount();
    }

    //分享二维码
    public function index($url = "http://ggqq.bjfable.com/index/login/regist", $level = 3, $size = 3) {
        $se = Session::get('login');
        $phone = $se['name']; //当前用户id
        $where = ['userphone' => $phone];
        $agent = $this->Agent->getAgent($where);
        $id = $agent['id'];
        $httpurl = $_SERVER['SERVER_NAME'];
        Vendor('phpqrcode.phpqrcode');
        header('Content-Type: image/png'); //加这一句
        $errorCorrectionLevel = intval($level); //容错级别 
        $matrixPointSize = intval($size); //生成图片大小 
        //生成二维码图片 
        $object = new \QRcode();
        $value = "http://$httpurl/index/login/regist/?id=$id"; //邀请链接
        $a = ROOT_PATH . 'static' . DS . "index\img\dl.png";
        $object->png($value, $a, $errorCorrectionLevel, $matrixPointSize, 2);
        $logo = ROOT_PATH . 'static' . DS . 'index\img\log.jpg'; //准备好的logo图片   
        $QR = $a; //已经生成的原始二维码图 
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR); //二维码图片宽度  
            $QR_height = imagesy($QR); //二维码图片高度   
            $logo_width = imagesx($logo); //logo图片宽度      
            $logo_height = imagesy($logo); //logo图片高度  
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小   
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        //输出图片   
        $a = 'agent\image\\' . $id . '.png';
        $m = ROOT_PATH . 'static' . DS . $a;
        imagepng($QR, "$m");
        $image = "__ROOT__" . '/' . 'static' . '/' . $a; //邀请二维码
        $this->assign('url', $value);
        $this->assign('image', $image);
        return $this->fetch();
    }

    public function bank() {
        $se = Session::get('login');
        $phone = $se['name']; //当前用户id
        $where = ['userphone' => $phone];
        $agent = $this->Agent->getAgent($where);
        $id = ['uid' => $agent['id']];
        $list = $this->AgentBank->getById($id);
        $request = \think\Request::instance();
        if ($request->isPost()) {
            $req = $request->post();
            if (preg_match(REG_BANKCARD, $req['num']) == 0) {
                $date['status'] = 0;
                $date['msg'] = '银行卡号格式不正确';
                return json($date);
            }
            $where = ['num' => $req['num']];
            $bank = $this->AgentBank->getById($where);
            if ($bank) {
                $date['status'] = 0;
                $date['msg'] = '银行卡已存在';
                return json($date);
            }
            $admin['uid'] = $agent['id'];
            $admin['name'] = $req['name'];
            $admin['bank_name'] = $req['bank_name'];
            $admin['num'] = $req['num'];
            $admin['address'] = $req['address'];
            $admin['time'] = time();
            $res = $this->AgentBank->add($admin);
            if ($res) {
                $date['status'] = 1;
                $date['msg'] = '添加成功';
            } else {
                $date['status'] = 0;
                $date['msg'] = '添加失败';
            }
            return json($date);
        }
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function user() {
        $se = Session::get('login');
        $phone = $se['name'];
        $where = ['userphone' => $phone];
        $list = $this->Agent->getAgent($where);
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function withdraw() {
        $se = Session::get('login');
        $phone = $se['name'];
        $where = ['userphone' => $phone];
        $list = $this->Agent->getAgent($where); //代理信息
        $request = \think\Request::instance();
        if ($request->isPost()) {
            $req = $request->post();
            $fee = $this->withdraw_fee($req['number']);
            if (preg_match("/^[0-9]+(.[0-9]{1,2})?$/", $req['number']) == 0) {
                $date['status'] = 0;
                $date['msg'] = '请输入数字';
                return json($date);
            }
            if ($req['number'] + $fee > $list['account'] && $req['number'] > 2) {
                $date['status'] = 0;
                $date['msg'] = '余额不足';
                return json($date);
            }
            $where = [
                'account' => $list['account'] - $req['number'] - $fee, //余额
            ];
            $r = $this->AgentAccount->updateAccount($list['id'], $where);

            $admin['uid'] = $list['id'];
            $admin['number'] = $req['number'];
            $admin['fee'] = $fee;
            $admin['status'] = 1;
            $admin['time'] = time();
            $res = $this->AgentWithdraw->add($admin);
            if ($res) {
                $date['status'] = 1;
                $date['msg'] = '提现申请成功';
            } else {
                $date['status'] = 0;
                $date['msg'] = '提现申请失败';
            }
            return json($date);
        }
        return $this->fetch();
    }

    /**
     * 提现手续费
     * @param $num
     * @return float|int
     */
    private function withdraw_fee($num) {
        if ($num >= 2000) {
            $fee = $num * 0.01;
        } else {
            $fee = 2;
        }
        return $fee;
    }

}
