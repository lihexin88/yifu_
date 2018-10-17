<?php

namespace app\agent\controller;


use app\common\model\Relation;
use app\agent\model\Flow;
use app\agent\model\Deal;
use app\agent\model\Depot;
use think\Request;

class Count extends Common {

    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->Deal = new Deal();
        $this->Depot = new Depot();
        $this->Flow = new Flow();

    }
    //持仓
    public function depot(){
        $user = session('dladmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['uid'] = $user['id'];
        $map['status'] = 0;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Depot->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        // print_r("<pre>");
        // print_r($list);exit; 
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
    //历史成交
    public function deal(){
        $user = session('dladmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['uid'] = $user['id'];
        $map['status'] = 1;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Deal->query_log($map, $current_page, $this->num);
        // print_r("<pre>");
        // print_r($last);exit; 
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
    //今日成交
    public function today_deal(){
        $user = session('dladmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $today = strtotime(date("Y-m-d"),time());
        $map['time']=['>',$today];
        $map['uid'] = $user['id'];
        $map['status'] = 1;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Deal->query_log($map, $current_page, $this->num);
        // print_r("<pre>");
        // print_r($list);exit; 
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
    //平仓
    public function close(){
        $user = session('dladmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['uid'] = $user['id'];
        $map['status'] = 1;
        $map['type'] = 2;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Deal->query_log($map, $current_page, $this->num);
        // print_r("<pre>");
        // print_r($list);exit; 
        // var_dump($list);exit;
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
     //结算明细
    public function set(){
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
        // print_r("<pre>");
        // print_r($list);exit; 
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

}
