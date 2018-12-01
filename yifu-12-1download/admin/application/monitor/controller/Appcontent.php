<?php

namespace app\monitor\controller;

use think\Request;
use app\common\model\Applog;
use app\common\model\Appset;
use app\common\model\User;
use app\common\model\Userorders;
use app\common\model\LoginLog;
use app\common\model\Closed;
use app\common\model\Userdeal;
use app\common\model\Variety;
use app\common\model\Contract;
use app\common\model\Exchange;
use app\common\model\Config;
use think\Controller;
use think\Db;
use think\Session;

class Appcontent extends Common
{
    private $Applog;
    private $Appset;
    private $Users;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Applog = new Applog();
        $this->Appset = new Appset();
        $this->User = new User();
        $this->Userdeal = new Userdeal();
        $this->Userorders = new Userorders();
        $this->LoginLog = new LoginLog();
        $this->Exchange = new Exchange();
        $this->Contract = new Contract();
        $this->Variety = new Variety();
        $this->Closed = new Closed();
        $this->Config = new Config();
        
    }

    /*
       代理商列表
      */
    public function set()
    {
        $map = "";
        $name = input('get.name');
        $admin_id = session('admin_id');
        if ($name) {
            $user=$this->User->where(array("phone|real_name"=>['like',$name]))->find();
            if($user && $user["agent"] == $admin_id){
                $map['uid'] = ['like', $user["id"]];
            }else{
                $map['uid'] = ['like', 0];
            }
        }
         $map["status"]=0;
         $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));


         $current_page = page_judge(input('get.page/d'));
         $list = $this->Userorders->query_logs($map, $current_page, $this->num);
         foreach($list["data"] as $k=>$v){
            $list["data"][$k]["user"]=$this->User->where(array("id"=>$v["uid"]))->find();

            $one_contract=$this->Contract->where(array("code"=>$v["code"]))->find();
            $list["data"][$k]["contract"]=$one_contract;

            $list["data"][$k]["time"]=date("Y-m-d H:i:s",$v["time"]);
            if($one_contract["currency_type"] == 1){
                $list["data"][$k]["currency_type"]="美金";
            }else if($one_contract["currency_type"] == 2){
                $list["data"][$k]["currency_type"]="港币";
            }else if($one_contract["currency_type"] == 3){
                $list["data"][$k]["currency_type"]="欧元";
            }else{
                $list["data"][$k]["currency_type"]="人民币";
            }

            if($v["status"] == 1){
                $list["data"][$k]["status"]="已撤单";
            }else if($v["status"] == 2){
                $list["data"][$k]["status"]="已完成";
            }else{
                $list["data"][$k]["status"]="未成交";
            }

            if($v["direction"] == 1){
                $list["data"][$k]["direction"]="买";
            }else{
                $list["data"][$k]["direction"]="卖";
            }

            if($v["type"] == 1){
                $list["data"][$k]["type"]="平仓";
            }else{
                $list["data"][$k]["type"]="开仓";
            }
        }
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);

        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    public function log()
    {
        $map = "";
        $name = input('get.name');
        $admin_id = session('admin_id');
        if ($name) {
            $user=$this->User->where(array("phone|real_name"=>['like',$name]))->find();
            if($user && $user["agent"] == $admin_id){
                $map['uid'] = ['like', $user["id"]];
            }else{
                $map['uid'] = ['like', 0];
            }
        }
        $map['number'] = ['>', 0];
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
         

         $current_page = page_judge(input('get.page/d'));
         $list = $this->Userdeal->query_logs($map, $current_page, $this->num);
         foreach($list["data"] as $k=>$v){
            $list["data"][$k]["user"]=$this->User->where(array("id"=>$v["uid"]))->find();
            $list["data"][$k]["contract"]=$this->Contract->where(array("id"=>$v["contract_id"]))->find();
        }
         $page = page_handling($list['num'], $current_page, $this->show, $list['total']);

        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('name', $name);
        $this->assign('start_query', input('get.start_query'));
        $this->assign('end_query', input('get.end_query'));
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    public function login_log(){
        $map = "";
        $name = input('get.name');
        $admin_id = session('admin_id');
        if ($name) {
            $user=$this->User->where(array("phone|real_name"=>['like',$name]))->find();
            if($user && $user["agent"] == $admin_id){
                $map['uid'] = ['like', $user["id"]];
            }else{
                $map['uid'] = ['like', 0];
            }
        }
         $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
         

         $current_page = page_judge(input('get.page/d'));
         $list = $this->LoginLog->query_logs($map, $current_page, $this->num);
         foreach($list["data"] as $k=>$v){
            $list["data"][$k]["user"]=$this->User->where(array("id"=>$v["uid"]))->find();
            $list["data"][$k]["time"]=date("T-m-d H:i:s",$v["time"]);
            if($v["type"] == 1){
                $list["data"][$k]["type"]="WEB端";
            }else{
                $list["data"][$k]["type"]="App端";
            }
        }
         $page = page_handling($list['num'], $current_page, $this->show, $list['total']);

        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('name', $name);
        $this->assign('start_query', input('get.start_query'));
        $this->assign('end_query', input('get.end_query'));
        $this->assign('list', $list['data']);
        return $this->fetch();
    }


}


?>