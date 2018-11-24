<?php

namespace app\agent\controller;


use app\common\model\Relation;
use app\agent\model\Flow;
use app\agent\model\Withdrawinfo;
use app\agent\model\Bank;
use app\agent\model\Sion;
use app\agent\model\Agent;
use think\Request;

class Agentinfo extends Common {

    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->Sion = new Sion();
        $this->Agent = new Agent();
        $this->Withdrawinfo = new Withdrawinfo();
        $this->Bank = new Bank();

    }
    //银行卡
    public function agent(){
        $user = session('dladmin');
        $id= $user['id'];
        $list= $this->Bank->where('uid='.$id)->find();
        if ($list) {
            $list['readonly'] = '1';
            $this->assign('list', $list);
        }else{
             $list=array('id'=>'','name'=>'','card'=>'','account'=>'','province'=>'','city'=>'','address'=>'','number'=>'','phone'=>'','readonly'=>"2");
            $this->assign('list', $list);
        }
        
        return $this->fetch();
    }
    public function edit(){
        if (request()->isAjax()) {
            $user = session('dladmin');
            $id= $user['id'];
            $data = $_POST['arr'];
            $data['time'] = time();
            $data['uid'] = $id;
            if (empty($data['name'])) {
                $r = msg_handle('银行名称不能为空', 0);
            }elseif(empty($data['card'])){
                $r = msg_handle('银行卡号不能为空', 0);
            }elseif(empty($data['account'])){
                $r = msg_handle('开户姓名不能为空', 0);
            }elseif(empty($data['province'])){
                $r = msg_handle('开户省份不能为空', 0);
            }elseif(empty($data['city'])){
                $r = msg_handle('开户城市不能为空', 0);
            }elseif(empty($data['address'])){
                $r = msg_handle('开户支行不能为空', 0);
            }elseif(empty($data['number'])){
                $r = msg_handle('开户身份证号码不能为空', 0);
            }elseif(empty($data['phone'])){
                $r = msg_handle('开户手机号不能为空', 0);
            }else{
                if (empty($data['id'])) {
                    $list = $this->Bank->insert($data);
                }else{
                    $map['name']=$data['name'];
                    $map['card'] = $data['card'];
                    $map['account'] = $data['account'];
                    $map['province'] = $data['province'];
                    $map['city'] = $data['city'];
                    $map['address'] = $data['address'];
                    $map['number'] = $data['number'];
                    $map['phone'] = $data['phone'];
                    $map['time'] = time();
                    $list = $this->Bank->where('uid='.$data['id'])->update($map);
                }
                
                if ($list) {
                    //设置成功后跳转页面的地址
                    $this->success('操作成功', 'Bank/agent');
                } else {
                    //错误页面的默认跳转页面是返回前一页，通常不需要设置
                    $this->error('操作失败,未改动数据!');
                }
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
    }
   
     //提现记录
    public function withdraw(){
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
        // print_r("<pre>");
        // print_r($list);exit; 
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
     //代理佣金明细
    public function sion(){
        $user = session('dladmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['reid'] = $user['id'];
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Sion->query_log($map, $current_page, $this->num);
        // print_r("<pre>");
        // print_r($list);exit; 
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
     //代理佣金统计
    public function sioncount(){
        $user = session('dladmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['reid'] = $user['id'];
        $list= $this->Agent->where('id='.$map['reid'])->find();
        $list['number'] = $this->Sion->where($map)->sum('number');

        // var_dump($list);exit;
        $this->assign('list', $list);
        return $this->fetch();
    }

}
