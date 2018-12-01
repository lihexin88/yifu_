<?php

namespace app\monitor\controller;

use think\Request;
use app\common\model\User;
use app\common\model\Banks;
use app\common\model\Miner;
use app\common\model\Account;
use app\common\model\Flow;
use app\common\model\Recharge;
use app\common\model\Withdraw;


class Money extends Common
{

    private $Users;
    private $Miner;
    private $Account;
    private $Recharge;
    private $Flow;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->User = new User();
        $this->Banks = new Banks();
        $this->Miner = new Miner();
        $this->Account = new Account();
        $this->Recharge = new Recharge();
        $this->Flow = new Flow();
        $this->Withdraw = new Withdraw();

    }

    /*
       客户资金
      */
    public function user_money()
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
        
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Account->query_logs($map, $current_page, $this->num);

        foreach($list["data"] as $k=>$v){
            $one_user=$this->User->where(array("id"=>$v["uid"]))->find();
            $list["data"][$k]["user"]=$one_user;
            //$list["data"][$k]["time"]=date("Y-m-d H:i:s",$v["time"]);
            if($one_user["reid"]>0){
                $real_name=$this->User->field("real_name")->where(array("id"=>$one_user["reid"]))->find();
                $list["data"][$k]["p_user"]=$real_name["real_name"];
            }else{
                $list["data"][$k]["p_user"]="暂无推荐人";
            }
        }

        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);

        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

     /*
      *出入金查询
      */
    public function find_money()
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
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Withdraw->query_logs($map, $current_page, $this->num);

        foreach($list["data"] as $k=>$v){
            $one_user=$this->User->where(array("id"=>$v["uid"]))->find();
            $list["data"][$k]["time"]=date("Y-m-d H:i:s",$v["time"]);
            if($one_user["reid"]>0){
                $real_name=$this->User->field("real_name")->where(array("id"=>$one_user["reid"]))->find();
                $list["data"][$k]["p_user"]=$real_name["real_name"];
            }else{
                $list["data"][$k]["p_user"]="暂无推荐人";
            }
            if($v["status"] == 1){
                $list["data"][$k]["status"]="已成功";
            }else if($v["status"] == 2){
                $list["data"][$k]["status"]="失败";
            }else{
                $list["data"][$k]["status"]="未审核";
            }
        }

        
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);

        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

}


?>