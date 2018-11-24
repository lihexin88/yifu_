<?php

namespace app\monitor\controller;
use app\common\model\Capital;
use app\common\model\Stock;
use app\common\model\UserAccount;
use app\common\model\Flow;
use think\Request;

class Capitals extends Common
{
    private $Capital;
    private $UserAccount;
    private $Stock;
    private $Flow;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Capital = new Capital();
        $this->UserAccount = new UserAccount();
        $this->Stock = new Stock();
        $this->Flow = new Flow();
    }

    /**
     * 处理
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function sell_handle()
    {
       if (trade_time() == 0) {
            $r = msg_handle('休市时间,无法交易', 0);
            return json($r);
        } else {
            if (request()->isAjax()) {
                $data = $_POST;
                if (empty($data['id'])) {
                    $r = msg_handle('操作错误', 0);
                } else {
                    $map['id']=$data['id'];
                    $map['status']=0;
                    $list = $this->Capital->query_log($map);
                    if (empty($list)) {
                        $r = msg_handle('操作错误', 0);
                    } else {
                        $res = $this->heads_close_position($list);
                        return $res;
                       /* if ($this->heads_close_position($list)) {
                            // $res = $this->heads_close_position($value, 1);
                            return '平仓成功';
                            $d['uid']=session('admin')['id'];
                            $d['time']=time();
                            $d['desc']='管理员'.session('admin')['name'].'修改了平台介绍';
                            $this->admin_log($d);
                            $r = msg_handle('平仓成功', 1);
                        } else {
                            $r = msg_handle('操作失败', 0);
                        }*/
                    }
                }
            } else {
                $r = msg_handle('错误操作', 0);
            }
            return json($r);
         }
    }
   
    /**
     * 执行平仓
     * @param $capital
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function heads_close_position($capital)
    {
        $user = $this->UserAccount->where('uid', $capital['uid'])->find();
        $balance = $user['account'];
        $agent = $user['agent'];
        $staff = $user['staff'];
//        $stock_info = stock_tencent($capital['code']);//当前股票信息
        $stock_info = stock_real($capital['code']);//当前股票信息
        $stock_info['4']=10;
        $now_total = $stock_info['4'] * $capital['num'];//当前价格
        $profit_loss = $now_total + $capital['surplus_price'] - $capital['total'];//盈亏
        $back_bond = $capital['number'] + $profit_loss;//返还用户
        if ($back_bond < 0) {
            $back_bond = 0;
        }
        if ($stock_info) {
            $this->Capital->startTrans();
            $this->UserAccount->startTrans();
            $this->Flow->startTrans();
            $res1 = $this->edit_capital($capital['id'], $stock_info['4'], 1, 2, time(), $profit_loss, $back_bond);
            $res2 = $this->UserAccount->add_account($capital['uid'], $back_bond + 0.01);//用户账户结算
            $desc = '用户ID为' . $capital['uid'] . '结算策略ID为' . $capital['id'] . '返还' . $back_bond;
            $res3 = $this->Flow->exit_log($capital['uid'], $back_bond, 1, 8, $balance + $back_bond, $agent, $staff, $desc);//资金流水记录
            if ($res1 && $res3) {
                $this->Capital->commit();
                $this->UserAccount->commit();
                $this->Flow->commit();
                $r = msg_handle('平仓成功', 1);
            } else {
                $this->Capital->rollback();
                $this->UserAccount->rollback();
                $this->Flow->rollback();
                $r = msg_handle('平仓失败', 0);
            }
            //满足条件,执行平仓
        } else {
            $r = msg_handle('没有自动平仓的', 0);
            //不满足条件,执行平仓
        }
        return $r;

    }
    /**
     * 修改平仓信息
     */
    /**
     * @param $id 配资id
     * @param $selling_price 卖出价格
     * @param $status 状态 0已配资 1已结算
     * @param $type 类型 默认0 1正常结算 2强制平仓
     * @param $clear_time 结算时间
     * @param $profit_loss 点买盈亏
     * @param $back_bond 返还用户保证金
     */
    public function edit_capital($id, $selling_price, $status, $type, $clear_time, $profit_loss, $back_bond)
    {
        $data['id'] = $id;
        $data['selling_price'] = $selling_price;
        $data['status'] = $status;
        $data['type'] = $type;
        $data['clear_time'] = $clear_time;
        $data['profit_loss'] = $profit_loss;
        $data['back_bond'] = $back_bond;
        return $this->Capital->where('id', $id)->update($data);
    }

}
