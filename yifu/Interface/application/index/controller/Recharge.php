<?php

namespace app\index\controller;


use app\index\model\Recharge as RechargeModel;

use app\index\model\Config;

use app\index\model\UserAccount;
use think\Controller;
use think\Request;
use think\Db;

class Recharge extends IndexController
{

//    充值主页
    public function index(){

//        print_r($_SESSION);
      return $this->fetch();

    }
//    显示入金记录
    public function record()
    {
        $all_record = new RechargeModel();
        $page_size = 10;
        $user_info['uid'] = $this->user_id;
        $order_by_time['time'] = "desc";
        $get_record = $all_record->where($user_info)->order($order_by_time)->paginate($page_size);
        $get_record_page = $get_record->render();
        $this->assign("record_page",$get_record_page);
        $this->assign("record",$get_record);
        $htmls = $this->fetch();
        return $htmls;
//        return $this->fetch();
    }
//显示线下入金记录
   public function offlinetransferrecord()
   {
        $all_record = new RechargeModel();
        $page_size = 10;
//        交易类型为3（线下交易）
        $offline['pay_type'] = '3';
        $offline['uid'] = $this->user_id;
        $order_by_time['time'] = "desc";
        $get_offline_transfer_record = $all_record->order($order_by_time)->where($offline)->paginate($page_size);
        $offline_page = $get_offline_transfer_record->render();
        $this->assign("offline_page",$offline_page);
        $this->assign("offline_recharge",$get_offline_transfer_record);
        $htmls = $this->fetch();
        return $htmls;
//        echo Db::name("sn_recharge")->getLastSql();
//        print_r($get_offline_transfer_record);
//        exit;
//       return $this->fetch();
   }
//   充值
   public function post_recharge()
   {
//       print_r($_POST);
        $data = $_POST;
//        exit;
        $r = $this->do_recharge($data);
        return json($r);
   }
//   获取汇率,充值提现手续费
   public function get_config()
   {
        //人民币美元汇率
        $data['usdt_rmb'] = $this->usdt_rmb;
        //提现手续费
        $data['withdraw_ratio'] = $this->withdraw_ratio;
        //充值手续费
       $data['recharge_ratio'] = $this->recharge_ratio;
        if($data){
           $r = msg_handle("",1,$data);
           return $r;
       }
   }
   private function do_recharge($data)
   {
//        $data['pay_type'] = substr($data['pay_type'],strpos($data['pay_type'],'='),strpos($data['pay_type'],'&'));
//        print_r($data);
        $get_rmbt_usd = $this->usdt_rmb;
//        print_r($get_rmbt_usd);
//       声明美金
        $usd = 0;
//       声明手续费
        $exchange_fee = 0;
        if($data['pay_type'] == 1){
            $exchange_fee = $data['amount']*$this->recharge_ratio;
        }else if($data['pay_type'] == 3){
        }
//        print_r($data);
//        exit();
//        验证用户充值金额规范性
       try{
            $data['amount'] = (int) $data['amount'];
       }catch (\Exception $e){
            $r = msg_handle($e->getMessage(),-1);
            return $r;
       }
       if(!(is_int($data['amount']) && $data['amount'] > 0)){
            $r = msg_handle('入金数据错误！',-1);
            return $r;
       }

       $data['amount'] -= $exchange_fee;
       $usd = $data['amount']/$get_rmbt_usd;

        $new_recharge = new RechargeModel();
        $new_recharge->uid = $this->user_id;
       //        订单编号
        $new_recharge->order = $order=createOrderNum(1);
        $new_recharge->pay_type = $data['pay_type'];
        $new_recharge->time = time();
        $new_recharge->remark = "";
        $new_recharge->number = $data['amount'];
        $new_recharge->usd = $usd;
        $new_recharge->fee = $exchange_fee;
        if($new_recharge->save()){
//           修改用户余额
            $get_user_account = UserAccount::get($this->user_id);
            $get_user_account['change_time'] = time();
            $get_user_account['balance'] +=$new_recharge['number'];
//            echo "开始显示用户帐户";
            if($get_user_account->save()){
                $r = msg_handle("成功！",1);
            }
        }else{
            $r = msg_handle("失败!",-1);
        }
       return $r;
   }


}