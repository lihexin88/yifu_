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
use app\common\model\Account;
use think\Controller;
use think\Db;
use think\Session;

class Pneumatic extends Common
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
        $this->Account = new Account();
        
    }

    /*
       持仓监控
      */
    public function position()
    {
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

    /*
    用户申请充值
     */
    public function user_apply()
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
    /*
    用户提现处理
     */
    public function withdraw(){
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

    /*
    在线监控
     */
    public function on_line(){
        $map = "";
        $name = input('get.name');
        $admin_id = session('admin_id');
        $map["time"]=['>=',time()-1800];
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
            $news_time=time()-$v["time"];
            $list["data"][$k]["news_time"]=ceil($news_time/60);
            $list["data"][$k]["time"]=date("Y-m-d H:i:s",$v["time"]);
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


    /*
    资金监控
     */
    public function money(){
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


}


?>