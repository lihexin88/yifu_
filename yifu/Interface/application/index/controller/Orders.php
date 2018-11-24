<?php

namespace app\index\controller;

use app\index\model\User;
use app\index\model\Agree;
use app\index\model\Position;
use app\index\model\Entrust;
use app\index\model\Tradelog;
use app\index\model\Contract;
use app\index\model\Variety;
use app\index\model\Useraccount;
use app\index\model\Userflow;
use think\Controller;
use think\Request;

class Orders extends Controller
{
    private $User;
    private $Agree;
    private $Position;
    private $Entrust;
    private $Tradelog;
    private $Contract;
    private $Useraccount;
    private $Variety;
    private $Userflow;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->User = new User();
        $this->Agree = new Agree();
        $this->Position = new Position();
        $this->Entrust = new Entrust();
        $this->Tradelog = new Tradelog();
        $this->Contract = new Contract();
        $this->Useraccount = new Useraccount();
        $this->Variety = new Variety();
        $this->Userflow = new Userflow();
    }

    /**
     * 各种协议
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function create_order($id, $data, $price_type)
    {
        $user = $this->User->query_info($id);
        if (empty($data['code'])) {
            $r = msg_handle('请选择下单合约', 0);
        } elseif (empty($data['trade_direct'])) {
            $r = msg_handle('请选择交易方向', 0);
        } elseif (empty($data['trade_type'])) {
            $r = msg_handle('请选择交易模式', 0);
        } elseif (empty($data['number'])) {
            $r = msg_handle('请输入委托数量', 0);
        } elseif (empty($data['number'])) {
            $r = msg_handle('请输入委托数量', 0);
        }


//*/1 * * * *  /www/auto/auto_get_data.sh

        return $r;
        if (empty($data['type'])) {
            $r = msg_handle('请选择类型', 0);
        } else {
            $type = $data['type'];
            switch ($type) {
                case 1:
                    $map['type'] = 1;
                    break;
                case 2:
                    $map['type'] = 2;
                    break;
                default:
                    $map['type'] = 1;
                    break;
            }

            $data = $this->Agree->query_info($map);
            $r = msg_handle('', 1, $data);
        }
        return $r;
    }

    /**
     * 查找用户持仓
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function find_position($id)
    {
        $map = array("uid" => $id, 'number' => ['>', 0], 'status' => 1);
        $position = $this->Position->query_log($map, 'time desc', 'uid,cid,name,code,order_number,number,price,status,time,trade_direct,trade_type,deal_num,bond,fee');
        $r = msg_handle('', 1, $position);
        return $r;
    }

    /**
     * 用户持仓列表
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function position_list($id)
    {
        $map = array("uid" => $id, 'number' => ['>', 0], 'status' => 1);
        $position = $this->Position->query_list($map, 'time desc', 'uid,cid,name,code,order_number,number,price,status,time,trade_direct,trade_type,deal_num,bond,fee');
        $r = msg_handle('', 1, $position);
        return $r;
    }

    /**
     * 查找用户委托
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function find_entrust($id)
    {
        $position = $this->Entrust->where(array("uid" => $id, 'status' => 1))->select();
        if (count($position) > 0) {
            foreach ($position as $k => $v) {
                $position[$k]["time"] = date('Y-m-d H:i:s', $v["time"]);
                $position[$k]["deal_time"] = date('Y-m-d H:i:s', $v["deal_time"]);
            }
            $r = msg_handle('', 1, $position);
        } else {
            $r = msg_handle('暂无数据', 0);
        }
        return $r;
    }

    /**
     * 查找用户委托
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function find_trade_log($id)
    {
        $position = $this->Tradelog->where(array("uid" => $id, 'status' => 1))->select();
        if (count($position) > 0) {
            foreach ($position as $k => $v) {
                $position[$k]["time"] = detail_time($v["time"]);
            }
            $r = msg_handle('', 1, $position);
        } else {
            $r = msg_handle('暂无数据', 0);
        }
        return $r;
    }

    /**
     * 用户购买
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function user_buy($id, $data)
    {
//        $user = $this->User->query_info($id);
        $trade_type = $data["trade_type"];//购买方向  1  为买入   2为卖出
        $new_price = $data["new_price"];//系统当前价格
        $user_price = $data["user_price"];//用户购买价格
        $number = $data["number"];//用户购买数量
        $code = $this->Contract->where(array("code" => $data["code"]))->find();
        $user_account = $this->Useraccount->where('uid', $id)->find();
        $variety = $this->Variety->where("id", $code["variety_id"])->find();
        $bond = $code["bond"] * $number;//需要保证金
        $need_money = $bond + $variety["open_position_fee"];
        if ($trade_type == 1) {//买入
            if ($new_price > $user_price) {//委托状态

                if ($user_account["balance"] >= $need_money) {
                    $user_entrust["uid"] = $id;
                    $user_entrust["cid"] = $code["id"];
                    $user_entrust["name"] = $code["name"];
                    $user_entrust["code"] = $code["code"];
                    $user_entrust["number"] = $number;
                    $user_entrust["price"] = $user_price;
                    $user_entrust["status"] = 0;
                    $user_entrust["time"] = time();
                    $user_entrust["trade_direct"] = 1;
                    $user_entrust["trade_type"] = 1;
                    $user_entrust["total_num"] = $number;
                    $user_entrust["source"] = $data["source"];
                    $user_entrust["bond"] = $bond;
                    $user_entrust["price_type"] = $data["price_type"];
                    $this->Entrust->startTrans();
                    $one_list = $this->Entrust->insertGetId($user_entrust);
                    if ($one_list) {//订单编号
                        $ordersn["order_number"] = "en" . $id . $code["code"] . $one_list;
                        $two_list = $this->Entrust->where('id', $one_list)->update($ordersn);
                    }

                    $useracc["balance"] = $user_account["balance"] - $need_money;
                    $useracc["frozen_bond"] = $user_account["frozen_bond"] + $bond;
                    $three_list = $this->Useraccount->where('uid', $user_account["uid"])->update($useracc);

                    $one_flow["uid"] = $id;
                    $one_flow["order_num"] = "en" . $id . $code["code"] . $one_list;
                    $one_flow["number"] = $bond;
                    $one_flow["balance"] = $user_account["balance"] - $bond;
                    $one_flow["type"] = 1;
                    $one_flow["mold"] = 1;
                    $one_flow["time"] = time();
                    $one_flow["desc"] = "保证金";
                    $four_list = $this->Userflow->insertGetId($one_flow);

                    $two_flow["uid"] = $id;
                    $two_flow["order_num"] = "en" . $id . $code["code"] . $one_list;
                    $two_flow["number"] = $variety["open_position_fee"];
                    $two_flow["balance"] = $user_account["balance"] - $need_money;
                    $two_flow["type"] = 1;
                    $two_flow["mold"] = 1;
                    $two_flow["time"] = time();
                    $two_flow["desc"] = "手续费";
                    $five_list = $this->Userflow->insertGetId($two_flow);

                    if ($one_list && $two_list && $three_list && $four_list && $five_list) {
                        $this->Entrust->commit();
                        $r = msg_handle('添加成功', 1);
                    } else {
                        $this->Entrust->rollback();
                        $r = msg_handle('操作失败,请重试', 0);
                    }
                } else {
                    $r = msg_handle('账户金额不足,无法交易', 0);
                }
                //$r = msg_handle('账户金额不足,无法交易', 0);
            } else {//持仓状态

                if ($user_account["balance"] >= $need_money) {
                    $user_entrust["uid"] = $id;
                    $user_entrust["cid"] = $code["id"];
                    $user_entrust["name"] = $code["name"];
                    $user_entrust["code"] = $code["code"];
                    $user_entrust["number"] = $number;
                    $user_entrust["price"] = $user_price;
                    $user_entrust["status"] = 1;
                    $user_entrust["time"] = time();
                    $user_entrust["trade_direct"] = 1;
                    $user_entrust["trade_type"] = 1;
                    $user_entrust["total_num"] = $number;
                    $user_entrust["source"] = $data["source"];
                    $user_entrust["bond"] = $bond;
                    $user_entrust["price_type"] = $data["price_type"];
                    $this->Position->startTrans();
                    $one_list = $this->Position->insertGetId($user_entrust);
                    if ($one_list) {//订单编号
                        $ordersn["order_number"] = "po" . $id . $code["code"] . $one_list;
                        $two_list = $this->Position->where('id', $one_list)->update($ordersn);
                    }

                    $useracc["balance"] = $user_account["balance"] - $need_money;
                    $useracc["frozen_bond"] = $user_account["frozen_bond"] + $bond;
                    $three_list = $this->Useraccount->where('uid', $user_account["uid"])->update($useracc);

                    $one_flow["uid"] = $id;
                    $one_flow["order_num"] = "po" . $id . $code["code"] . $one_list;
                    $one_flow["number"] = $bond;
                    $one_flow["balance"] = $user_account["balance"] - $bond;
                    $one_flow["type"] = 1;
                    $one_flow["mold"] = 1;
                    $one_flow["time"] = time();
                    $one_flow["desc"] = "保证金";
                    $four_list = $this->Userflow->insertGetId($one_flow);

                    $two_flow["uid"] = $id;
                    $two_flow["order_num"] = "po" . $id . $code["code"] . $one_list;
                    $two_flow["number"] = $variety["open_position_fee"];
                    $two_flow["balance"] = $user_account["balance"] - $need_money;
                    $two_flow["type"] = 1;
                    $two_flow["mold"] = 1;
                    $two_flow["time"] = time();
                    $two_flow["desc"] = "手续费";
                    $five_list = $this->Userflow->insertGetId($two_flow);

                    if ($one_list && $two_list && $three_list && $four_list && $five_list) {
                        $this->Position->commit();
                        $r = msg_handle('添加成功', 1);
                    } else {
                        $this->Position->rollback();
                        $r = msg_handle('操作失败,请重试', 0);
                    }
                } else {
                    $r = msg_handle('账户金额不足,无法交易', 0);
                }

            }
        } else {//卖出
            if ($user_price > $new_price) {//委托状态

                if ($user_account["balance"] >= $need_money) {
                    $user_entrust["uid"] = $id;
                    $user_entrust["cid"] = $code["id"];
                    $user_entrust["name"] = $code["name"];
                    $user_entrust["code"] = $code["code"];
                    $user_entrust["number"] = $number;
                    $user_entrust["price"] = $user_price;
                    $user_entrust["status"] = 0;
                    $user_entrust["time"] = time();
                    $user_entrust["trade_direct"] = 2;
                    $user_entrust["trade_type"] = 1;
                    $user_entrust["total_num"] = $number;
                    $user_entrust["source"] = $data["source"];
                    $user_entrust["bond"] = $bond;
                    $user_entrust["price_type"] = $data["price_type"];
                    $this->Entrust->startTrans();
                    $one_list = $this->Entrust->insertGetId($user_entrust);
                    if ($one_list) {//订单编号
                        $ordersn["order_number"] = "en" . $id . $code["code"] . $one_list;
                        $two_list = $this->Entrust->where('id', $one_list)->update($ordersn);
                    }

                    $useracc["balance"] = $user_account["balance"] - $need_money;
                    $useracc["frozen_bond"] = $user_account["frozen_bond"] + $bond;
                    $three_list = $this->Useraccount->where('uid', $user_account["uid"])->update($useracc);

                    $one_flow["uid"] = $id;
                    $one_flow["order_num"] = "en" . $id . $code["code"] . $one_list;
                    $one_flow["number"] = $bond;
                    $one_flow["balance"] = $user_account["balance"] - $bond;
                    $one_flow["type"] = 1;
                    $one_flow["mold"] = 1;
                    $one_flow["time"] = time();
                    $one_flow["desc"] = "保证金";
                    $four_list = $this->Userflow->insertGetId($one_flow);

                    $two_flow["uid"] = $id;
                    $two_flow["order_num"] = "en" . $id . $code["code"] . $one_list;
                    $two_flow["number"] = $variety["open_position_fee"];
                    $two_flow["balance"] = $user_account["balance"] - $need_money;
                    $two_flow["type"] = 1;
                    $two_flow["mold"] = 1;
                    $two_flow["time"] = time();
                    $two_flow["desc"] = "手续费";
                    $five_list = $this->Userflow->insertGetId($two_flow);

                    if ($one_list && $two_list && $three_list && $four_list && $five_list) {
                        $this->Entrust->commit();
                        $r = msg_handle('添加成功', 1);
                    } else {
                        $this->Entrust->rollback();
                        $r = msg_handle('操作失败,请重试', 0);
                    }
                } else {
                    $r = msg_handle('账户金额不足,无法交易', 0);
                }

            } else {//持仓状态

                if ($user_account["balance"] >= $need_money) {
                    $user_entrust["uid"] = $id;
                    $user_entrust["cid"] = $code["id"];
                    $user_entrust["name"] = $code["name"];
                    $user_entrust["code"] = $code["code"];
                    $user_entrust["number"] = $number;
                    $user_entrust["price"] = $user_price;
                    $user_entrust["status"] = 1;
                    $user_entrust["time"] = time();
                    $user_entrust["trade_direct"] = 2;
                    $user_entrust["trade_type"] = 1;
                    $user_entrust["total_num"] = $number;
                    $user_entrust["source"] = $data["source"];
                    $user_entrust["bond"] = $bond;
                    $user_entrust["price_type"] = $data["price_type"];
                    $this->Position->startTrans();
                    $one_list = $this->Position->insertGetId($user_entrust);
                    if ($one_list) {//订单编号
                        $ordersn["order_number"] = "po" . $id . $code["code"] . $one_list;
                        $two_list = $this->Position->where('id', $one_list)->update($ordersn);
                    }

                    $useracc["balance"] = $user_account["balance"] - $need_money;
                    $useracc["frozen_bond"] = $user_account["frozen_bond"] + $bond;
                    $three_list = $this->Useraccount->where('uid', $user_account["uid"])->update($useracc);

                    $one_flow["uid"] = $id;
                    $one_flow["order_num"] = "po" . $id . $code["code"] . $one_list;
                    $one_flow["number"] = $bond;
                    $one_flow["balance"] = $user_account["balance"] - $bond;
                    $one_flow["type"] = 1;
                    $one_flow["mold"] = 1;
                    $one_flow["time"] = time();
                    $one_flow["desc"] = "保证金";
                    $four_list = $this->Userflow->insertGetId($one_flow);

                    $two_flow["uid"] = $id;
                    $two_flow["order_num"] = "po" . $id . $code["code"] . $one_list;
                    $two_flow["number"] = $variety["open_position_fee"];
                    $two_flow["balance"] = $user_account["balance"] - $need_money;
                    $two_flow["type"] = 1;
                    $two_flow["mold"] = 1;
                    $two_flow["time"] = time();
                    $two_flow["desc"] = "手续费";
                    $five_list = $this->Userflow->insertGetId($two_flow);

                    if ($one_list && $two_list && $three_list && $four_list && $five_list) {
                        $this->Position->commit();
                        $r = msg_handle('添加成功', 1);
                    } else {
                        $this->Position->rollback();
                        $r = msg_handle('操作失败,请重试', 0);
                    }
                } else {
                    $r = msg_handle('账户金额不足,无法交易', 0);
                }

            }
        }
        return $r;
    }

    /**
     * 用户平仓
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function user_close($id, $data)
    {

//        $user = $this->User->query_info($id);
        $order_number = $data["order_number"];//购买方向  1  为买入   2为卖出
        $new_price = $data["new_price"];//系统当前价格
        $user_price = $data["user_price"];//用户平仓价格
        $number = $data["number"];//用户平仓数量
        $position = $this->Position->where(array("order_number" => $data["order_number"], "uid" => $id))->find();
        $code = $this->Contract->where(array("code" => $position["code"]))->find();
        $user_account = $this->Useraccount->where('uid', $id)->find();
        $variety = $this->Variety->where("id", $code["variety_id"])->find();
        $bond = $code["bond"] * $number;//需要释放保证金
        $need_money = $variety["close_position_fee"];
        if ($position) {
            $po_number = $position["number"] - $position["is_close_number"];
            if ($position["trade_type"] == 2 || $position["is_close"] == 1) {
                $r = msg_handle('订单不符合平仓要求', 0);
            } else {
                if ($po_number >= $number) {

                    if ($position["trade_direct"] == 1) {//平仓为卖出

                        if ($user_price > $new_price) {//委托状态
                            $user_entrust["uid"] = $id;
                            $user_entrust["cid"] = $code["id"];
                            $user_entrust["name"] = $code["name"];
                            $user_entrust["code"] = $code["code"];
                            $user_entrust["number"] = $number;
                            $user_entrust["price"] = $user_price;
                            $user_entrust["status"] = 0;
                            $user_entrust["time"] = time();
                            $user_entrust["trade_direct"] = 2;
                            $user_entrust["trade_type"] = 2;
                            $user_entrust["total_num"] = $number;
                            $user_entrust["source"] = $data["source"];
                            $user_entrust["bond"] = 0;
                            $user_entrust["price_type"] = $data["price_type"];
                            $user_entrust["close_order_number"] = $position["order_number"];
                            $this->Entrust->startTrans();
                            $one_list = $this->Entrust->insertGetId($user_entrust);
                            if ($one_list) {//订单编号
                                $ordersn["order_number"] = "en" . $id . $code["code"] . $one_list;
                                $two_list = $this->Entrust->where('id', $one_list)->update($ordersn);
                            }

                            $is_close_number = $position["is_close_number"] + $number;
                            if ($is_close_number >= $position["number"]) {
                                $three["is_close_number"] = $is_close_number;
                                $three["is_close"] = 1;
                            } else {
                                $three["is_close_number"] = $is_close_number;
                            }
                            $three_list = $this->Position->where(array("id" => $position["id"]))->update($three);

                            if ($one_list && $two_list && $three_list) {
                                $this->Entrust->commit();
                                $r = msg_handle('添加成功', 1);
                            } else {
                                $this->Entrust->rollback();
                                $r = msg_handle('操作失败,请重试', 0);
                            }

                        } else {

                            $user_entrust["uid"] = $id;
                            $user_entrust["cid"] = $code["id"];
                            $user_entrust["name"] = $code["name"];
                            $user_entrust["code"] = $code["code"];
                            $user_entrust["number"] = $number;
                            $user_entrust["price"] = $user_price;
                            $user_entrust["status"] = 1;
                            $user_entrust["time"] = time();
                            $user_entrust["deal_time"] = time();
                            $user_entrust["trade_direct"] = 2;
                            $user_entrust["trade_type"] = 2;
                            $user_entrust["total_num"] = $number;
                            $user_entrust["source"] = $data["source"];
                            $user_entrust["bond"] = 0;
                            $user_entrust["price_type"] = $data["price_type"];
                            $user_entrust["close_order_number"] = $position["order_number"];
                            $this->Position->startTrans();
                            $one_list = $this->Position->insertGetId($user_entrust);

                            if ($one_list) {//订单编号
                                $ordersn["order_number"] = "po" . $id . $code["code"] . $one_list;
                                $two_list = $this->Position->where('id', $one_list)->update($ordersn);
                            }

                            $earning = $user_price - $position["price"];
                            $earning = $earning * $number;//收益
                            $useracc["frozen_bond"] = $user_account["frozen_bond"] - $bond;
                            $useracc["balance"] = $user_account["balance"] + $bond - $need_money + $earning;
                            $three_list = $this->Useraccount->where('uid', $user_account["uid"])->update($useracc);

                            $two_flow["uid"] = $id;
                            $two_flow["order_num"] = "po" . $id . $code["code"] . $one_list;
                            $two_flow["number"] = $need_money;
                            $two_flow["balance"] = $user_account["balance"] - $need_money;
                            $two_flow["type"] = 4;
                            $two_flow["mold"] = 1;
                            $two_flow["time"] = time();
                            $two_flow["desc"] = "平仓手续费";
                            $four_list = $this->Userflow->insertGetId($two_flow);

                            $two_flow["uid"] = $id;
                            $two_flow["order_num"] = "po" . $id . $code["code"] . $one_list;
                            $two_flow["number"] = $bond;
                            $two_flow["balance"] = $user_account["balance"] - $need_money + $bond;
                            $two_flow["type"] = 1;
                            $two_flow["mold"] = 0;
                            $two_flow["time"] = time();
                            $two_flow["desc"] = "平仓返还保证金";
                            $five_list = $this->Userflow->insertGetId($two_flow);

                            $three_flow["uid"] = $id;
                            $three_flow["order_num"] = "po" . $id . $code["code"] . $one_list;
                            $three_flow["number"] = $earning;
                            $three_flow["balance"] = $user_account["balance"] + $bond - $need_money + $earning;
                            $three_flow["type"] = 2;
                            $three_flow["mold"] = 0;
                            $three_flow["time"] = time();
                            $three_flow["desc"] = "平仓收益";
                            $six_list = $this->Userflow->insertGetId($three_flow);

                            $is_close_number = $position["is_close_number"] + $number;
                            if ($is_close_number >= $position["number"]) {
                                $three["is_close_number"] = $is_close_number;
                                $three["is_close"] = 1;
                            } else {
                                $three["is_close_number"] = $is_close_number;
                            }
                            $seven_list = $this->Position->where(array("id" => $position["id"]))->update($three);

                            if ($one_list && $two_list && $three_list && $four_list && $five_list && $six_list && $seven_list) {
                                $this->Position->commit();
                                $r = msg_handle('添加成功', 1);
                            } else {
                                $this->Position->rollback();
                                $r = msg_handle('操作失败,请重试', 0);
                            }

                        }

                    } else {//平仓为买入

                        if ($new_price > $user_price) {//委托状态

                            $user_entrust["uid"] = $id;
                            $user_entrust["cid"] = $code["id"];
                            $user_entrust["name"] = $code["name"];
                            $user_entrust["code"] = $code["code"];
                            $user_entrust["number"] = $number;
                            $user_entrust["price"] = $user_price;
                            $user_entrust["status"] = 0;
                            $user_entrust["time"] = time();
                            $user_entrust["trade_direct"] = 1;
                            $user_entrust["trade_type"] = 2;
                            $user_entrust["total_num"] = $number;
                            $user_entrust["source"] = $data["source"];
                            $user_entrust["bond"] = 0;
                            $user_entrust["price_type"] = $data["price_type"];
                            $user_entrust["close_order_number"] = $position["order_number"];
                            $this->Entrust->startTrans();
                            $one_list = $this->Entrust->insertGetId($user_entrust);
                            if ($one_list) {//订单编号
                                $ordersn["order_number"] = "en" . $id . $code["code"] . $one_list;
                                $two_list = $this->Entrust->where('id', $one_list)->update($ordersn);
                            }

                            $is_close_number = $position["is_close_number"] + $number;
                            if ($is_close_number >= $position["number"]) {
                                $three["is_close_number"] = $is_close_number;
                                $three["is_close"] = 1;
                            } else {
                                $three["is_close_number"] = $is_close_number;
                            }
                            $seven_list = $this->Position->where(array("id" => $position["id"]))->update($three);

                            if ($one_list && $two_list && $seven_list) {
                                $this->Entrust->commit();
                                $r = msg_handle('添加成功', 1);
                            } else {
                                $this->Entrust->rollback();
                                $r = msg_handle('操作失败,请重试', 0);
                            }

                        } else {

                            $user_entrust["uid"] = $id;
                            $user_entrust["cid"] = $code["id"];
                            $user_entrust["name"] = $code["name"];
                            $user_entrust["code"] = $code["code"];
                            $user_entrust["number"] = $number;
                            $user_entrust["price"] = $user_price;
                            $user_entrust["status"] = 1;
                            $user_entrust["time"] = time();
                            $user_entrust["deal_time"] = time();
                            $user_entrust["trade_direct"] = 1;
                            $user_entrust["trade_type"] = 2;
                            $user_entrust["total_num"] = $number;
                            $user_entrust["source"] = $data["source"];
                            $user_entrust["bond"] = 0;
                            $user_entrust["price_type"] = $data["price_type"];
                            $user_entrust["close_order_number"] = $position["order_number"];
                            $this->Position->startTrans();
                            $one_list = $this->Position->insertGetId($user_entrust);

                            if ($one_list) {//订单编号
                                $ordersn["order_number"] = "po" . $id . $code["code"] . $one_list;
                                $two_list = $this->Position->where('id', $one_list)->update($ordersn);
                            }


                            $earning = $position["price"] - $user_price;
                            $earning = $earning * $number;//收益
                            $useracc["frozen_bond"] = $user_account["frozen_bond"] - $bond;
                            $useracc["balance"] = $user_account["balance"] + $bond - $need_money + $earning;
                            $three_list = $this->Useraccount->where('uid', $user_account["uid"])->update($useracc);

                            $two_flow["uid"] = $id;
                            $two_flow["order_num"] = "po" . $id . $code["code"] . $one_list;
                            $two_flow["number"] = $need_money;
                            $two_flow["balance"] = $user_account["balance"] - $need_money;
                            $two_flow["type"] = 4;
                            $two_flow["mold"] = 1;
                            $two_flow["time"] = time();
                            $two_flow["desc"] = "平仓手续费";
                            $four_list = $this->Userflow->insertGetId($two_flow);

                            $two_flow["uid"] = $id;
                            $two_flow["order_num"] = "po" . $id . $code["code"] . $one_list;
                            $two_flow["number"] = $bond;
                            $two_flow["balance"] = $user_account["balance"] - $need_money + $bond;
                            $two_flow["type"] = 1;
                            $two_flow["mold"] = 0;
                            $two_flow["time"] = time();
                            $two_flow["desc"] = "平仓返还保证金";
                            $five_list = $this->Userflow->insertGetId($two_flow);

                            $three_flow["uid"] = $id;
                            $three_flow["order_num"] = "po" . $id . $code["code"] . $one_list;
                            $three_flow["number"] = $earning;
                            $three_flow["balance"] = $user_account["balance"] + $bond - $need_money + $earning;
                            $three_flow["type"] = 2;
                            $three_flow["mold"] = 0;
                            $three_flow["time"] = time();
                            $three_flow["desc"] = "平仓收益";
                            $six_list = $this->Userflow->insertGetId($three_flow);

                            $is_close_number = $position["is_close_number"] + $number;
                            if ($is_close_number >= $position["number"]) {
                                $three["is_close_number"] = $is_close_number;
                                $three["is_close"] = 1;
                            } else {
                                $three["is_close_number"] = $is_close_number;
                            }
                            $seven_list = $this->Position->where(array("id" => $position["id"]))->update($three);

                            if ($one_list && $two_list && $three_list && $four_list && $five_list && $six_list && $seven_list) {
                                $this->Position->commit();
                                $r = msg_handle('添加成功', 1);
                            } else {
                                $this->Position->rollback();
                                $r = msg_handle('操作失败,请重试', 0);
                            }

                        }


                    }


                } else {
                    $r = msg_handle('平仓手数超出最大数量', 0);
                }
            }
        } else {
            $r = msg_handle('订单不存在,无法平仓', 0);
        }
        return $r;
    }

    /**
     * 撤单操作
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function revocation_order($id, $data)
    {
//        $user = $this->User->query_info($id);
        $entrust = $this->Entrust->where(array("order_number" => $data["order_number"], "uid" => $id))->find();
        $useraccount = $this->Useraccount->where(array("uid" => $id))->find();
        if ($entrust) {
            if ($entrust["trade_type"] == 1) {//开仓委托撤单
                $contract = $this->Contract->where(array("code" => $entrust["code"]))->find();
                $bond = $contract["bond"] * $entrust["number"];//应当返还的保证金
                $this->Entrust->startTrans();
                $one_list = $this->Entrust->where(array("id" => $entrust["id"]))->update(array("status" => 2));//修改订单状态

                $news_account["frozen_bond"] = $useraccount["frozen_bond"] - $bond;
                $news_account["balance"] = $useraccount["balance"] + $bond;
                $two_list = $this->Useraccount->where(array("uid" => $useraccount["uid"]))->update($news_account);//修改订单状态

                $two_flow["uid"] = $id;
                $two_flow["order_num"] = $entrust["order_number"];
                $two_flow["number"] = $bond;
                $two_flow["balance"] = $useraccount["balance"] + $bond;
                $two_flow["type"] = 1;
                $two_flow["mold"] = 0;
                $two_flow["time"] = time();
                $two_flow["desc"] = "撤单返还保证金";
                $three_list = $this->Userflow->insertGetId($two_flow);


                if ($one_list && $two_list && $three_list) {
                    $this->Entrust->commit();
                    $r = msg_handle('撤单成功', 1);
                } else {
                    $this->Entrust->rollback();
                    $r = msg_handle('撤单失败,请重试', 0);
                }
            } else {//平仓委托撤单
                $position = $this->Position->where(array("order_number" => $entrust["close_order_number"]))->find();
                $this->Entrust->startTrans();
                $one_list = $this->Entrust->where(array("id" => $entrust["id"]))->update(array("status" => 2));
                $new_position["is_close_number"] = $position["is_close_number"] - $entrust["number"];
                if ($position["is_close"] == 1) {
                    $new_position["is_close"] = 0;
                }
                $two_list = $this->Position->where(array("order_number" => $entrust["close_order_number"]))->update($new_position);
                if ($one_list && $two_list) {
                    $this->Entrust->commit();
                    $r = msg_handle('撤单成功', 1);
                } else {
                    $this->Entrust->rollback();
                    $r = msg_handle('撤单失败,请重试', 0);
                }
            }
        } else {
            $r = msg_handle('暂无数据', 0);
        }

        return $r;
    }

    /**
     * 全部撤单
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function revocation_order_all($id, $data)
    {
//        $user = $this->User->query_info($id);
        $entrust = $this->Entrust->where(array("uid" => $id, "status" => 0))->select();
        if (count($entrust) >= 1) {
            foreach ($entrust as $k => $v) {
                $data["order_number"] = $v["order_number"];
                $this->revocation_order($id, $data);
            }
            $r = msg_handle('撤单成功', 1);
        } else {
            $r = msg_handle('暂无可撤订单', 0);
        }
        return $r;
    }

    /**
     * 计算价格
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function find_price($data)
    {
        $price = array();
        if ($data["price_type"] == 1) {//现价
            $price["buy_price"] = $data["now_price"];
            $price["close_price"] = $data["now_price"];
        } else if ($data["price_type"] == 2) {//最新价
            $price["buy_price"] = $data["now_price"];
            $price["close_price"] = $data["now_price"];
        } else if ($data["price_type"] == 3) {//对手价
            $ran = rand(1, 3);
            $price["buy_price"] = $data["now_price"] + $ran / 100;
            $price["close_price"] = $data["now_price"] - $ran / 100;
        } else if ($data["price_type"] == 4) {//快速价
            $ran = rand(1, 3);
            $price["buy_price"] = $data["now_price"] + $ran / 100;
            $price["close_price"] = $data["now_price"] - $ran / 100;
        }
        $r = msg_handle('', 1, $price);
        return $r;
    }

    /**
     * 委托处理
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function dispose_entrust($data)
    {
        $code = $data["code"];
        $price = $data["now_price"];
        $contract = $this->Contract->where(array("code" => $code))->find();
        $all_order = $this->Entrust->where(array("code" => $code, "status" => 0))->select();
        foreach ($all_order as $k => $v) {
            if ($v["trade_direct"] == 1) {//买入   客户价格需要大于行情价格

                if ($v["trade_type"] == 1) {//开仓委托
                    if ($v["price"] >= $price) {
                        $this->Entrust->startTrans();

                        $one_list = $this->Entrust->where(array("id" => $v["id"]))->update(array("status" => 1));

                        $posi["uid"] = $v["uid"];
                        $posi["cid"] = $v["cid"];
                        $posi["name"] = $v["name"];
                        $posi["code"] = $v["code"];
                        $posi["number"] = $v["number"];
                        $posi["price"] = $v["price"];
                        $posi["status"] = 1;
                        $posi["time"] = time();
                        $posi["trade_direct"] = $v["trade_direct"];
                        $posi["trade_type"] = $v["trade_type"];
                        $posi["total_num"] = $v["total_num"];
                        $posi["source"] = $v["source"];
                        $posi["bond"] = $v["bond"];
                        $posi["price_type"] = $v["price_type"];
                        $two_list = $this->Position->insertGetId($posi);

                        $order_num = "po" . $v["uid"] . $code . $two_list;
                        $three_list = $this->Position->where(array("id" => $two_list))->update(array("order_number" => $order_num));

                        if ($one_list && $two_list && $three_list) {
                            $this->Entrust->commit();
                        } else {
                            $this->Entrust->rollback();
                        }
                    }
                } else {//平仓委托
                    if ($v["price"] >= $price) {
                        $user_account = $this->Useraccount->where(array("uid" => $v["uid"]))->find();
                        $variety = $this->Variety->where(array("id" => $contract["variety_id"]))->find();
                        $buy_position = $this->Position->where(array("order_number" => $v["close_order_number"]))->find();
                        $need_money = $variety["close_position_fee"];
                        $this->Entrust->startTrans();
                        $one_list = $this->Entrust->where(array("id" => $v["id"]))->update(array("status" => 1));

                        $posi["uid"] = $v["uid"];
                        $posi["cid"] = $v["cid"];
                        $posi["name"] = $v["name"];
                        $posi["code"] = $v["code"];
                        $posi["number"] = $v["number"];
                        $posi["price"] = $v["price"];
                        $posi["status"] = 1;
                        $posi["time"] = time();
                        $posi["trade_direct"] = $v["trade_direct"];
                        $posi["trade_type"] = $v["trade_type"];
                        $posi["total_num"] = $v["total_num"];
                        $posi["source"] = $v["source"];
                        $posi["bond"] = $v["bond"];
                        $posi["price_type"] = $v["price_type"];
                        $posi["close_order_number"] = $v["close_order_number"];
                        $two_list = $this->Position->insertGetId($posi);

                        $order_num = "po" . $v["uid"] . $code . $two_list;
                        $three_list = $this->Position->where(array("id" => $two_list))->update(array("order_number" => $order_num));
                        $bond = $contract["bond"] * $v["number"];
                        $earning = $buy_position["price"] - $price;
                        $earning = $earning * $v["number"];//收益
                        $useracc["frozen_bond"] = $user_account["frozen_bond"] - $bond;
                        $useracc["balance"] = $user_account["balance"] + $bond - $need_money + $earning;
                        $four_list = $this->Useraccount->where('uid', $user_account["uid"])->update($useracc);

                        $two_flow["uid"] = $v["uid"];
                        $two_flow["order_num"] = $order_num;
                        $two_flow["number"] = $need_money;
                        $two_flow["balance"] = $user_account["balance"] - $need_money;
                        $two_flow["type"] = 4;
                        $two_flow["mold"] = 1;
                        $two_flow["time"] = time();
                        $two_flow["desc"] = "平仓手续费";
                        $five_list = $this->Userflow->insertGetId($two_flow);

                        $six_flow["uid"] = $v["uid"];
                        $six_flow["order_num"] = $order_num;
                        $six_flow["number"] = $bond;
                        $six_flow["balance"] = $user_account["balance"] - $need_money + $bond;
                        $six_flow["type"] = 1;
                        $six_flow["mold"] = 0;
                        $six_flow["time"] = time();
                        $six_flow["desc"] = "平仓返还保证金";
                        $six_list = $this->Userflow->insertGetId($six_flow);

                        $three_flow["uid"] = $v["uid"];
                        $three_flow["order_num"] = $order_num;
                        $three_flow["number"] = $earning;
                        $three_flow["balance"] = $user_account["balance"] + $bond - $need_money + $earning;
                        $three_flow["type"] = 2;
                        $three_flow["mold"] = 0;
                        $three_flow["time"] = time();
                        $three_flow["desc"] = "平仓收益";
                        $seven_list = $this->Userflow->insertGetId($three_flow);

                        $is_close_number = $buy_position["is_close_number"] + $v["number"];
                        if ($is_close_number >= $buy_position["number"]) {
                            $three["is_close_number"] = $is_close_number;
                            $three["is_close"] = 1;
                        } else {
                            $three["is_close_number"] = $is_close_number;
                        }
                        $eight_list = $this->Position->where(array("id" => $buy_position["id"]))->update($three);


                        if ($one_list && $two_list && $three_list && $four_list && $five_list && $six_list && $seven_list && $eight_list) {
                            $this->Entrust->commit();
                        } else {
                            $this->Entrust->rollback();
                        }
                    }
                }


            } else {//卖出   行情价格需要大于客户价格

                if ($v["trade_type"] == 1) {//开仓委托
                    if ($price >= $v["price"]) {
                        $this->Entrust->startTrans();

                        $one_list = $this->Entrust->where(array("id" => $v["id"]))->update(array("status" => 1));

                        $posi["uid"] = $v["uid"];
                        $posi["cid"] = $v["cid"];
                        $posi["name"] = $v["name"];
                        $posi["code"] = $v["code"];
                        $posi["number"] = $v["number"];
                        $posi["price"] = $v["price"];
                        $posi["status"] = 1;
                        $posi["time"] = time();
                        $posi["trade_direct"] = $v["trade_direct"];
                        $posi["trade_type"] = $v["trade_type"];
                        $posi["total_num"] = $v["total_num"];
                        $posi["source"] = $v["source"];
                        $posi["bond"] = $v["bond"];
                        $posi["price_type"] = $v["price_type"];
                        $two_list = $this->Position->insertGetId($posi);

                        $order_num = "po" . $v["uid"] . $code . $two_list;
                        $three_list = $this->Position->where(array("id" => $two_list))->update(array("order_number" => $order_num));

                        if ($one_list && $two_list && $three_list) {
                            $this->Entrust->commit();
                        } else {
                            $this->Entrust->rollback();
                        }
                    }
                } else {//平仓委托
                    if ($price >= $v["price"]) {
                        $user_account = $this->Useraccount->where(array("uid" => $v["uid"]))->find();
                        $variety = $this->Variety->where(array("id" => $contract["variety_id"]))->find();
                        $buy_position = $this->Position->where(array("order_number" => $v["close_order_number"]))->find();
                        $need_money = $variety["close_position_fee"];
                        $this->Entrust->startTrans();
                        $bond = $contract["bond"] * $v["number"];
                        $one_list = $this->Entrust->where(array("id" => $v["id"]))->update(array("status" => 1));

                        $posi["uid"] = $v["uid"];
                        $posi["cid"] = $v["cid"];
                        $posi["name"] = $v["name"];
                        $posi["code"] = $v["code"];
                        $posi["number"] = $v["number"];
                        $posi["price"] = $v["price"];
                        $posi["status"] = 1;
                        $posi["time"] = time();
                        $posi["trade_direct"] = $v["trade_direct"];
                        $posi["trade_type"] = $v["trade_type"];
                        $posi["total_num"] = $v["total_num"];
                        $posi["source"] = $v["source"];
                        $posi["bond"] = $v["bond"];
                        $posi["price_type"] = $v["price_type"];
                        $posi["close_order_number"] = $v["close_order_number"];
                        $two_list = $this->Position->insertGetId($posi);

                        $order_num = "po" . $v["uid"] . $code . $two_list;
                        $three_list = $this->Position->where(array("id" => $two_list))->update(array("order_number" => $order_num));

                        $earning = $price - $buy_position["price"];
                        $earning = $earning * $v["number"];//收益
                        $useracc["frozen_bond"] = $user_account["frozen_bond"] - $bond;
                        $useracc["balance"] = $user_account["balance"] + $bond - $need_money + $earning;
                        $four_list = $this->Useraccount->where('uid', $user_account["uid"])->update($useracc);

                        $two_flow["uid"] = $v["uid"];
                        $two_flow["order_num"] = $order_num;
                        $two_flow["number"] = $need_money;
                        $two_flow["balance"] = $user_account["balance"] - $need_money;
                        $two_flow["type"] = 4;
                        $two_flow["mold"] = 1;
                        $two_flow["time"] = time();
                        $two_flow["desc"] = "平仓手续费";
                        $five_list = $this->Userflow->insertGetId($two_flow);

                        $six_flow["uid"] = $v["uid"];
                        $six_flow["order_num"] = $order_num;
                        $six_flow["number"] = $bond;
                        $six_flow["balance"] = $user_account["balance"] - $need_money + $bond;
                        $six_flow["type"] = 1;
                        $six_flow["mold"] = 0;
                        $six_flow["time"] = time();
                        $six_flow["desc"] = "平仓返还保证金";
                        $six_list = $this->Userflow->insertGetId($six_flow);

                        $three_flow["uid"] = $v["uid"];
                        $three_flow["order_num"] = $order_num;
                        $three_flow["number"] = $earning;
                        $three_flow["balance"] = $user_account["balance"] + $bond - $need_money + $earning;
                        $three_flow["type"] = 2;
                        $three_flow["mold"] = 0;
                        $three_flow["time"] = time();
                        $three_flow["desc"] = "平仓收益";
                        $seven_list = $this->Userflow->insertGetId($three_flow);

                        $is_close_number = $buy_position["is_close_number"] + $v["number"];
                        if ($is_close_number >= $buy_position["number"]) {
                            $three["is_close_number"] = $is_close_number;
                            $three["is_close"] = 1;
                        } else {
                            $three["is_close_number"] = $is_close_number;
                        }
                        $eight_list = $this->Position->where(array("id" => $buy_position["id"]))->update($three);


                        if ($one_list && $two_list && $three_list && $four_list && $five_list && $six_list && $seven_list && $eight_list) {
                            $this->Entrust->commit();
                        } else {
                            $this->Entrust->rollback();
                        }
                    }
                }

            }
        }
        $this->profit_state($data);
        $this->loss_state($data);
        $r = msg_handle('执行完成', 1);
        return $r;
    }

    /**
     * 止盈止损
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function set_profit($id, $data)
    {
//        $user = $this->User->query_info($id);
        $order_number = $data["order_number"];
        $position = $this->Position->where(array("order_number" => $order_number, "uid" => $id))->find();
        if ($position) {
            if ($position["is_close"] < 1) {
                $date["profit_price"] = $data["profit_price"];
                $date["loss_price"] = $data["loss_price"];
                $list = $this->Position->where(array("id" => $position["id"]))->update($date);
                if ($list) {
                    $r = msg_handle('设置成功', 0);
                } else {
                    $r = msg_handle('设置失败', 0);
                }
            } else {
                $r = msg_handle('该订单暂时无法设置盈损', 0);
            }
        } else {
            $r = msg_handle('请确认订单', 0);
        }
        return $r;
    }


    /**
     * 处理止盈
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function profit_state($data)
    {
        $one_position = $this->Position->where(array("code" => $data["code"], "status" => 1, "trade_type" => 1, "trade_direct" => 1, "profit_price" => ["<=", $data["now_price"]]))->select();
        $three_position = $this->Position->where(array("code" => $data["code"], "status" => 1, "trade_type" => 1, "trade_direct" => 2, "profit_price" => [">=", $data["now_price"]]))->select();
        if (count($one_position) >= 1) {
            $position = array_push($one_position, $three_position);
        } else if (count($three_position) >= 1) {
            $position = $three_position;
        } else {
            $position = array();
        }
        if (count($position) >= 1) {
            foreach ($position as $k => $v) {
                $this->user_close($v["uid"], array("order_number" => $v["order_number"], "number" => $v["number"] - $v["is_close_number"], "user_price" => $v["profit_price"], "new_price" => $v["profit_price"], "source" => 4, "price_type" => 4));
            }
        }
        $r = msg_handle('执行完成', 0);
        return $r;
    }

    /**
     * 处理止损
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function loss_state($data)
    {
        $two_position = $this->Position->where(array("code" => $data["code"], "status" => 1, "trade_type" => 1, "trade_direct" => 1, "loss_price" => [">=", $data["now_price"]]))->select();
        $four_position = $this->Position->where(array("code" => $data["code"], "status" => 1, "trade_type" => 1, "trade_direct" => 2, "loss_price" => ["<=", $data["now_price"]]))->select();
        if (count($two_position) >= 1) {
            $position = array_push($two_position, $four_position);
        } else if (count($four_position) >= 1) {
            $position = $four_position;
        } else {
            $position = array();
        }
        if (count($position) >= 1) {
            foreach ($position as $k => $v) {
                $r = $this->user_close($v["uid"], array("order_number" => $v["order_number"], "number" => $v["number"] - $v["is_close_number"], "user_price" => $v["profit_price"], "new_price" => $v["profit_price"], "source" => 4, "price_type" => 4));
            }
        }
        return $r;
    }


}