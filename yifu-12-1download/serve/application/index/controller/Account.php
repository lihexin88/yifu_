<?php



namespace app\index\controller;



use app\index\model\UserAccount;

use app\index\model\User;

use app\index\model\UserConfig;

use app\index\model\Depot;

use app\index\model\ProfitCode;

use app\index\model\Entrust;

use app\index\model\ConditionSet;

use app\index\model\CheckParameterConfig;

use think\Controller;

use think\Request;



class Account extends Controller

{

    private $UserAccount;



    public function __construct(Request $request = null)

    {

        parent::__construct($request);

        $this->UserAccount = new UserAccount();

        $this->User = new User();

        $this->Depot = new Depot();

        $this->UserConfig = new UserConfig();

        $this->ProfitCode = new ProfitCode();

        $this->Entrust = new Entrust();

        $this->ConditionSet = new ConditionSet();

        $this->CheckParameterConfig = new CheckParameterConfig();

    }



    public function index($id)

    {

        $list = $this->UserAccount->where(array('uid' => $id))->find();

        $user = $this->User->where(array("id"=>$id))->find();

        $depot = array('profit' => 0, 'market' => 0);

        $data['total'] = num_data($list['bond'] + $list['account'] - $depot['profit']);

        $data['close_profit '] = num_data($list['profit_total']);

        $data['open_profit '] = num_data($depot['profit']);

        $data['fee '] = num_data($list['fee_total']);

        $data['frozen_bond '] = num_data($list['bond']);

        $data['account'] = num_data($list['account']);

        $data['yesterday'] = num_data($list['yesterday']);

        $data['risk_degree'] = num_data(0);

        $data['risk_control'] = num_data(0);

        $data['day_withdraw'] = num_data(0);

        $data['day_withdraw'] = num_data(0);

        $data['occupy_bond'] = num_data(0);

        $data['occupy_fee'] = num_data(0);

        $data['user_number'] = $user["number"];

        return msg_handle('', 1, $data);

    }

    /**
     * 查找用户配置
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function find_user_config($data)
    {
         $user=$this->User->where(array("token"=>$data["token"],"status"=>1))->find();
        if($user){
            $list=$this->UserConfig->where(array("uid"=>$user["id"]))->find();
            if(!$list){
                $list=$this->UserConfig->where(array("uid"=>0))->find();
            }
        }else{
            $list=$this->UserConfig->where(array("uid"=>0))->find();
        }
        $r = msg_handle('', 1,$list);
        return $r;
    }

    /**
     * 查找用户止盈止损配置
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function find_check_config($data)
    {
         $user=$this->User->where(array("token"=>$data["token"],"status"=>1))->find();
        if($user){
            $list=$this->CheckParameterConfig->where(array("uid"=>$user["id"]))->find();
            if(!$list){
                $list=$this->CheckParameterConfig->where(array("uid"=>0))->find();
            }
        }else{
            $list=$this->CheckParameterConfig->where(array("uid"=>0))->find();
        }
        $r = msg_handle('', 1,$list);
        return $r;
    }

    /**
     * 修改用户止盈止损配置
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function update_check_config($id,$data)
    {//user_config
        if($data["user_config"] == 1){
            $data["user_config"]=array();
        }
        $userconfig=$this->CheckParameterConfig->where(array("uid"=>$id))->find();
        if($userconfig){
            $list=$this->CheckParameterConfig->where(array("uid"=>$id))->update($data["user_config"]);
        }else{
            $data["user_config"]["uid"]=$id;
            $list=$this->CheckParameterConfig->insertGetId($data["user_config"]);

        }
        if($list){
            $r = msg_handle('修改成功', 1);
        }else{
            $r = msg_handle('修改失败', 0);
        }
        
        return $r;
    }

    /**
     * 修改用户配置
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function update_user_config($id,$data)
    {//user_config
            $list=array();
            $list["istrue_placeorder"]=isset($data["istrue_placeorder"])?$data["istrue_placeorder"]:"";
            $list["open_transaction_number"]=isset($data["open_transaction_number"])?$data["open_transaction_number"]:"";
            $list["customs_max_number"]=isset($data["customs_max_number"])?$data["customs_max_number"]:"";
            $list["close_number"]=isset($data["close_number"])?$data["close_number"]:"";
            $list["select_order_focus"]=isset($data["select_order_focus"])?$data["select_order_focus"]:"";
            $list["place_order_focus"]=isset($data["place_order_focus"])?$data["place_order_focus"]:"";
            $list["place_order_close"]=isset($data["place_order_close"])?$data["place_order_close"]:"";
            $list["click_market_direction"]=isset($data["click_market_direction"])?$data["click_market_direction"]:"";
            $list["order_cut_kaiping_direction"]=isset($data["order_cut_kaiping_direction"])?$data["order_cut_kaiping_direction"]:"";
            $list["order_cut_market_type"]=isset($data["order_cut_market_type"])?$data["order_cut_market_type"]:"";
            $list["list_operation_is_true"]=isset($data["list_operation_is_true"])?$data["list_operation_is_true"]:"";
            $list["double_click_order"]=isset($data["double_click_order"])?$data["double_click_order"]:"";
            $list["double_click_open_close"]=isset($data["double_click_open_close"])?$data["double_click_open_close"]:"";
            $list["double_open_price"]=isset($data["double_open_price"])?$data["double_open_price"]:"";
            $list["double_open_num"]=isset($data["double_open_num"])?$data["double_open_num"]:"";
            $list["speediness_backhand"]=isset($data["speediness_backhand"])?$data["speediness_backhand"]:"";
            $list["speediness_backhand_open_price"]=isset($data["speediness_backhand_open_price"])?$data["speediness_backhand_open_price"]:"";
            $list["speediness_locked_position"]=isset($data["speediness_locked_position"])?$data["speediness_locked_position"]:"";
            $list["speediness_locked_open_price"]=isset($data["speediness_locked_open_price"])?$data["speediness_locked_open_price"]:"";
            $list["shortcut_operation"]=isset($data["shortcut_operation"])?$data["shortcut_operation"]:"";
            $list["shortcut_operation_is_sure"]=isset($data["shortcut_operation_is_sure"])?$data["shortcut_operation_is_sure"]:"";
            $list["shortcut_buy_open"]=isset($data["shortcut_buy_open"])?$data["shortcut_buy_open"]:"";
            $list["shortcut_sell_open"]=isset($data["shortcut_sell_open"])?$data["shortcut_sell_open"]:"";
            $list["shortcut_buy_close"]=isset($data["shortcut_buy_close"])?$data["shortcut_buy_close"]:"";
            $list["shortcut_sell_close"]=isset($data["shortcut_sell_close"])?$data["shortcut_sell_close"]:"";
            $list["shortcut_operation_cancellations"]=isset($data["shortcut_operation_cancellations"])?$data["shortcut_operation_cancellations"]:"";
            $list["shortcut_operation_inventory"]=isset($data["shortcut_operation_inventory"])?$data["shortcut_operation_inventory"]:"";
            $list["shortcut_operation_buy_open"]=isset($data["shortcut_operation_buy_open"])?$data["shortcut_operation_buy_open"]:"";
            $list["shortcut_operation_sell_open"]=isset($data["shortcut_operation_sell_open"])?$data["shortcut_operation_sell_open"]:"";
            $list["shortcut_operation_buy_close"]=isset($data["shortcut_operation_buy_close"])?$data["shortcut_operation_buy_close"]:"";
            $list["shortcut_operation_sell_close"]=isset($data["shortcut_operation_sell_close"])?$data["shortcut_operation_sell_close"]:"";
            $list["message_alert_bargain"]=isset($data["message_alert_bargain"])?$data["message_alert_bargain"]:"";
            $list["message_alert_buy"]=isset($data["message_alert_buy"])?$data["message_alert_buy"]:"";
            $list["message_alert_cancellations"]=isset($data["message_alert_cancellations"])?$data["message_alert_cancellations"]:"";
            $list["message_alert_condition"]=isset($data["message_alert_condition"])?$data["message_alert_condition"]:"";
            $list["deriva_path"]=isset($data["deriva_path"])?$data["deriva_path"]:"";
            $list["berth_location"]=isset($data["berth_location"])?$data["berth_location"]:"";
            $list["show_position_wire"]=isset($data["show_position_wire"])?$data["show_position_wire"]:"";
            $list["click_market_negation"]=isset($data["click_market_negation"])?$data["click_market_negation"]:"";
        $userconfig=$this->UserConfig->where(array("uid"=>$id))->find();
        if($userconfig){
            $list=$this->UserConfig->where(array("uid"=>$id))->update($list);
        }else{
            $data["user_config"]["uid"]=$id;
            $list=$this->UserConfig->insertGetId($data["user_config"]);;
        }
        if($list){
            $r = msg_handle('修改成功', 1);
        }else{
            $r = msg_handle('修改失败', 0);
        }
        
        return $r;
    }

    /**
     * 设置止盈止损
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function set_check_full($id,$data)
    {//user_config
            $order=$this->ProfitCode->where(array("code"=>$data["code"],"uid"=>$id))->find();
            if($order){
                $list["profit_price"]=$data["profit_price"];
                $list["loss_price"]=$data["loss_price"];
                $list["code"]=$data["code"];
                $list["direction"]=$data["direction"];
                $list["uid"]=$id;
                $list["time"]=time();
                $one_list=$this->ProfitCode->where(array("id"=>$order["id"]))->update($list); 
            }else{
                $list["profit_price"]=$data["profit_price"];
                $list["loss_price"]=$data["loss_price"];
                $list["code"]=$data["code"];
                $list["direction"]=$data["direction"];
                $list["uid"]=$id;
                $list["time"]=time();
                $one_list=$this->ProfitCode->insert($list);  
            }
            if($one_list){
              $r = msg_handle('设置成功', 1); 
            }else{
              $r = msg_handle('设置失败', 0);    
            }
        
        
        
        return $r;
    }


    /**
     * 查找用户可设置止盈止损的持仓订单
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function find_user_depot($id,$data)
    {
        $order=$this->Depot->field("id,name,direction,loss,profit,status")->where(array("uid"=>$id,"status"=>"0|1"))->select();
        if(count($order) >= 1){
            foreach($order as $k=>$v){
                if($v["direction"] == 1){
                    $order[$k]["direction"]="卖";
                }else{
                    $order[$k]["direction"]="买";
                }
                $order[$k]["status"]=$this->Depot->status_name($v["status"]);
            }
            $r = msg_handle('', 1,$order);
        }else{
            $r = msg_handle('暂无数据', 0);
        }
        return $r;
    }

    /**
     * 查找用户可撤销的持仓订单
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function find_repeal_order($id,$data)
    {
        $order=$this->Entrust->where(array("uid"=>$id,"status"=>"0|1"))->select();
        if(count($order) >= 1){
            foreach($order as $k=>$v){
                if($v["direction"] == 1){
                    $order[$k]["direction"]="卖";
                }else{
                    $order[$k]["direction"]="买";
                }
                if($v["pattern"] == 1){
                    $order[$k]["pattern"]="套利";
                }else if($v["pattern"] == 2){
                    $order[$k]["pattern"]="套保";
                }else{
                    $order[$k]["pattern"]="投机";
                }

                if($v["mold"] == 1){
                    $order[$k]["mold"]="平";
                }else{
                    $order[$k]["mold"]="开";
                }
                $order[$k]["status"]=$this->Entrust->status_name($v["status"]);
                $order[$k]["time"]=date("Y-m-d H:i:s",$v["time"]);
            }
            $r = msg_handle('', 1,$order);
        }else{
            $r = msg_handle('暂无数据', 0);
        }
        return $r;
    }

    /**
     * 条件单设置
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function set_entrust_condition($id,$data)
    {
        $list["uid"]=$id;
        $list["code"]=$data["code"];
        $list["direction"]=$data["direction"];
        $list["mold"]=$data["mold"];
        $list["price_type"]=$data["price_type"];
        $list["exceed_num"]=$data["code"];
        $list["number"]=$data["number"];
        $list["condition_type"]=$data["condition_type"];
        $list["continuous_num"]=$data["continuous_num"];
        $list["continuous_eq"]=$data["continuous_eq"];
        $list["continuous_price"]=$data["continuous_price"];
        $list["continuous_choice"]=$data["continuous_choice"];
        $list["continuous_time"]=$data["continuous_time"];
        $list["continuous_cancellations_sure"]=$data["continuous_cancellations_sure"];
        $list["continuous_cancellations"]=$data["continuous_cancellations"];
        $list["continuous_cancellations_buy"]=$data["continuous_cancellations_buy"];
        $list["continuous_cancellations_buy_sure"]=$data["continuous_cancellations_buy_sure"];
        $list["continuous_cancellations_buy_num"]=$data["continuous_cancellations_buy_num"];
        $list["status"]=1;
        $list["order_sn"]=date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $order=$this->ConditionSet->insert($list);
        if($order){
            $r = msg_handle('设置成功', 1);
        }else{
            $r = msg_handle('设置失败', 0);
        }
        return $r;
    }

     /**
     * 查找条件单
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function find_entrust_condition($id)
    {
        $order=$this->ConditionSet->where(array("uid"=>$id))->select();
        if(count($order) >= 1){
            foreach($order as $k=>$v){
                if($v["direction"] == 1){
                    $order[$k]["direction"]="卖";
                }else{
                    $order[$k]["direction"]="买";
                }

                if($v["mold"] == 1){
                    $order[$k]["mold"]="平仓";
                }else{
                    $order[$k]["mold"]="开仓";
                }
                $order[$k]["status"]=$this->ConditionSet->status_name($v["status"]);
                $order[$k]["price_type"]=$this->ConditionSet->type_name($v["price_type"]);
                $order[$k]["trigger"]=$this->ConditionSet->choice_name($order[$k]);
            }
            $r = msg_handle('', 1, $order);
        }else{
            $r = msg_handle('暂无订单', 0);
        }
        return $r;
    }

    /**
     * 删除条件单
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function del_entrust_condition($id,$data)
    {
        $order=$this->ConditionSet->where(array("uid"=>$id,"id"=>$data["condition_id"]))->find();
        if($order){
            $new_order=$this->ConditionSet->where(array("uid"=>$id,"id"=>$data["condition_id"]))->update(array("status"=>4));
            if($new_order){
              $r = msg_handle('修改成功', 1);
            }else{
              $r = msg_handle('修改失败', 0);  
            }
        }else{
            $r = msg_handle('请确认订单', 0);
        }
        
        return $r;
    }

    /**
     * 删除全部条件单
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     **/
    public function del_entrust_condition_all($id)
    {
        $order=$this->ConditionSet->where(array("uid"=>$id))->select();
        if(count($order) >= 1){
            foreach($order as $k=>$v){
                $this->del_entrust_condition($id,array("condition_id"=>$v["id"]));
            }
            $r = msg_handle('修改成功', 1);
        }else{
            $r = msg_handle('暂无可删除订单', 0);
        }
        
        return $r;
    }





//合约代码

//买卖

//开平

//委托状态

//委托手数

//成交手数

//剩余手数

//冻结手续费

//冻结保证金

//状态信息

//投保

//委托时间

//报单编号

//操作员

}



























