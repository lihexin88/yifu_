<?php

namespace app\index\controller;


use app\common\model\Relation;
use app\common\model\Flow;
use app\common\model\Deal;
use app\common\model\Agentinfo;
use app\common\model\Account;
use app\common\model\AgentAcc;
use app\common\model\Agent;
use think\Request;

class Select extends Common {

    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->Deal = new Deal();
        $this->Agentinfo = new Agentinfo();
        $this->Account = new Account();
        $this->AgentAcc = new AgentAcc();
        $this->Flow = new Flow();
        $this->Agent = new Agent();

    }
    
    //运营总报表
    public function operate(){
        $map='';
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Account->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $numbe['number'] = $this->Account->where($map)->sum('total');
        $numbe['number1'] = $this->Account->where($map)->sum('wit_total');
        $numbe['number2'] = $this->Account->where($map)->sum('rec_total');
        $numbe['number3'] = $this->Account->where($map)->sum('capital_total');
        $numbe['number4'] = $this->Account->where($map)->sum('trade_total');
        $numbe['number5'] = $this->Account->where($map)->sum('fee_total');
        $numbe['number6'] = $this->Account->where($map)->sum('push_total');
        $numbe['number7'] = $this->Account->where($map)->sum('server_total');
        $numbe['number8'] = $list['total'];

        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('number', $numbe);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
    //运营总报表
    public function today_operate(){
        $map='';
        $today = strtotime(date("Y-m-d"),time());
        $map['time']=['>',$today];
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Account->query_log($map, $current_page, $this->num);
        $numbe['number'] = $this->Account->where($map)->sum('total');
        $numbe['number1'] = $this->Account->where($map)->sum('wit_total');
        $numbe['number2'] = $this->Account->where($map)->sum('rec_total');
        $numbe['number3'] = $this->Account->where($map)->sum('capital_total');
        $numbe['number4'] = $this->Account->where($map)->sum('trade_total');
        $numbe['number5'] = $this->Account->where($map)->sum('fee_total');
        $numbe['number6'] = $this->Account->where($map)->sum('push_total');
        $numbe['number7'] = $this->Account->where($map)->sum('server_total');
        $numbe['number8'] = $list['total'];
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        // var_dump($list['data']);exit();
        $this->assign('list', $list['data']);
        $this->assign('number', $numbe);
        return $this->fetch();
    }
    //员工总报表
    public function staff(){
        $map='';
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Agentinfo->query_log($map, $current_page, $this->num);
        $numbe['number'] = $this->Agentinfo->where($map)->sum('count_with');
        $numbe['number1'] = $this->Agentinfo->where($map)->sum('count_agne');
        $numbe['number2'] = $this->Agentinfo->where($map)->sum('bala');
        $numbe['number3'] = $this->Agentinfo->where($map)->sum('allo');
        $numbe['number4'] = $this->Agentinfo->where($map)->sum('bond');
        $numbe['number5'] = $this->Agentinfo->where($map)->sum('add_bond');
        $numbe['number6'] = $this->Agentinfo->where($map)->sum('tran');
        $numbe['number7'] = $this->Agentinfo->where($map)->sum('hold');
        $numbe['number8'] = $this->Agentinfo->where($map)->sum('proif');
        $numbe['number9'] = $this->Agentinfo->where($map)->sum('settle');
        $numbe['number10'] = $this->Agentinfo->where($map)->sum('proc');
        $numbe['number12'] = $this->Agentinfo->where($map)->sum('defe');
        $numbe['number11'] = $list['total'];
        // var_dump($list['data']);exit();
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('number', $numbe);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
    //员工日报表
    public function staff_today(){
        $map='';
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $today = strtotime(date("Y-m-d"),time());
        $map['time']=['>',$today];
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Agentinfo->query_log($map, $current_page, $this->num);
        $numbe['number'] = $this->Agentinfo->where($map)->sum('count_with');
        $numbe['number1'] = $this->Agentinfo->where($map)->sum('count_agne');
        $numbe['number2'] = $this->Agentinfo->where($map)->sum('bala');
        $numbe['number3'] = $this->Agentinfo->where($map)->sum('allo');
        $numbe['number4'] = $this->Agentinfo->where($map)->sum('bond');
        $numbe['number5'] = $this->Agentinfo->where($map)->sum('add_bond');
        $numbe['number6'] = $this->Agentinfo->where($map)->sum('tran');
        $numbe['number7'] = $this->Agentinfo->where($map)->sum('hold');
        $numbe['number8'] = $this->Agentinfo->where($map)->sum('proif');
        $numbe['number9'] = $this->Agentinfo->where($map)->sum('settle');
        $numbe['number10'] = $this->Agentinfo->where($map)->sum('proc');
        $numbe['number12'] = $this->Agentinfo->where($map)->sum('defe');
        $numbe['number11'] = $list['total'];
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('number', $numbe);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
    //今日成交
    public function today_deal(){
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $today = strtotime(date("Y-m-d"),time());
        $map['time']=['>',$today];
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
        $this->assign('list', $list);
        return $this->fetch();
    }
    //平仓
    public function close(){
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['status'] = 1;
        $map['type'] = 2;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Deal->query_log($map, $current_page, $this->num);
        // print_r("<pre>");
        // print_r($list);exit; 
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list);
        return $this->fetch();
    }
     //结算明细
    public function set(){
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Flow->query_log($map, $current_page, $this->num);
        // print_r("<pre>");
        // print_r($list);exit; 
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list);
        return $this->fetch();
    }
    //员工业绩表
    public function staffs(){
        $map='';
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Agentinfo->query_log($map, $current_page, $this->num);
        // var_dump($list['data']);exit();
        $numbe['number'] = $this->Agentinfo->where($map)->sum('count_with');
        $numbe['number1'] = $this->Agentinfo->where($map)->sum('count_agne');
        $numbe['number2'] = $this->Agentinfo->where($map)->sum('bala');
        $numbe['number3'] = $this->Agentinfo->where($map)->sum('allo');
        $numbe['number4'] = $this->Agentinfo->where($map)->sum('bond');
        $numbe['number5'] = $this->Agentinfo->where($map)->sum('add_bond');
        $numbe['number6'] = $this->Agentinfo->where($map)->sum('tran');
        $numbe['number7'] = $this->Agentinfo->where($map)->sum('hold');
        $numbe['number8'] = $this->Agentinfo->where($map)->sum('proif');
        $numbe['number9'] = $this->Agentinfo->where($map)->sum('settle');
        $numbe['number10'] = $this->Agentinfo->where($map)->sum('proc');
        $numbe['number12'] = $this->Agentinfo->where($map)->sum('defe');
        $numbe['number11'] = $list['total'];
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('number', $numbe);
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
    //代理业绩
    public function staff_achi(){
        $list= $this->Agent->select();
        foreach ($list as $key => $value) {
            $map['uid'] = $value['id'];
            $value['account'] = $this->AgentAcc->where($map)->sum('account');
            $value['total'] = $this->AgentAcc->where($map)->sum('total');
            $value['wit_total'] = $this->AgentAcc->where($map)->sum('wit_total');
            $value['rec_total'] = $this->AgentAcc->where($map)->sum('rec_total');
            $value['capital_total'] = $this->AgentAcc->where($map)->sum('capital_total');
            $value['trade_total'] = $this->AgentAcc->where($map)->sum('trade_total');
            $value['fee_total'] = $this->AgentAcc->where($map)->sum('fee_total');
            $value['push_total'] = $this->AgentAcc->where($map)->sum('push_total');
            $value['user_server_total'] = $this->AgentAcc->where($map)->sum('user_server_total');
            $value['agent_server_total'] = $this->AgentAcc->where($map)->sum('agent_server_total');
        }
        $number['number']=0;
        $number['number1']=0;
        $number['number2']=0;
        $number['number3']=0;
        $number['number4']=0;
        $number['number5']=0;
        $number['number6']=0;
        $number['number8']=0;
        $number['number9']=0;
        foreach ($list as $k => $v) {
            $number['number'] +=  $v['rec_total'];
            $number['number1'] +=  $v['wit_total'];
            $number['number2'] +=  $v['account'];
            $number['number3'] +=  $v['capital_total'];
            $number['number4'] +=  $v['trade_total'];
            $number['number5'] +=  $v['fee_total'];
            $number['number6'] +=  $v['push_total'];
            $number['number8'] +=  $v['user_server_total'];
            $number['number9'] +=  $v['agent_server_total'];
        }
        $number['total'] = $this->Agent->count();
        $this->assign('number', $number);
        $this->assign('list', $list);
        return $this->fetch();
    }
}
