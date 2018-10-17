<?php

namespace app\index\controller;

use app\common\model\Option;

class Options extends Common {

    public function __construct(\think\Request $request = null) {
        parent::__construct($request);
        $this->Option = new Option;
    }

    public function index() {
        header("Content-type: text/html; charset=utf-8");
        $map = "";
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Option->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();;
    }

}
