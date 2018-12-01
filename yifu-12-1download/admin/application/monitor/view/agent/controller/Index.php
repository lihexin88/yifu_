<?php

namespace app\agent\controller;


use app\common\model\Relation;
use app\agent\model\Agent;
use app\agent\model\Flow;
use think\Request;

class Index extends Common {

    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->Agent = new Agent();
        $this->Flow = new Flow();

        $user = session('dladmin');
        $map['roid'] =$user['ro_id'];
        $map['roid'] = 8;
//        $map['roid'] = 5;
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
       $url = "";
       $level = 3;
        $size = 3;
        $name = $_SERVER['SERVER_NAME'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $user = session('dladmin');
        $id = $user['id'];
        $httpurl = $_SERVER['SERVER_NAME'];
        Vendor('phpqrcode.phpqrcode');
        header('Content-Type: image/png'); //加这一句
        $errorCorrectionLevel = intval($level); //容错级别 
        $matrixPointSize = intval($size); //生成图片大小 
        //生成二维码图片 
        $object = new \QRcode();
        $value = "http://$httpurl/index/login/regist/?id=$id"; //邀请链接
        $a = ROOT_PATH . 'public\static' . DS . "img\dl.png";
        $object->png($value, $a, $errorCorrectionLevel, $matrixPointSize, 2);
        $logo = ROOT_PATH . 'public\static' . DS . 'img\logo.png'; //准备好的logo图片   
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
        $a = '\image\\' . $id . '.png';
        $m = ROOT_PATH . 'public\static' . DS . $a;
        imagepng($QR, "$m");
        $c = 'image/' . $id . '.png';
        $image = '/static' . '/' . $c; //邀请二维码
        $this->assign('url', $value);
        $this->assign('image', $image);
        $this->assign('name', $name);
        $this->assign('ip', $ip);
        $this->assign('user', $user);
        return $this->fetch();
    }
    public function today(){
        return $this->fetch();
    }
    public function user_agent(){
        $user = session('dladmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['agent'] = $user['id'];
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Agent->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        // print_r("<pre>");
        // print_r($list);exit; 
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
    public function flow(){
        $user = session('dladmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['reid'] = $user['id'];
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Flow->query_log($map, $current_page, $this->num);
        /*print_r("<pre>");
        print_r($list);
        print_r("</pre>");exit; 
        var_dump($list);exit;*/
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
}
