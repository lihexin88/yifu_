<?php

namespace app\index\controller;

use app\common\model\Stock;
use think\Request;

class ShockManage extends Common
{
    private $Stock;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Stock = new Stock();
    }

    /**
     * 股票列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $a=Request::instance()->param('a');
        $b=Request::instance()->param('b');
        $map = array('stop' => 1, 'status' => 0, 'close' => 1);
        $map = $this->query_code($map, trim(input('get.name')));
        $current_page = page_judge(input('get.page'));
        $list = $this->Stock->data_handling($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(14));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        $this->assign('a',$a);
        $this->assign('b',$b);
        return $this->fetch();
    }

    /**
     * 禁止交易
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function prohibit()
    {
        $map = array('stop' => 1, 'status' => 1, 'close' => 1);
        $map = $this->query_code($map, trim(input('get.name')));
        $current_page = page_judge(input('get.page'));
        $list = $this->Stock->data_handling($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $a=Request::instance()->param('a');
        $b=Request::instance()->param('b');
        $this->assign('a',$a);
        $this->assign('b',$b);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(14));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /**
     * 停牌股票
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function stop()
    {
        $map = array('stop' => 0, 'status' => 0, 'close' => 1);
        $map = $this->query_code($map, trim(input('get.name')));
        $current_page = page_judge(input('get.page'));
        $list = $this->Stock->data_handling($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $a=Request::instance()->param('a');
        $b=Request::instance()->param('b');
        $this->assign('a',$a);
        $this->assign('b',$b);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(14));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /**
     * 退市股票
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function close()
    {
        $map = array('close' => 0, 'stop' => 0, 'status' => 0);
        $map = $this->query_code($map, trim(input('get.name')));
        $current_page = page_judge(input('get.page'));
        $list = $this->Stock->data_handling($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $a=Request::instance()->param('a');
        $b=Request::instance()->param('b');
        $this->assign('a',$a);
        $this->assign('b',$b);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(14));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /**
     * 查询过
     * @param $map array 条件
     * @param $code  string 股票信息
     * @return mixed
     */
    private function query_code($map, $code)
    {
        if (isset($code) && $code) {
            $map['name|code'] = array('like', '%' . trim(input('get.name')) . '%');
        }
        return $map;
    }

    public function add_log()
    {
        $a=Request::instance()->param('a');
        $b=Request::instance()->param('b');
        $this->assign('a',$a);
        $this->assign('b',$b);
        return $this->fetch();
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add_handle()
    {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['name'])) {
                $r = array('status' => 0, 'info' => '股票代码不能为空');
            } elseif ($data['type'] != 1 && $data['type'] != 2) {
                $r = array('status' => 0, 'info' => '交易所选择错误');
            } else {
                if ($data['type'] == 1) {
                    $stock = 'sh' . $data['name'];
                } else {
                    $stock = 'sz' . $data['name'];
                }
                $list = $this->Stock->where(array('short' => $stock))->find();
                if ($list) {
                    $r = array('status' => 0, 'info' => '股票代码已经存在');
                } else {
                    $url = "http://hq.sinajs.cn/list=" . $stock;
                    $html = file_get_contents($url);
                    $html = substr($html, 0, strlen($html) - 3);
                    $html = substr($html, 21, strlen($html));
                    $arr = explode(',', $html);
                    if ($arr[0]) {
                        $map['code'] = $data['name'];
                        $map['short'] = $stock;
                        $map['name'] = iconv('GBK', 'UTF-8', $arr[0]);
                        $map['pinyin'] = Pinyin($map['name']);
                        $map['time'] = time();
                        $this->Stock->insert($map);
                        $r = array('status' => 1, 'info' => '股票代码添加成功');
                    } else {
                        $r = array('status' => 0, 'info' => '未查询股票代码信息');
                    }
                }
            }
        } else {
            $r = array('status' => 0, 'info' => '错误操作');
        }
        return json($r);
    }


    /**
     * 股票操作
     * @return $this|int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function frozen()
    {
        if (request()->isAjax()) {
            $stock = $this->Stock->where(array('id' => $_POST['id']))->find();
            $map['id'] = $stock['id'];
            if ($_POST['type'] == 1) {
                $map['buy'] = $stock['buy'] == 0 ? 1 : 0;
            }
            if ($_POST['type'] == 2) {
                $map['sell'] = $stock['sell'] == 0 ? 1 : 0;
            }
            if ($_POST['type'] == 3) {
                $map['status'] = $stock['status'] == 0 ? 1 : 0;
            }
            if ($_POST['type'] == 4) {
                $map['stop'] = $stock['stop'] == 0 ? 1 : 0;
            }
            $r = $this->Stock->update($map);
        } else {
            $r = 0;
        }
        return $r;
    }

}