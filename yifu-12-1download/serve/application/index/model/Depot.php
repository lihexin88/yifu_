<?php

namespace app\index\model;

use think\Model;

class Depot extends Model
{
    protected $table = 'sn_depot';

    public function query_log($map)
    {
        $list = $this->where($map)->order(array('time desc'))->select();
        $data = array();
        foreach ($list as $key => $value) {
            $data[$key]['id'] = $value['id'];
            $data[$key]['contract'] = $value['name'];
            $data[$key]['direction'] = $value['direction'] == 0 ? '买' : '卖';
            $data[$key]['total'] = $value['number'];
            $data[$key]['today'] = $value['number'];
            $data[$key]['available'] = $value['surplus'];
            $data[$key]['daily_profit'] = $value['number'];
            $data[$key]['pen_profit'] = $value['number'];
            $data[$key]['bond'] = $value['bond'];
            $data[$key]['open_price'] = $value['average'];
            $data[$key]['position_price'] = $value['price'];
            $data[$key]['current'] = $value['price'];
            $data[$key]['loss'] = $value['loss'];
            $data[$key]['profit'] = $value['profit'];
            $data[$key]['pattern'] = $value['pattern'] == 0 ? '投机' : ($value['pattern'] == 1 ? '套利' : '套保');
            $data[$key]['is_lock'] = $value['is_lock'] == 0 ? '未锁' : '锁定';
        }
        return $data;
    }

    public function reduce_position($id, $number)
    {//委托平仓
        $order = $this->where(array("id" => $id))->find();
        if ($order) {
            $data["frozen"] = $order["frozen"] + $number;
            $all_number = $number + $order["frozen"] + $order["finish"];
            if ($all_number == $order["number"]) {
                $data["status"] = 2;
            } else {
                $data["status"] = 1;
            }
            $r = $this->where(array("id" => $id))->update($data);
        } else {
            $r = 0;
        }
        return $r;
    }

    /*
     *恢复持仓
     */
    public function recover_position($list)
    {
        $number = $list["number"] - $list["cancel"];
        $order_depot = $this->where(array("id" => $list["depot_id"]))->find();
        $data["frozen"] = $order_depot["frozen"] - $number;
        if ($order_depot["frozen"] == $number && $order_depot["finish"] < 1) {
            $data["status"] = 0;
        } else {
            $data["status"] = 1;
        }
        return $this->where(array("id" => $order_depot["id"]))->update($data);
    }

    public function add_log($uid, $short, $code, $name, $number, $price, $average, $cost, $direction, $pattern, $time, $bond, $fee, $buy_total, $sell_total, $profit, $loss)
    {//  uid  证券简码 证券代码  证券名称  持仓数量  持仓均价  开仓均价  持仓成本均价  委托方向 0：买、1卖  投资类型0 投机 1 套利 2套保  开仓时间  保证金  手续费
        $map["uid"] = $uid;
        $map["short"] = $short;
        $map["code"] = $code;
        $map["name"] = $name;
        $map["number"] = $number;
        $map["price"] = $price;
        $map["average"] = $average;
        $map["cost"] = $cost;
        $map["direction"] = $direction;
        $map["pattern"] = $pattern;
        $map["time"] = $time;
        $map["bond"] = $bond;
        $map["fee"] = $fee;
        $map["buy_total"] = $buy_total;
        $map["sell_total"] = $sell_total;
        $map["profit"] = $profit;
        $map["loss"] = $loss;
        return $this->insert($map);
    }

    public function update_sell($id, $number, $price)
    {//平仓处理
        $order = $this->where(array("id" => $id))->find();
        $all_number = $order["number"] - $order["finish"];//剩余可完成数量
        if ($number == $all_number) {
            $data["status"] = 3;
        }
        if ($order["direction"] == 1) {
            $data["buy_total"] = $number * $price + $order["buy_total"];
            $one_profit = $order["average"] - $price;//单个收益
        } else {
            $data["sell_total"] = $number * $price + $order["sell_total"];
            $one_profit = $price - $order["average"];//单个收益
        }
        if ($order["finish"] > 0) {
            $old_sell = $order["sell_price"] * $order["finish"];
            $new_sell = $price * $number + $old_sell;
            $new_number = $order["finish"] + $number;
            $data["sell_price"] = $new_sell / $new_number;
        } else {
            $data["sell_price"] = $price;
        }
        if ($order["average"] == $price) {
            $data["profit_loss"] = 0;
        } else {
            $data["profit_loss"] = $one_profit * $number + $order["profit_loss"];
        }
        $data["finish"] = $order["finish"] + $number;
        $data["frozen"] = $order["frozen"] - $number;
        $data["clean_time"] = time();
        $r = $this->where(array("id" => $id))->update($data);
        if ($r) {
            if ($order["average"] == $price) {
                return 0.1;
            } else {
                return $one_profit * $number;
            }
        } else {
            return 0;
        }
    }

    public function status_name($status)
    {
        switch ($status) {
            case 0:
                $name = '开仓完成';
                break;
            case 1:
                $name = '部分平仓完成';
                break;
            case 2:
                $name = '委托平仓完成';
                break;
            case 3:
                $name = '平仓已完成';
                break;
        }
        return $name;
    }
}
