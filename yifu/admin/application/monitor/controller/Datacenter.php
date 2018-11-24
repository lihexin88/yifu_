<?php

namespace app\monitor\controller;

use think\Request;
use app\common\model\Applog;
use app\common\model\Appset;
use app\common\model\User;
use app\common\model\Userorders;
use app\common\model\UserAccount;
use app\common\model\Userdeal;
use app\common\model\Closed;
use app\common\model\Flow;
use app\common\model\Variety;
use app\common\model\Contract;
use app\common\model\Exchange;
use app\common\model\Config;
use think\Controller;
use think\Db;
use think\Session;

class Datacenter extends Common {

    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->Applog = new Applog();
        $this->Appset = new Appset();
        $this->User = new User();
        $this->Flow = new Flow();
        $this->Exchange = new Exchange();
        $this->Contract = new Contract();
        $this->Variety = new Variety();
        $this->Closed = new Closed();
        $this->Config = new Config();
        $this->Userorders = new Userorders();
        $this->UserAccount = new UserAccount();
        $this->Userdeal = new Userdeal();
    }

    public function user_data(){
        $map = "";
        $name = input('get.name/s');
        $id = session('admin_id');
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
    
    public function warehouse(){//仓单查询
        $map = "";
        $name = input('get.name/s');
        $id = session('admin_id');
        if ($name) {
            $user=$this->User->where(array("phone|real_name"=>['like',$name]))->find();
            if($user && $user["agent"] == $admin_id){
                $map['uid'] = ['like', $user["id"]];
            }else{
                $map['uid'] = ['like', 0];
            }
        }
         $map['type'] = 0;
         $map['status'] = 2;
         $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
         

         $current_page = page_judge(input('get.page/d'));


         $list = $this->Userorders->query_logs($map, $current_page, $this->num);
         foreach($list["data"] as $k=>$v){
            $one_user=$this->User->where(array("id"=>$v["uid"]))->find();
            $list["data"][$k]["user"]=$one_user;
            if($one_user["reid"]>0){
                $real_name=$this->User->field("real_name")->where(array("id"=>$one_user["reid"]))->find();
                $list["data"][$k]["p_user"]=$real_name["real_name"];
            }else{
                $list["data"][$k]["p_user"]["real_name"]="暂无推荐人";
            }

            $one_contract=$this->Contract->where(array("code"=>$v["code"]))->find();
            $list["data"][$k]["contract"]=$one_contract;

            $list["data"][$k]["time"]=date("Y-m-d H:i:s",$v["time"]);
            $list["data"][$k]["buy_time"]=date("Y-m-d H:i:s",$v["buy_time"]);
            if($one_contract["currency_type"] == 1){
                $list["data"][$k]["currency_type"]="美金";
            }else if($one_contract["currency_type"] == 2){
                $list["data"][$k]["currency_type"]="港币";
            }else if($one_contract["currency_type"] == 3){
                $list["data"][$k]["currency_type"]="欧元";
            }else{
                $list["data"][$k]["currency_type"]="人民币";
            }

            if($v["direction"] == 1){
                $list["data"][$k]["direction"]="买";
            }else{
                $list["data"][$k]["direction"]="卖";
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
    
    public function position(){//持仓查询
        $map = "";
        $name = input('get.name/s');
        $id = session('admin_id');
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

    public function close_out(){//平仓查询
        $map = "";
        $name = input('get.name/s');
        $id = session('admin_id');
        if ($name) {
            $user=$this->User->where(array("phone|real_name"=>['like',$name]))->find();
            if($user && $user["agent"] == $admin_id){
                $map['uid'] = ['like', $user["id"]];
            }else{
                $map['uid'] = ['like', 0];
            }
        }
        $map["type"]=1;
        $map["status"]=2;
         $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
         

         $current_page = page_judge(input('get.page/d'));
         $list = $this->Userorders->query_logs($map, $current_page, $this->num);
         foreach($list["data"] as $k=>$v){
            $list["data"][$k]["user"]=$this->User->where(array("id"=>$v["uid"]))->find();
            $list["data"][$k]["buy_time"]=date("Y-m-d H:i:s",$v["buy_time"]);
            $list["data"][$k]["sell_time"]=date("Y-m-d H:i:s",$v["sell_time"]);
            $list["data"][$k]["split_order_sn"]=$this->Userorders->field("order_sn")->where(array("id"=>$v["split_order_sn"]))->find();
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

    public function transaction_deal(){//交易明细
        $map = "";
        $name = input('get.name/s');
        $id = session('admin_id');
        if ($name) {
            $user=$this->User->where(array("phone|real_name"=>['like',$name]))->find();
            if($user && $user["agent"] == $admin_id){
                $map['uid'] = ['like', $user["id"]];
            }else{
                $map['uid'] = ['like', 0];
            }
        }
        $map["status"]=[">=",0];
         $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
         
         $current_page = page_judge(input('get.page/d'));
         $list = $this->Userorders->query_logs($map, $current_page, $this->num);
         foreach($list["data"] as $k=>$v){
            $list["data"][$k]["user"]=$this->User->where(array("id"=>$v["uid"]))->find();

            if($v["status"] == 1){
                $list["data"][$k]["status"]="已撤单";
            }else if($v["status"] == 2){
                $list["data"][$k]["status"]="订单已完成";
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
                $list["data"][$k]["order_price"]=$v["sell_price"];
                $list["data"][$k]["synthesize_money"]=$v["open_synthesize_money"];
                $list["data"][$k]["new_time"]=date("Y-m-d H:i:s",$v["buy_time"]);
            }else{
                $list["data"][$k]["type"]="开仓";
                $list["data"][$k]["order_price"]=$v["buy_price"];
                $list["data"][$k]["synthesize_money"]=$v["close_synthesize_money"];
                $list["data"][$k]["new_time"]=date("Y-m-d H:i:s",$v["sell_time"]);
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

    public function capital(){//客户资金流水
        $map = "";
        $name = input('get.name/s');
        $id = session('admin_id');
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
         $list = $this->Flow->query_logs($map, $current_page, $this->num);
         foreach($list["data"] as $k=>$v){
            $list["data"][$k]["user"]=$this->User->where(array("id"=>$v["uid"]))->find();
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

    public function agency(){//代理报表
        $map = "";
        $name = input('get.name/s');
        if ($name) {
            $user=$this->User->where(array("phone|real_name"=>['like',$name]))->find();
            if($user){
                $map['uid'] = ['like', $user["id"]];
            }else{
                $map['uid'] = ['like', 0];
            }
        }
         $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
         

         $current_page = page_judge(input('get.page/d'));
         $list = $this->Applog->query_log($map, $current_page, $this->num);
         foreach($list["data"] as $k=>$v){
            $list["data"][$k]["user"]=$this->User->where(array("id"=>$v["uid"]))->find();
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
