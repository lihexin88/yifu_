<?php

namespace app\monitor\controller;

use think\Request;

class Strategyconfig extends Common
{

    private $Strategyconfig;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Strategyconfig = new \app\common\model\Strategyconfig();
    }

    /*
       策略配置列表
      */
    public function index()
    {
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Strategyconfig->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    //执行添加和修改
    public function updates()
    {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            $list = $this->Strategyconfig->updates($data);
            if ($list) {
                $r = msg_handle('修改成功', 0);
            } else {
                $r = msg_handle('修改失败', 0);
            }
        }
        return $r;
    }
}