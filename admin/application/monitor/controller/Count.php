<?php

namespace app\monitor\controller;


use app\common\model\Relation;
use app\common\model\Flow;
use app\common\model\Deal;
use app\common\model\Plat;
use app\common\model\Depot;
use think\Request;

class Count extends Common {

    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->Deal = new Deal();
        $this->Depot = new Depot();
        $this->Plat = new Plat();
        $this->Flow = new Flow();

    }
    //持仓
    public function depot(){
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map_agent['name|phone'] = $name;
        }
        $map['status'] = 0;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Depot->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $number=$this->Depot->where($map)->sum("number");
        $num=$this->Depot->where($map)->sum("num");
        $increase_bond=$this->Depot->where($map)->sum("increase_bond");
        $this->assign('number', $number);  
        $this->assign('num', $num);
        $this->assign('increase_bond', $increase_bond);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
    //历史成交
    public function deal(){
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['status'] = 1;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Deal->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $total=$this->Deal->where($map)->sum("total");
        $number=$this->Deal->where($map)->sum("number");
        $bond=$this->Deal->where($map)->sum("bond");
        $ratio=$this->Deal->where($map)->sum("ratio");
        $fee=$this->Deal->where($map)->sum("fee");
        $extra=$this->Deal->where($map)->sum("extra");
        $trade_fee=$this->Deal->where($map)->sum("trade_fee");
        $sell_fee=$this->Deal->where($map)->sum("sell_fee");
        $buy_fee=$this->Deal->where($map)->sum("buy_fee");
        $increase_capital=$this->Deal->where($map)->sum("increase_capital");
        $increase_bond=$this->Deal->where($map)->sum("increase_bond");
        $profit_loss=$this->Deal->where($map)->sum("profit_loss");
        $num=$this->Deal->where($map)->sum("num");
        $this->assign('total', $total);  
        $this->assign('number', $number);  
        $this->assign('bond', $bond);  
        $this->assign('ratio', $ratio);  
        $this->assign('fee', $fee);  
        $this->assign('extra', $extra);  
        $this->assign('trade_fee', $trade_fee); 
        $this->assign('sell_fee', $sell_fee);  
        $this->assign('buy_fee', $buy_fee);  
        $this->assign('increase_capital', $increase_capital);                        
        $this->assign('increase_bond', $increase_bond);  
        $this->assign('profit_loss', $profit_loss);  
        $this->assign('num', $num); 
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
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
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $total=$this->Deal->where($map)->sum("total");
        $number=$this->Deal->where($map)->sum("number");
        $bond=$this->Deal->where($map)->sum("bond");
        $ratio=$this->Deal->where($map)->sum("ratio");
        $fee=$this->Deal->where($map)->sum("fee");
        $extra=$this->Deal->where($map)->sum("extra");
        $trade_fee=$this->Deal->where($map)->sum("trade_fee");
        $sell_fee=$this->Deal->where($map)->sum("sell_fee");
        $buy_fee=$this->Deal->where($map)->sum("buy_fee");
        $increase_capital=$this->Deal->where($map)->sum("increase_capital");
        $increase_bond=$this->Deal->where($map)->sum("increase_bond");
        $profit_loss=$this->Deal->where($map)->sum("profit_loss");
        $num=$this->Deal->where($map)->sum("num");
        $this->assign('total', $total);  
        $this->assign('number', $number);  
        $this->assign('bond', $bond);  
        $this->assign('ratio', $ratio);  
        $this->assign('fee', $fee);  
        $this->assign('extra', $extra);  
        $this->assign('trade_fee', $trade_fee);                          
        $this->assign('sell_fee', $sell_fee);  
        $this->assign('buy_fee', $buy_fee);  
        $this->assign('increase_capital', $increase_capital);                       
        $this->assign('increase_bond', $increase_bond);  
        $this->assign('profit_loss', $profit_loss);  
        $this->assign('num', $num); 
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
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
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Deal->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $total=$this->Deal->where($map)->sum("total");
        $number=$this->Deal->where($map)->sum("number");
        $bond=$this->Deal->where($map)->sum("bond");
        $ratio=$this->Deal->where($map)->sum("ratio");
        $fee=$this->Deal->where($map)->sum("fee");
        $extra=$this->Deal->where($map)->sum("extra");
        $trade_fee=$this->Deal->where($map)->sum("trade_fee");
        $sell_fee=$this->Deal->where($map)->sum("sell_fee");
        $buy_fee=$this->Deal->where($map)->sum("buy_fee");
        $increase_capital=$this->Deal->where($map)->sum("increase_capital");
        $increase_bond=$this->Deal->where($map)->sum("increase_bond");
        $profit_loss=$this->Deal->where($map)->sum("profit_loss");
        $num=$this->Deal->where($map)->sum("num");
        $this->assign('total', $total);  
        $this->assign('number', $number);  
        $this->assign('bond', $bond);  
        $this->assign('ratio', $ratio);  
        $this->assign('fee', $fee);  
        $this->assign('extra', $extra);  
        $this->assign('trade_fee', $trade_fee);                          
        $this->assign('sell_fee', $sell_fee);  
        $this->assign('buy_fee', $buy_fee);  
        $this->assign('increase_capital', $increase_capital);                       
        $this->assign('increase_bond', $increase_bond);  
        $this->assign('profit_loss', $profit_loss);  
        $this->assign('num', $num); 
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
     //结算明细
    public function set(){
        $map['status'] = 1;
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Deal->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $number=$this->Deal->where($map)->sum("number");
        $increase_bond=$this->Deal->where($map)->sum("increase_bond");
        $num=$this->Deal->where($map)->sum("num");
        $bond=$this->Deal->where($map)->sum("bond");
        $fee=$this->Deal->where($map)->sum("fee");
        $buy_fee=$this->Deal->where($map)->sum("buy_fee");       
        $profit_loss=$this->Deal->where($map)->sum("profit_loss");
        $this->assign('number', $number);
        $this->assign('increase_bond', $increase_bond);  
        $this->assign('num', $num);  
        $this->assign('bond', $bond);  
        $this->assign('fee', $fee);  
        $this->assign('buy_fee', $buy_fee);  
        $this->assign('profit_loss', $profit_loss);    
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
    //平台资金
     public function plat(){
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Plat->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $hap_price=$this->Plat->where($map)->sum("hap_price");
        $sur_price=$this->Plat->where($map)->sum("sur_price");
        $acc_price=$this->Plat->where($map)->sum("acc_price");
        $this->assign('hap_price', $hap_price);
        $this->assign('sur_price', $sur_price);
        $this->assign('acc_price', $acc_price);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

}
