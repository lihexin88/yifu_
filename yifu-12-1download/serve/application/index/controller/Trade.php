<?php

namespace app\index\controller;

use app\index\model\Contract;
use app\index\model\Stock;
use app\index\model\Depot;
use think\Request;

class Trade extends Common
{
    private $Stock;
    private $Contract;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Stock = new Stock();
        $this->Depot = new Depot();
        $this->Contract = new Contract();
    }

    public function index($id, $data)
    {
        $code = empty($data['code']) ? '' : $data['code']; //IC000
        $pattern = empty($data['pattern']) ? '' : $data['pattern'];  //0 投机 1 套利 2套保
        $direction = empty($data['direction']) ? '' : $data['direction']; //0 买 1卖
        $mode = empty($data['mode']) ? '' : $data['mode']; //0 开仓  1 平仓 2平今
        $classify = empty($data['classify']) ? 0 : $data['classify']; // 0 限价 1：最新价 2：对手价 3：挂单价 4:快速价
        $price = empty($data['price']) ? 0 : $data['price'];
        $number = empty($data['number']) ? 0 : intval($data['number']);
        if (empty($code)) {
            $r = msg_handle('请选择合约名称', 0);
        } elseif ($pattern != 0 && $pattern != 1 && $pattern != 2) {
            $r = msg_handle('请选择投机方式', 0);
        } elseif ($direction != 0 && $direction != 1) {
            $r = msg_handle('请选择交易方向', 0);
        } elseif ($mode != 0 && $mode != 1 && $mode != 2) {
            $r = msg_handle('请选择仓位类型', 0);
        } elseif (!in_array($classify, array(0, 1, 2, 3, 4, 5, 6))) {
            $r = msg_handle('请选择价位信息', 0);
        } elseif ($classify == 0 && empty($price)) {
            $r = msg_handle('请选择价位', 0);
        } elseif (empty($number)) {
            $r = msg_handle('请选择交易数量手数', 0);
        } else {
            $contract = $this->Contract->where(array('status' => 1, 'name|short|code' => $code))->find();
            if (empty($contract)) {
                $r = msg_handle('暂未开启此合约交易', 0);
            } else {
                if ($mode == 0) {
                    $TradeBuy = new TradeBuy();
                    $r = $TradeBuy->index($id, $contract, $pattern, $direction, $mode, $classify, $price, $number);
                } else {
                    $order_id = empty($data['order_id']) ? 0 : intval($data['order_id']);
                    if (empty($order_id)) {
                        $r = msg_handle('请选择交易订单', 0);
                    } else {
                        $order = $this->Depot->where(array("id" => $order_id, "uid" => $id))->find();
                        if (!$order) {
                            $r = msg_handle('订单不存在', 0);
                        } else {
                            if ($order["direction"] == 1) {
                                $direction = 0;
                            } else {
                                $direction = 1;
                            }
                            $TradeSell = new TradeSell();
                            $r = $TradeSell->index($id, $order_id, $contract, $pattern, $direction, $mode, $classify, $price, $number);
                        }
                    }
                }
            }
        }
        return $r;
    }

    public function news_index($id, $data)
    {
        $code = empty($data['code']) ? '' : $data['code']; //IC000
        $pattern = empty($data['pattern']) ? '' : $data['pattern'];  //0 投机 1 套利 2套保
        $direction = empty($data['direction']) ? '' : $data['direction']; //0 买 1卖
        $mode = empty($data['mode']) ? '' : $data['mode']; //0 开仓  1 平仓 2平今
        $classify = empty($data['classify']) ? 0 : $data['classify']; // 0 限价 1：最新价 2：对手价 3：挂单价 4:快速价
        $price = 10;//empty($data['price']) ? 0 : $data['price'];
        $number = empty($data['number']) ? 0 : intval($data['number']);
        if (empty($code)) {
            $r = msg_handle('请选择合约名称', 0);
        } elseif ($pattern != 0 && $pattern != 1 && $pattern != 2) {
            $r = msg_handle('请选择投机方式', 0);
        } elseif ($direction != 0 && $direction != 1) {
            $r = msg_handle('请选择交易方向', 0);
        } elseif ($mode != 0 && $mode != 1 && $mode != 2) {
            $r = msg_handle('请选择仓位类型', 0);
        } elseif (!in_array($classify, array(0, 1, 2, 3, 4, 5, 6))) {
            $r = msg_handle('请选择价位信息', 0);
        } elseif ($classify == 0 && empty($price)) {
            $r = msg_handle('请选择价位', 0);
        } elseif (empty($number)) {
            $r = msg_handle('请选择交易数量手数', 0);
        } else {
            $contract = $this->Contract->where(array('status' => 1, 'name|short|code' => $code))->find();
            if (empty($contract)) {
                $r = msg_handle('暂未开启此合约交易', 0);
            } else {
                if ($mode == 0) {
                    $TradeBuy = new TradeBuy();
                    $r = $TradeBuy->index($id, $contract, $pattern, $direction, $mode, $classify, $price, $number);
                } else {
                    $order_id = empty($data['order_id']) ? 0 : intval($data['order_id']);
                    if (empty($order_id)) {
                        $r = msg_handle('请选择交易订单', 0);
                    } else {
                        $order = $this->Depot->where(array("id" => $order_id, "uid" => $id))->find();
                        if (!$order) {
                            $r = msg_handle('订单不存在', 0);
                        } else {
                            if ($order["direction"] == 1) {
                                $direction = 0;
                            } else {
                                $direction = 1;
                            }
                            $TradeSell = new TradeSell();
                            $r = $TradeSell->index($id, $order_id, $contract, $pattern, $direction, $mode, $classify, $price, $number);
                        }
                    }
                }
            }
        }
        return $r;
    }

    /**
     * 用户快捷锁仓
     * @param $id
     * @param $data
     * @return array
     */
    public function lock_position($id, $data)
    {
        if (empty($data['depot_id'])) {
            $r = msg_handle('请选择一笔持仓进行锁仓', 0);
        } elseif (empty($data['price'])) {
            $r = msg_handle('请选择价位信息', 0);
        } else {
            $map['uid'] = $id;
            $map['id'] = $data['depot_id'];
            $map['is_lock'] = 0;
            $order = $this->Depot->where($map)->find();
            if (empty($order)) {
                $r = msg_handle('合约信息有误', 0);
            } else {
                $res = $this->Depot->where('id', $data['depot_id'])->update(array('is_lock' => 1, 'lock_price' => $data['price']));
                if ($res) {
                    $r = msg_handle('锁仓成功', 1);
                } else {
                    $r = msg_handle('锁仓失败', 0);
                }
            }
        }
        return $r;
    }

    /**
     * 港交所
     * @return int
     */
    private function hk_fe_time()
    {
        $trade = 0;
        $c_time = time();
        $time = day_time();
        $am_start = $time + 9 * 3600 + 15 * 60;
        $am_end = $time + 12 * 3600 + 0 * 60;
        $pm_start = $time + 13 * 3600 + 0 * 60;
        $pm_end = $time + 16 * 3600 + 30 * 60;
        $at_start = $time + 17 * 3600 + 15 * 60;
        $at_end = $time + 23 * 3600 + 45 * 60;
        $bd_start = $time + 0 * 3600 + 0 * 60;
        $bd_end = $time + 1 * 3600 + 0 * 60;
        if ($c_time > $am_start && $c_time < $am_end) {
            $trade = 1;
        }
        if ($c_time > $pm_start && $c_time < $pm_end) {
            $trade = 1;
        }
        if ($c_time > $at_start && $c_time < $at_end) {
            $trade = 1;
        }
        if ($c_time > $bd_start && $c_time < $bd_end) {
            $trade = 1;
        }
        return $trade;
    }

    /**
     * 纽金所
     * @return int
     */
    private function co_mex_time()
    {
        $trade = 1;
        $c_time = time();
        $time = day_time();
        $am_start = $time + 5 * 3600 + 15 * 60;
        $am_end = $time + 6 * 3600 + 0 * 60;
        if ($c_time > $am_start && $c_time < $am_end) {
            $trade = 0;
        }
        return $trade;
    }

    /**
     * 纽商所
     * @return int
     */
    private function ny_mex_time()
    {
        $trade = 1;
        $c_time = time();
        $time = day_time();
        $am_start = $time + 5 * 3600 + 15 * 60;
        $am_end = $time + 6 * 3600 + 0 * 60;
        if ($c_time > $am_start && $c_time < $am_end) {
            $trade = 0;
        }
        return $trade;
    }

    /**
     * 芝商所
     * @return int
     */
    private function cme_time()
    {
        $trade = 1;
        $c_time = time();
        $time = day_time();
        $am_start = $time + 5 * 3600 + 15 * 60;
        $am_end = $time + 6 * 3600 + 0 * 60;
        if ($c_time > $am_start && $c_time < $am_end) {
            $trade = 0;
        }
        return $trade;
    }

    /**
     * 新商所
     * @return int
     */
    public function sgx_time()
    {
        $trade = 0;
        $c_time = time();
        $time = day_time();
        $am_start = $time + 9 * 3600 + 0 * 60;
        $am_end = $time + 16 * 3600 + 30 * 60;
        $pm_start = $time + 17 * 3600 + 0 * 60;
        $pm_end = $time + 24 * 3600 + 0 * 60;
        $bd_start = $time + 0 * 3600 + 0 * 60;
        $bd_end = $time + 4 * 3600 + 45 * 60;
        if ($c_time > $am_start && $c_time < $am_end) {
            $trade = 1;
        }
        if ($c_time > $pm_start && $c_time < $pm_end) {
            $trade = 1;
        }
        if ($c_time > $bd_start && $c_time < $bd_end) {
            $trade = 1;
        }
        return $trade;
    }

    /**
     * 欧交所
     * @return int
     */
    public function eur_ex_time()
    {
        $trade = 0;
        $c_time = time();
        $time = day_time();
        $pm_start = $time + 14 * 3600 + 0 * 60;
        $pm_end = $time + 24 * 3600 + 0 * 60;
        $bd_start = $time + 0 * 3600 + 0 * 60;
        $bd_end = $time + 4 * 3600 + 45 * 60;
        if ($c_time > $pm_start && $c_time < $pm_end) {
            $trade = 1;
        }
        if ($c_time > $bd_start && $c_time < $bd_end) {
            $trade = 1;
        }
        return $trade;
    }
    //注意事项：
    //"当客户触发平仓线或隔夜强平时，只将仓位强平到维持保证金以内
    //系统强平方式为逐手下单，强平顺序为先开先平
    //每周五晚上休市前5分钟统一强制平仓
    //手续费的收取方式为开仓时冻结手续费，平仓时收取"
}
