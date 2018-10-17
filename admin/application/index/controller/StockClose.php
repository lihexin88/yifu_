<?php

namespace app\index\controller;

use think\Db;
use think\Request;

class StockClose extends Common
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
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $id = input('post.id');
        if (trade_time() == 0) {
            $r = msg_handle('休市时间无法交易', 0);
        } else {
            $deal = Db::name('deal')->where(array('id' => $id, 'status' => 0))->find();
            if (empty($deal)) {
                $r = msg_handle('持仓订单不存在', 0);
            } elseif (day_time() <= $deal['time']) {
                $r = msg_handle('持仓时间不足', 0);
            } else {
                $price = $this->stock_open($deal['short']);
                if ($price['price']) {
                    $basic = Db::name('core_set')->where(array('id' => 1))->find();;
                    $money = $deal['surplus'] * $price['price'];
                    $fee = ($basic['sell'] + $basic['stamp_duty']) * $money;
                    $list = Db::name('stock')->where(array('code' => $deal['code'], 'status' => 0, 'stop' => 1))->find();
                    $array = Db::name('capital')->where(array('uid' => $deal['uid'], 'status' => 0, 'id' => $deal['capital']))->find();;
                    $r = $this->fast_sale_data($deal['uid'], $array, $list, $deal['surplus'], $price['price'], $fee, $deal, $basic);
                } else {
                    $r = msg_handle('此股票暂不可交易', 0);
                }
            }
        }
        return json($r);
    }

    /**
     * 出售成交处理
     * @param $id int 用户id
     * @param $capital array 配资信息
     * @param $list array 股票信息
     * @param $number int 数量
     * @param $price float 价位
     * @param $fee  float 手续费
     * @param $deal  array 持仓信息
     * @param $basic  array 基础信息
     * @return array
     * @throws \Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    private function fast_sale_data($id, $capital, $list, $number, $price, $fee, $deal, $basic)
    {
        $user = $this->User->where(array('id' => $id))->find();
        $money = $number * $price;
        $total = $money - $fee;
        $balance = $capital['total'] + $total;
        Db::name('capital')->startTrans();
        $res1 = $this->entrust_sell($capital, $total, $fee);
        $res2 = $this->deal_log($id, $capital['id'], $number, $price, 1, $list, $fee, $user['agent'], $user['staff']);
        $res3 = $this->fast_sale_buy_log($deal);
        $res4 = $this->deal_sell_handling($id, $user['agent'], $user['staff'], $capital['id'], $money, $balance, $fee, $list, $basic);
        $res5 = $this->buy_handling($id, ($number * $price), $fee);
        if ($res1 && $res2 && $res3 && $res4 && $res5) {
            Db::name('capital')->commit();
            $this->trade_log($user, $capital, $fee, $price, $number, $list, 1);
            $r = msg_handle('出售股票成功', 1);
        } else {
            Db::name('capital')->rollback();
            $r = msg_handle('暂时无法出售股票', 0);
        }
        return $r;
    }

    /**
     * @param $user
     * @param $capital
     * @param $fee
     * @param $price
     * @param $number
     * @param $list
     * @param $type
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function trade_log($user, $capital, $fee, $price, $number, $list, $type)
    {
        if ($user['agent']) {
            $this->agent_server($user['id'], $user['agent'], $user['staff'], $capital, $type, $number, $fee, $price, $list, 0);
        }
    }

    /**
     * @param $id
     * @param $agent
     * @param $staff
     * @param $capital
     * @param $type
     * @param $number
     * @param $money
     * @param $price
     * @param $list
     * @param $ratio
     * @param int $level
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    private function agent_server($id, $agent, $staff, $capital, $type, $number, $money, $price, $list, $ratio, $level = 1)
    {
        $data = Db::name('agent')->where(array('id' => $agent))->find();
        $cash = $money * ($data['ratio'] - $ratio);
        $cash = round($cash, 2);
        Db::name('agent_serve')->startTrans();
        if ($cash) {
            $res1 = $this->agent_serve($id, $agent, $staff, $capital, $cash, $price, $number, $list, $type);
        } else {
            $res1 = 0;
        }
        $res2 = $this->stack_trade($agent, $cash, $number, $level);
        if ($res1 && $res2) {
            Db::name('agent_serve')->commit();
        } else {
            Db::name('agent_serve')->rollback();
        }
    }

    /**
     * 股票交易收取交易费
     * @param $id int 代理id
     * @param $money int 金额
     * @param $number int 交易数量
     * @param $level
     * @return int|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function stack_trade($id, $money, $number, $level)
    {
        $list = Db::name('agent_account')->where(array('uid' => $id))->find();
        if ($list) {
            $map['uid'] = $list['uid'];
            $map['account'] = $list['account'] + $money;
            if ($level == 1) {
                $map['user_server_total'] = $list['user_server_total'] + $money;
            } else {
                $map['agent_server_total'] = $list['agent_server_total'] + $money;
            }
            $map['total'] = $list['total'] + $money;
            $map['trade_total'] = $list['trade_total'] + $number;
            $map['time'] = time();
            $r = Db::name('agent_account')->update($map);
        } else {
            $map['uid'] = $id;
            $map['account'] = $money;
            if ($level == 1) {
                $map['user_server_total'] = $money;
            } else {
                $map['agent_server_total'] = $money;
            }
            $map['total'] = $money;
            $map['trade_total'] = $number;
            $map['time'] = time();
            $r = Db::name('agent_account')->insert($map);
        }
        return $r;
    }

    /**
     * 交易手续费
     * @param $id int 用户id
     * @param $agent int 代理id
     * @param $staff int 员工id
     * @param $capital array 配资信息
     * @param $fee float 费用
     * @param $price  float 交易价格
     * @param $number int 交易数量
     * @param $list array 股票信息
     * @param $type  int 交易类型
     * @return int|string
     */
    public function agent_serve($id, $agent, $staff, $capital, $fee, $price, $number, $list, $type)
    {
        $map['uid'] = $id;
        $map['agent'] = $agent;
        $map['staff'] = $staff;
        $map['capital'] = $capital['id'];
        $map['price'] = $price;
        $map['number'] = $number;
        $map['code'] = $list['code'];
        $map['name'] = $list['name'];
        $map['type'] = $type;
        $map['fee'] = $fee;
        $map['time'] = time();
        return Db::name('agent_serve')->insert($map);
    }

    /**
     * 股票交易处理
     * @param $id  int 用户id
     * @param $number float 交易数量
     * @param $fee float 佣金
     * @return int|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function buy_handling($id, $number, $fee)
    {
        $list = Db::name('user_account')->where(array('uid' => $id))->find();
        $map['uid'] = $id;
        $map['server_total'] = $list['server_total'] + $fee;
        $map['trade_total'] = $list['trade_total'] + $number;
        $map['time'] = time();
        return Db::name('user_account')->update($map);
    }

    /**
     * 出售成功
     * @param $id  int 用户id
     * @param $agent int 代理id
     * @param $staff int 员工id
     * @param $capital int 配资id
     * @param $money float 总价值
     * @param $balance  float 剩余数量
     * @param $fee float 费用
     * @param $list  array 股票信息
     * @param $basic  array 基础信息
     * @return int|string
     */
    public function deal_sell_handling($id, $agent, $staff, $capital, $money, $balance, $fee, $list, $basic)
    {
        $stamp = $basic['stamp_duty'] * $money;
        $sell = $basic['sell'] * $money;
        $total = $money - $fee;
        $balance_trade = $balance + $stamp;
        $balance_stamp = $balance + $sell;
        $arr[0] = $this->add_log_data($id, $agent, $staff, $capital, $total, $balance, $list['code'], $list['name'], 4);
        $arr[1] = $this->add_log_data($id, $agent, $staff, $capital, -$sell, $balance_trade, $list['code'], $list['name'], 7);
        $arr[2] = $this->add_log_data($id, $agent, $staff, $capital, -$stamp, $balance_stamp, $list['code'], $list['name'], 10);
        return Db::name('flow_log')->insertAll($arr);
    }

    /**
     * 数据处理
     * @param $id int 用户数据
     * @param $agent int 用户数据
     * @param $staff int 用户数据
     * @param $capital int 配资id
     * @param $number int 数量
     * @param $balance float 剩余
     * @param $code int/string 股票代码
     * @param $name int/string 股票名称
     * @param $type int 类型
     * @return mixed
     */
    public function add_log_data($id, $agent, $staff, $capital, $number, $balance, $code, $name, $type)
    {
        $map['uid'] = $id;
        $map['agent'] = $agent;
        $map['staff'] = $staff;
        $map['capital'] = $capital;
        $order = str_shuffle(time() . str_shuffle(sprintf('%05d', $id)));
        $map['code'] = $code;
        $map['name'] = $name;
        $map['number'] = $number;
        $map['order'] = $order;
        $map['number'] = $number;
        $map['balance'] = $balance;
        $map['type'] = $type;
        $map['time'] = time();
        return $map;
    }

    /**
     * 添加记录
     * @param $id  int  用户id
     * @param $agent  int  代理id
     * @param $staff  int  员工id
     * @param $capital int 配资id
     * @param $number int 数量
     * @param $price int 价格
     * @param $type int 买卖类型
     * @param $list array 数据
     * @param $fee float 交易佣金
     * @return false|int
     */
    public function deal_log($id, $capital, $number, $price, $type, $list, $fee, $agent, $staff)
    {
        $map['uid'] = $id;
        $map['capital'] = $capital;
        $map['agent'] = $agent;
        $map['staff'] = $staff;
        $order = str_shuffle(time() . $type . str_shuffle(sprintf('%05d', $id)));
        $map['order'] = $order;
        $map['short'] = $list['short'];
        $map['code'] = $list['code'];
        $map['name'] = $list['name'];
        $map['number'] = $number;
        if ($type == 0) {
            $map['surplus'] = $number;
            $map['finish'] = 0;
        } else {
            $map['surplus'] = 0;
            $map['finish'] = $number;
            $map['status'] = 1;
        }
        $map['deal'] = $price;
        $map['type'] = $type;
        $map['fee'] = $fee;
        $map['time'] = time();
        return Db::name('deal')->insert($map);
    }

    /**
     * @param $list
     * @param $number
     * @param $fee
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function entrust_sell($list, $number, $fee)
    {
        $map['id'] = $list['id'];
        $map['total'] = $list['total'] + $number;
        $map['fee'] = $list['fee'] + $fee;
        $map['sell_fee'] = $list['sell_fee'] + $fee;
        return Db::name('capital')->update($map);
    }

    /**
     * @param $deal
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    private function fast_sale_buy_log($deal)
    {
        $map['id'] = $deal['id'];
        $map['surplus'] = 0;
        $map['finish'] = $deal['finish'] + $deal['surplus'];
        $map['status'] = 1;
        return Db::name('deal')->update($map);
    }


    /**
     * 当前价位
     * @param $short
     * @return mixed
     */
    private function stock_open($short)
    {
        $stock = stock_real($short);
        if ($stock) {
            $arr['limit'] = round(floatval($stock[3]) * (1 - 0.1), 2);
            $arr['price'] = floatval($stock[4]);
        } else {
            $arr['limit'] = $arr['price'] = 0;
        }
        return $arr;
    }
}