<?php

namespace app\agents\controller;

use app\agents\model\Flow;
use app\agents\model\Deal;
use app\agent\model\Strategy;
use think\Request;

class Count extends Common {

    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->Deal = new Deal();
        $this->Flow = new Flow();
        $this->Strategy = new Strategy();
    }

    //持仓
    public function depot() {
        $user = session('ygadmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['staff'] = $user['id'];
        $map['status'] = 0;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Deal->query_log($map, $current_page, $this->num, '');
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $bond = $this->Deal->where($map)->sum('bond');
        $number = $this->Deal->where($map)->sum('number');
        $increase_bond = $this->Deal->where($map)->sum('increase_bond');
        $sum_fee = $this->Deal->where($map)->sum('sum_fee');
        $total = $this->Deal->where($map)->sum('total');
        $this->assign('bond', $bond);
        $this->assign('number', $number);
        $this->assign('increase_bond', $increase_bond);
        $this->assign('sum_fee', $sum_fee);
        $this->assign('total', $total);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    //历史成交
    public function deal() {
        $user = session('ygadmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['staff'] = $user['id'];
        $map['status'] = 1;
        $map['type'] = 1;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Deal->query_log($map, $current_page, $this->num, '');
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $number = $this->Deal->where($map)->sum('number');
        $bond = $this->Deal->where($map)->sum('bond');
        $profit_loss = $this->Deal->where($map)->sum('profit_loss');
        $this->assign('profit_loss', $profit_loss);
        $this->assign('bond', $bond);
        $this->assign('number', $number);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    //今日成交
    public function today_deal() {
        $user = session('ygadmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $today = strtotime(date("Y-m-d"), time());
        $map['clear_time'] = ['>', $today];
        $map['staff'] = $user['id'];
        $map['status'] = 1;

        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Deal->query_log($map, $current_page, $this->num, '');
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $number = $this->Deal->where($map)->sum('number');
        $bond = $this->Deal->where($map)->sum('bond');
        $profit_loss = $this->Deal->where($map)->sum('profit_loss');
        $this->assign('profit_loss', $profit_loss);
        $this->assign('bond', $bond);
        $this->assign('number', $number);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    //平仓
    public function close() {
        $user = session('ygadmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['staff'] = $user['id'];
        $map['status'] = 1;
        $map['type'] = 2;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Deal->query_log($map, $current_page, $this->num, '');
        $number = $this->Deal->where($map)->sum('number');
        $bond = $this->Deal->where($map)->sum('bond');
        $profit_loss = $this->Deal->where($map)->sum('profit_loss');
        $this->assign('profit_loss', $profit_loss);
        $this->assign('bond', $bond);
        $this->assign('number', $number);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    //结算明细
    public function set() {
        $user = session('ygadmin');
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['staff'] = $user['id'];
        $map['status'] = 1;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $ratio = $this->Strategy->find();
        $list = $this->Deal->query_log($map, $current_page, $this->num, $ratio['profit_sharing']);
        $bond = $this->Deal->where($map)->sum('bond');
        $number = $this->Deal->where($map)->sum('number');
        $trade_fee = $this->Deal->where($map)->sum('trade_fee');
        $sum_fee = $this->Deal->where($map)->sum('sum_fee');
        $profit_loss = $this->Deal->where($map)->sum('profit_loss');
        $day = $this->Deal->where($map)->sum('day');
        $this->assign('ke', $profit_loss * $ratio['profit_sharing']);
        $this->assign('ping', $profit_loss * (1 - $ratio['profit_sharing']));
        $this->assign('profit_loss', $profit_loss);
        $this->assign('bond', $bond);
        $this->assign('trade_fee', $trade_fee);
        $this->assign('number', $number);
        $this->assign('sum_fee', $sum_fee);
        $this->assign('day', $day);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

}
