<?php

namespace app\common\model;

use think\Model;

class Stock extends Model
{
    protected $table = 'sn_stock';

    /**
     * 查询股票信息
     * @param $map array 条件
     * @param $page int 页数
     * @param $size int 条数
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function data_handling($map, $page, $size)
    {
        $arr = $this->where($map)->order('id asc')->page($page, $size)->select()->toArray();
        if ($arr) {
            $data = $this->price_stock($arr);
            foreach ($arr as $key => $value) {
                if ($value['stop'] == 1 && $value['close'] == 1) {
                    $array = $this->stocks_data($data, $value['short']);
                    $arr[$key]['price'] = $array[4];
                    $arr[$key]['up_down'] = round(($array[4] - $array[3]), 2);
                    $arr[$key]['percent'] = round((($array[4] - $array[3]) / $array[3] * 100), 2);
                    $arr[$key]['amplitude'] = round((($array[5] - $array[6]) / $array[3] * 100), 2);
                }
                $arr[$key]['stop_name'] = $value['stop'] ? '正常' : '停牌';
                $arr[$key]['buy_name'] = $value['buy'] ? '正常' : '禁止';
                $arr[$key]['sell_name'] = $value['sell'] ? '正常' : '禁止';
                $arr[$key]['status_name'] = $value['status'] == 0 ? '正常' : '禁止';
            }
        }
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

    /**
     * 获取股票价位
     * @param $list
     * @return array
     */
    private function price_stock($list)
    {
        $stocks = array();
        foreach ($list as $key => $value) {
            if ($value['stop'] == 1 && $value['close'] == 1) {
                if (!in_array($value['short'], $stocks)) {
                    array_push($stocks, $value['short']);
                }
            }
        }
        if ($stocks) {
            return stocks_real(implode(',', $stocks));
        } else {
            return $stocks;
        }
    }

    /**
     * 股票数据处理
     * @param $data array 股票价位
     * @param $short string 股票代码
     * @return int
     */
    public function stocks_data($data, $short)
    {
        $arr = array();
        foreach ($data as $key => $value) {
            if ($value[0] == $short) {
                $arr = $value;
            }
        }
        return $arr;
    }
}

