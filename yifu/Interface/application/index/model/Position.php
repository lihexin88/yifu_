<?php

namespace app\index\model;

use think\Model;

class Position extends Model
{
    protected $table = 'sn_position';

    /**
     * 合约信息
     * @return $this
     */
    public function contract()
    {
        return $this->hasOne('Contract', 'id', 'cid')->field('name,code');
    }

    public function add_account($id, $number)
    {
        return $this->where(array('uid' => $id))->find();
    }

    /**
     * 查找持仓信息(列表)
     * @param $map
     * @param string $order
     * @param string $field
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function query_log($map, $order = 'time desc', $field = '')
    {
        $data = $this->where($map)->relation(array('contract'))->field($field)->order($order)->select();
        foreach ($data as $k => &$v) {
            $v["time"] = detail_time($v["time"]);
            $v['status'] = $this->query_status($v['status']);
            $v['trade_direct'] = $this->trade_direct($v['trade_direct']);
            $v['trade_type'] = $this->trade_type($v['trade_type']);
        }
        return $data;
    }

    /**
     * 持仓总列表
     * @param $map
     * @param string $order
     * @param string $field
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function query_list($map, $order = 'time desc', $field = '')
    {
        $data = $this->where($map)->relation(array('contract'))->field($field)->order($order)->select();
        foreach ($data as $k => &$v) {
            $v["time"] = detail_time($v["time"]);
            $v['status'] = $this->query_status($v['status']);
            $v['trade_direct'] = $this->trade_direct($v['trade_direct']);
            $v['trade_type'] = $this->trade_type($v['trade_type']);
        }
        return $data;
    }

    /**
     * 状态
     * @param $status
     * @return string
     */
    public function query_status($status)
    {
        switch ($status) {
            case 0:
                $status = '委托中';
                break;
            case 1:
                $status = '持仓中';
                break;
            default:
                $status = '持仓中';
                break;
        }
        return $status;
    }

    /**
     * 交易方向 1买入 2卖出
     * @param $trade_direct
     * @return string
     */
    public function trade_direct($trade_direct)
    {
        switch ($trade_direct) {
            case 1:
                $trade_direct = '买入';
                break;
            case 2:
                $trade_direct = '卖出';
                break;
            default:
                $trade_direct = '其他';
                break;
        }
        return $trade_direct;
    }

    /**
     * 交易模式：1开仓，2平仓
     * @param $trade_type
     * @return string
     */
    public function trade_type($trade_type)
    {
        switch ($trade_type) {
            case 1:
                $trade_type = '开仓';
                break;
            case 2:
                $trade_type = '平仓';
                break;
            default:
                $trade_type = '其他';
                break;
        }
        return $trade_type;
    }
}
