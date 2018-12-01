<?php



namespace app\index\controller;



use app\index\model\CapitalFlow;

use app\index\model\Contract;

use app\index\model\Entrust;

use app\index\model\Depot;

use app\index\model\ProfitCode;

use app\index\model\UserAccount;

use app\index\model\ConditionSet;

use think\Controller;

use think\Request;



class EntrustCancel extends Controller

{

    private $Entrust;

    private $UserAccount;

    private $CapitalFlow;



    public function __construct(Request $request = null)

    {

        parent::__construct($request);

        $this->Entrust = new Entrust();

        $this->UserAccount = new UserAccount();

        $this->Contract = new Contract();

        $this->ProfitCode = new ProfitCode();

        $this->Depot = new Depot();

        $this->Trade = new Trade();

        $this->CapitalFlow = new CapitalFlow();

        $this->ConditionSet = new ConditionSet();

    }



    /**

     * 取消全部订单

     * @param $id

     * @return array

     */

    public function index($id)

    {

        $list = $this->Entrust->where(array('uid' => $id, 'status' => array('in', array(0, 1))))->select();

        foreach($list as $k=>$v){
            $data=array("order_id"=>$v["order"]);
            $this->cancel_order($id, $data);
        }

        return msg_handle('撤单成功', 1);

    }



    /**

     * 取消委托

     * @param $id  int

     * @param $data  array

     * @return array

     */

    public function cancel_order($id, $data)

    {

        // $order = empty($data['order']) ? '' : $data['order'];

        $order=$this->Entrust->where(array("order"=>$data["order_id"]))->find();

        if (empty($order)) {

            $r = msg_handle('请选择撤销合约', 0);

        } else {

            $map = array('id' => $order["id"], 'uid' => $id, 'status' => array('in', array(0, 1)));

            $list = $this->Entrust->where($map)->find();

            if (empty($list)) {

                $r = msg_handle('无法撤销此合约', 0);

            } else {

                if ($order['mold'] == 0) {

                    $r = $this->open_handle($id, $order);

                } else {

                    $r = $this->close_handle($id, $order);

                }

            }

        }

        return $r;

    }

    /**

     * 撤销平仓处理

     * @param $id

     * @param $data

     * @return array

     */

    public function close_handle($id, $data)

    {
        $order=$this->Entrust->where(array("id"=>$data["id"]))->find();
        $useraccount=$this->UserAccount->where(array("uid"=>$order["uid"]))->find();
        $residue_number=$data["number"]-$data["finish"]-$data["cancel"];
        $fee=$residue_number*$order["fee_ratio"];
        $balance=$useraccount["account"]+$fee;

        $this->UserAccount->startTrans();

        $res1 = $this->UserAccount->cancel_entrust($useraccount, 0, $fee);//返回手续费
        $res2 = $this->CapitalFlow->add_log($id, $fee, $balance, 5, $useraccount);//生成返回手续费记录
        $res3 = $this->Entrust->sell_revocation($order);
        $res4 = $this->Depot->recover_position($order);

        if ($res1 && $res2 && $res3 && $res4) {

            $this->UserAccount->commit();
            $r = msg_handle('撤销委托成功', 1);

        } else {

            $this->UserAccount->rollback();
            $r = msg_handle('撤销委托失败', 0);

        }

        return $r;

    }



    /**

     * 撤销开仓处理

     * @param $id

     * @param $data

     * @return array

     */

    public function open_handle($id, $data)

    {
        $residue_number=$data["number"]-$data["finish"]-$data["cancel"];

        $fee = $residue_number * $data['fee_ratio'];

        $bond = $residue_number * $data['bond_ratio'];

        $account = $this->UserAccount->where(array('uid' => $data['uid']))->find();

        $balance = $account['account'] + $bond;

        $balance1 = $balance + $fee;
        

        $this->UserAccount->startTrans();

         $res1 = $this->UserAccount->cancel_entrust($account, $bond, $fee);
         // $r = msg_handle('撤销委托失败', 0,$res1);

        $res2 = $this->CapitalFlow->add_log($id, $bond, $balance, 4, $account);

        $res3 = $this->CapitalFlow->add_log($id, $fee, $balance1, 5, $account);

        $res4 = $this->Entrust->cancel_entrust($data);

        if ($res1 && $res2 && $res3 && $res4) {

            $this->UserAccount->commit();
            $r = msg_handle('撤销委托成功', 1);

        } else {

            $this->UserAccount->rollback();
            $r = msg_handle('撤销委托失败', 0);

        }

        return $r;

    }

    //执行委托转换交易进程(计划任务)
    public function stock_price($code,$price){
        $contract=$this->Contract->where(array("code"=>$code))->find();
        $buy_order=$this->Entrust->where(array("code"=>$code,"direction"=>0,"price"=>["<=",$price],"status"=>"0|1|4"))->select();//买入委托
        foreach($buy_order as $k=>$v){
            if($v["mold"] < 1){
                $this->buy_position($v["id"]);//开仓买
            }else{
                $this->sell_position($v["id"]);//平仓买入
            }
        }

        $sell_order=$this->Entrust->where(array("code"=>$code,"direction"=>1,"price"=>[">=",$price],"status"=>"0|1|4"))->select();//卖出委托
        foreach($sell_order as $k=>$v){
            if($v["mold"] < 1){
                $this->buy_position($v["id"]);//开仓卖出
            }else{
                $this->sell_position($v["id"]);//平仓卖出
            }
        }

        $r = msg_handle('执行完成', 1);
        return $r;
    }

    public function buy_position($id){//开仓处理
        $order=$this->Entrust->where(array("id"=>$id))->find();
        if($order){
            $data=array();
            $number=$order["number"]-$order["finish"]-$order["cancel"];
            $one_data["finish"]=$order["finish"]+$number;
            $one_data["status"]=2;
            $profitcode=$this->ProfitCode->where(array("uid"=>$order["uid"],"code"=>$order["code"],"direction"=>$order["direction"]))->find();
            if($profitcode){
                $loss_price=$profitcode["loss_price"];
                $profit_price=$profitcode["profit_price"];
            }else{
                $loss_price=0;
                $profit_price=0;
            }
            $this->Entrust->startTrans();
            $one_list=$this->Entrust->where(array("id"=>$order["id"]))->update($one_data);
            if($order["direction"] == 1){//卖出
                $two_list=$this->Depot->add_log($order["uid"],$order["short"],$order["code"],$order["name"],$number,$order["price"],$order["average"],$order["cost"],$order["direction"],$order["pattern"],time(),$order["bond_ratio"]*$number,$order["fee_ratio"]*$number,0,$order["price"]*$number,$profit_price,$loss_price);
            }else{//买入
                $two_list=$this->Depot->add_log($order["uid"],$order["short"],$order["code"],$order["name"],$number,$order["price"],$order["average"],$order["cost"],$order["direction"],$order["pattern"],time(),$order["bond_ratio"]*$number,$order["fee_ratio"]*$number,$order["price"]*$number,0,$profit_price,$loss_price);
            }
            if($one_list && $two_list){
                $this->Entrust->commit();
            }else{
                $this->Entrust->rollback();
            }
        }
    }

    public function sell_position($id,$price=0){//平仓处理
        $order=$this->Entrust->where(array("id"=>$id))->find();
        if($order){
            if($price < 1){
                $price=$order["price"];
            }
            $depot=$this->Depot->where(array("id"=>$order["depot_id"]))->find();
            $data=array();
            $number=$order["number"]-$order["finish"]-$order["cancel"];
            $one_data["finish"]=$order["finish"]+$number;
            $one_data["status"]=2;

            $this->Entrust->startTrans();
            $one_list=$this->Entrust->where(array("id"=>$order["id"]))->update($one_data);
            $two_list=$this->Depot->update_sell($order["depot_id"],$number,$price);

            if($two_list > 0){
                $user_list=$this->UserAccount->where(array("uid"=>$order["uid"]))->find();
                $order_bond=$order["bond_ratio"]*$number;
                $three_list=$this->UserAccount->account_profit($user_list,$two_list,$order_bond);
                $balance=$user_list["account"]+$two_list;
                $two_balance=$balance+$order_bond;
                $four_list = $this->CapitalFlow->add_log($order["uid"], $two_list, $balance, 6, $user_list);//计算盈亏
                $five_list = $this->CapitalFlow->add_log($order["uid"], $order_bond, $two_balance, 7, $user_list);//计算盈亏
            }

            if($one_list && $two_list && $three_list && $four_list && $five_list){
                $this->Entrust->commit();
            }else{
                $this->Entrust->rollback();
            }

        }
    }
    /*
     *计划任务    处理止盈止损
     */
    public function dispose_profit_loss($data){
        if($data["price"] > 0){
            $one_order=$this->Depot->where(array("code"=>$data["code"],"direction"=>0,"status"=>["<","2"],"profit"=>["<=",$data["price"]]))->select();//开仓买止盈
            if($one_order){
                foreach($one_order as $k=>$v){
                    $one_order[$k]["profit_loss"]="5";//止盈
                    $one_order[$k]["profit_price"]=$v["profit"];//止盈
                    $one_order[$k]["can_num"]=$v["number"]-$v["frozen"]-$v["finish"];//可用数量
                }
            }

            $two_order=$this->Depot->where(array("code"=>$data["code"],"direction"=>0,"status"=>["<","2"],"loss"=>[">=",$data["price"]]))->select();//开仓买止盈
            if($two_order){
                foreach($two_order as $k=>$v){
                    $two_order[$k]["profit_loss"]="6";//止损
                    $two_order[$k]["profit_price"]=$v["loss"];//止盈
                    $two_order[$k]["can_num"]=$v["number"]-$v["frozen"]-$v["finish"];//可用数量
                }
            }

            $three_order=$this->Depot->where(array("code"=>$data["code"],"direction"=>1,"status"=>["<","2"],"profit"=>[">=",$data["price"]]))->select();//开仓卖止盈
            if($three_order){
                foreach($three_order as $k=>$v){
                    $three_order[$k]["profit_loss"]="5";//止盈
                    $three_order[$k]["profit_price"]=$v["profit"];//止盈
                    $three_order[$k]["can_num"]=$v["number"]-$v["frozen"]-$v["finish"];//可用数量
                }
            }

            $four_order=$this->Depot->where(array("code"=>$data["code"],"direction"=>1,"status"=>["<","2"],"loss"=>["<=",$data["price"]]))->select();//开仓卖止盈
            if($four_order){
                foreach($four_order as $k=>$v){
                    $four_order[$k]["profit_loss"]="6";//止损
                    $four_order[$k]["profit_price"]=$v["loss"];//止盈
                    $four_order[$k]["can_num"]=$v["number"]-$v["frozen"]-$v["finish"];//可用数量
                }
            }
            $order=$this->myMerge($one_order,$two_order);
            $order=$this->myMerge($order,$three_order);
            $order=$this->myMerge($order,$four_order);
            foreach($order as $k=>$v){

                $data=array("code"=>$v["code"],"pattern"=>0,"direction"=>$v["direction"]==1?0:1,"mode"=>1,"price"=>$v["profit_price"],"classify"=>$v["profit_loss"],"number"=>$v["can_num"],"order_id"=>$v["id"]);
                $new_r=$this->Trade->news_index($v['uid'], $data);
                if($new_r["code"] > 0){
                    $new_order=$this->Entrust->where(array("depot_id"=>$v["id"]))-> order(array('id' => 'desc')) ->find();
                    $new_rs=$this->sell_position($new_order["id"]);
                }

            }
            $r = msg_handle('执行完成', 1,$new_rs);
        }
        return $r;
    }


    public function myMerge($a,$c){
        $number=count($a);
        foreach($c as $k=>$v){
            $number=$number+1;
            $a[$number]=$c[$k];
        }
        return $a;
    }

    /**
     * 查找符合要求的条件单(计划任务)
     * @param $data
     * @return array
     */
    public function find_condition_setuse($data,$or_id=0){
        if($or_id > 0){
            $code=$data["code"];
            $price=$data["price"];
            $order=$this->ConditionSet->where(array("id"=>$or_id))->select();
        }else{
            $code=$data["code"];
            $price=$data["price"];
            $time=time();
            $one_new=$this->ConditionSet->where(array("code"=>$code,"continuous_choice"=>1,"status"=>1,"continuous_eq"=>1,"continuous_price"=>[">=",$price]))->select();
            $two_new=$this->ConditionSet->where(array("code"=>$code,"continuous_choice"=>1,"status"=>1,"continuous_eq"=>2,"continuous_price"=>["=",$price]))->select();
            $three_new=$this->ConditionSet->where(array("code"=>$code,"continuous_choice"=>1,"status"=>1,"continuous_eq"=>3,"continuous_price"=>["<=",$price]))->select();
            $four_new=$this->ConditionSet->where(array("code"=>$code,"continuous_choice"=>2,"status"=>1,"continuous_time"=>["<=",$time]))->select();//按时间分配
            $order=$this->myMerge($one_new,$two_new);
            $order=$this->myMerge($order,$three_new);
            $order=$this->myMerge($order,$four_new); 
        }
        if(count($order) > 0){
            foreach($order as $k=>$v){
                if($v["continuous_choice"] == 1){//大小条件单
                    if($v["mold"] == 1){//平仓
                        $d_order=$this->Depot->where(array("code"=>$code,"status"=>["<",2]))->order('id', 'desc')->find();
                        if($d_order){

                            $data=array("code"=>$code,"pattern"=>0,"direction"=>$d_order["direction"],"mode"=>1,"price"=>$v["continuous_price"],"classify"=>$v["price_type"],"number"=>$d_order["number"]-$d_order["frozen"]-$d_order["finish"],"order_id"=>$d_order["id"]);
                            $new_r=$this->Trade->news_index($d_order['uid'], $data);
                            if($new_r["code"] == 1){
                                $this->ConditionSet->where(array("id"=>$v["id"]))->update(array("status"=>2,"finish_time"=>time()));
                            }else{
                                $this->ConditionSet->where(array("id"=>$v["id"]))->update(array("status"=>3,"finish_time"=>time()));
                            }

                        }else{
                            $this->ConditionSet->where(array("id"=>$v["id"]))->update(array("status"=>3,"finish_time"=>time()));
                        }
                    }else{//开仓
                       $data=array("code"=>$v["code"],"pattern"=>0,"direction"=>$v["direction"],"mode"=>0,"price"=>$v["continuous_price"],"classify"=>$v["price_type"],"number"=>$v["number"]);
                        $new_r=$this->Trade->news_index($v['uid'], $data);
                            if($new_r["code"] == 1){
                                $this->ConditionSet->where(array("id"=>$v["id"]))->update(array("status"=>2,"finish_time"=>time()));
                            }else{
                                $this->ConditionSet->where(array("id"=>$v["id"]))->update(array("status"=>3,"finish_time"=>time()));
                            } 
                    }
                }else{//时间条件单
                    if($v["mold"] == 1){//平仓
                        $d_order=$this->Depot->where(array("code"=>$code,"status"=>["<",2]))->order(array('id' => 'desc'))->find();
                        if($d_order){

                            $data=array("code"=>$code,"pattern"=>0,"direction"=>$d_order["direction"]==1?0:1,"mode"=>1,"price"=>$v["continuous_price"],"classify"=>$v["price_type"],"number"=>$d_order["number"]-$d_order["frozen"]-$d_order["finish"],"order_id"=>$d_order["id"]);
                            $new_r=$this->Trade->news_index($d_order['uid'], $data);
                            if($new_r["code"] == 1){
                                $this->ConditionSet->where(array("id"=>$v["id"]))->update(array("status"=>2,"finish_time"=>time()));
                            }else{
                                $this->ConditionSet->where(array("id"=>$v["id"]))->update(array("status"=>3,"finish_time"=>time()));
                            }

                        }else{
                            $this->ConditionSet->where(array("id"=>$v["id"]))->update(array("status"=>3,"finish_time"=>time()));
                        }
                    }else{//开仓
                        $data=array("code"=>$v["code"],"pattern"=>0,"direction"=>$v["direction"],"mode"=>0,"price"=>$v["continuous_price"],"classify"=>$v["price_type"],"number"=>$v["number"]);
                        $new_r=$this->Trade->news_index($v['uid'], $data);
                            if($new_r["code"] == 1){
                                $this->ConditionSet->where(array("id"=>$v["id"]))->update(array("status"=>2,"finish_time"=>time()));
                            }else{
                                $this->ConditionSet->where(array("id"=>$v["id"]))->update(array("status"=>3,"finish_time"=>time()));
                            } 
                    }
                }
            }
        }
        $r = msg_handle('执行完成', 1);
        return $r;
    }

    /**
     * 处理条件单
     * @param $data2
     * @return array
     */
    public function alone_condition_setuse($id,$data){
        $order_id=$data["order_id"];
        $order=$this->ConditionSet->where(array("id"=>$order_id,"uid"=>$id))->find();
        if($order){
            $data["code"]=$order["code"];
            $data["price"]=$order["continuous_price"];
            $this->find_condition_setuse($data,$order_id);
            $r = msg_handle('执行完成',1);
        }else{
            $r = msg_handle('请确认订单',0); 
        }
        return $r; 
    }

    /**
     * 查找用户最早的一个可平仓订单
     * @param $data2
     * @return array
     */
    public function user_close_order($id,$data){
        $code=$data["code"];
        $order=$this->Depot->where(array("code"=>$code,"uid"=>$id,"status"=>["<",2]))->order("id","asc")->find();
        if($order){
            $list=array("id"=>$order["id"],"direction"=>$order["direction"],"number"=>$order["number"]-$order["frozen"]-$order["finish"]);
            $r = msg_handle('执行完成',1,$list);
        }else{
            $r = msg_handle('暂无订单',0); 
        }
        return $r; 
    }




}























































































