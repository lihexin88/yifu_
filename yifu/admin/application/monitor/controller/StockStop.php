<?php

namespace app\monitor\controller;

use app\common\model\Stock;
use think\Db;
use think\Debug;
use think\Request;

class StockStop extends Common
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }

    /**
     * @throws \Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function stock()
    {
        $map = array();
        $list = Db::name('stock')->where($map)->field('id,short')->select();
        $codes = array();
        foreach ($list as $key => $value) {
            array_push($codes, $value['short']);
        }
        $this->get_contents($codes, $list);
    }


    /**
     * @throws \Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function shop_index()
    {

        $map['stop'] = 0;
        $map['status'] = 0;
        $map['close'] = 1;
        $list = Db::name('stock')->where($map)->field('id,short')->select();
        $codes = array();
        foreach ($list as $key => $value) {
            array_push($codes, $value['short']);
        }
        $this->get_contents($codes, $list);
    }

    /**
     * @throws \Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {

        $map['stop'] = 1;
        $map['status'] = 0;
        $map['close'] = 1;
        $list = Db::name('stock')->where($map)->field('id,short')->select();
        $codes = array();
        foreach ($list as $key => $value) {
            array_push($codes, $value['short']);
        }
        $this->get_contents($codes, $list);

    }

    /**
     * @param $codes
     * @param $data
     * @throws \Exception
     */
    private function get_contents($codes, $data)
    {
        $array = array_chunk($codes, 50, true);
        foreach ($array as $kye => $value) {
            $stocks = array();
            $arr = implode(",", $value);
            $url = "http://push3.gtimg.cn/utf8/q=" . $arr;
            $contents = file_get_contents($url);
            $vowels = array("v_pv_none_match=\"1\";");
            $contents = trim(str_replace($vowels, null, $contents));
            $list = array_filter(explode('v_', $contents));
            if ($list) {
                foreach ($list as $k => $v) {
                    $v = preg_replace("/=(.*?)~/", "~", $v);
                    $v = str_replace(array("\";"), "", $v);
                    $v = explode('~', $v);
                    array_push($stocks, $this->stock_data($v, $data));
                }
            }
            if ($stocks) {
                $stock = model('stock');
                $stock->saveAll($stocks);
            }
        }

    }

    private function stock_data($list, $data)
    {
        $v = $this->query_data(trim($list[0]), $data);
        $map['id'] = $v['id'];
        $map['short'] = trim($list[0]);
        $map['name'] = trim($list[1]);
        $map['code'] = trim($list[2]);
        $map['concept'] = trim($list[40]);
        if ($list[40] == 'D') {//退市
            $map['close'] = 0;
            $map['stop'] = 0;
        } else if ($list[40] == 'S') {//停牌
            $map['close'] = 1;
            $map['stop'] = 0;
        } else if ($list[40] == 'U') {//未上市
            $map['close'] = 0;
            $map['stop'] = 0;
        } else if ($list[40] == 'Z') {//即将退市
            $map['close'] = 0;
            $map['stop'] = 0;
        } else {
            $map['close'] = 1;
            $map['stop'] = 1;
        }
        if (strpos($map['name'], 'ST') !== false || $map['short'] == 'sz001481') {
            $map['status'] = 1;
        } else {
            $map['status'] = 0;
        }
        $map['time'] = time();
        return $map;
    }

    public function query_data($short, $data)
    {
        foreach ($data as $kye => $valuer) {
            if ($valuer['short'] == $short) {
                return $valuer;
            }
        }
    }
}













