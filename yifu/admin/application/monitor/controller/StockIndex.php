<?php

namespace app\monitor\controller;

use app\common\model\Stock;
use think\Db;
use think\Debug;
use think\Request;

class StockIndex extends Common
{
    private $Stock;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Stock = new Stock();
    }

    public function index()
    {
        Debug::remark('begin');
        $this->sh603();
        $this->sh601();
        $this->sh600();
        $this->sz000();
        $this->sz001();
        $this->sz002();
        $this->sz300();
        Debug::remark('end');
        echo Debug::getRangeTime('begin', 'end') . 's';
    }

    private function sh603($max = 1000, $short = 'sh603')
    {
        $codes = array();
        for ($i = 0; $i < $max; $i++) {
            $code = $short . sprintf("%03d", $i);
            array_push($codes, $code);
        }
        $this->get_contents($codes);
    }

    private function sh601($max = 1000, $short = 'sh601')
    {
        $codes = array();
        for ($i = 0; $i < $max; $i++) {
            $code = $short . sprintf("%03d", $i);
            array_push($codes, $code);
        }
        $this->get_contents($codes);
    }

    private function sh600($max = 1000, $short = 'sh600')
    {
        $codes = array();
        for ($i = 0; $i < $max; $i++) {
            $code = $short . sprintf("%03d", $i);
            array_push($codes, $code);
        }
        $this->get_contents($codes);
    }

    private function sz000($max = 1000, $short = 'sz000')
    {
        $codes = array();
        for ($i = 1; $i < $max; $i++) {
            $code = $short . sprintf("%03d", $i);
            array_push($codes, $code);
        }
        $this->get_contents($codes);
    }

    private function sz001()
    {
        $codes = array('sz001696', 'sz001896', 'sz001965', 'sz001979');
        $this->get_contents($codes);
    }

    private function sz002($max = 1000, $short = 'sz002')
    {
        $codes = array();
        for ($i = 1; $i < $max; $i++) {
            $code = $short . sprintf("%03d", $i);
            array_push($codes, $code);
        }
        $this->get_contents($codes);
    }

    private function sz300($max = 1000, $short = 'sz300')
    {
        $codes = array();
        for ($i = 1; $i < $max; $i++) {
            $code = $short . sprintf("%03d", $i);
            array_push($codes, $code);
        }
        $this->get_contents($codes);
    }

    private function get_contents($codes)
    {
        $map['short'] = array('in', $codes);
        $list_arr = Db::name('stock')->where($map)->select();
        foreach ($codes as $key => $value) {
            foreach ($list_arr as $k => $v) {
                if ($value == $v['short']) {
                    unset($codes[$key]);
                }
            }
        }
        sort($codes);
        $array = array_chunk($codes, 50, true);
        $stocks = array();
        foreach ($array as $kye => $value) {
            $arr = implode(",", $value);
            $url = "http://sqt.gtimg.cn/utf8/q=" . $arr;
            $contents = file_get_contents($url);
            $vowels = array("v_pv_none_match=\"1\";");
            $contents = trim(str_replace($vowels, null, $contents));
            $list = array_filter(explode('v_', $contents));
            if ($list) {
                foreach ($list as $k => $v) {
                    $v = preg_replace("/=(.*?)~/", "~", $v);
                    $v = str_replace(array("\";"), "", $v);
                    $v = explode('~', $v);
                    array_push($stocks, $this->stock_data($v));
                }
            }
        }
        if ($stocks) {
            Db::name('stock')->insertAll($stocks);
        }
    }

    private function stock_data($list)
    {
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
        if (strpos($map['name'], 'ST') !== false) {
            $map['status'] = 1;
        } else {
            $map['status'] = 0;
        }
        $map['time'] = time();
        return $map;
    }
}











