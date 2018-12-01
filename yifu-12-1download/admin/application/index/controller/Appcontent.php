<?php

namespace app\index\controller;

use think\Request;
use app\common\model\Applog;
use app\common\model\Appset;
use app\common\model\User;
use think\Controller;
use think\Db;

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
    }

    /*
       代理商列表
      */
    public function set()
    {
        $map = "";
        $name = input('get.name/s');
        if ($name) {
            $map['username|phone'] = ['like', $name];
        }
         $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));

         $current_page = page_judge(input('get.page/d'));

         $list = $this->Appset->query_log($map, $current_page, $this->num);
        echo 1;
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

    public function log()
    {
        $map = "";
        $name = input('get.name/s');
        if ($name) {
            $user=$this->User->where(array("phone|name"=>['like',$name]))->find();
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


?>