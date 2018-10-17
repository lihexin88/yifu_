<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/12
 * Time: 8:38
 */

namespace app\index\controller;


use app\index\model\User;
use app\index\model\Agree;
use app\index\model\UserBank;
use app\index\model\Withdraw;
use think\Controller;
use think\Request;
use think\Db;

class Withdrawal extends IndexController
{
//    private $User;
//    private $Agree;
//
//提现主页
    public function index()
    {

//       $this->assign('user_info',$this->get_user_info);
        return $this->fetch('apply');
    }
//    提现主页
    public function apply()
    {
        return $this->fetch();
    }
//    显示提现记录
    public function record()
    {
        $get_all_withdraw = new Withdraw();
        $page_size = 10;
        $order_by_time['time'] = "desc";
        $user_info['uid'] = $this->user_id;
        $withdraw_log = $get_all_withdraw->where($user_info)->order($order_by_time)->paginate($page_size);
        $withdraw_page = $withdraw_log->render();
        $this->assign("withdraw_page",$withdraw_page);
        $this->assign("withdraw_log",$withdraw_log);
        $htmls = $this->fetch();
        return $htmls;
    }
    public function post_withdraw()
    {
//        查询是否绑定银行卡
        if(!UserBank::get($this->uid)){
            $r = msg_handle("暂未绑定银行卡！请绑定银行卡后操作！",-1);
            return $r;
        }
//        print_r($_POST);
//        echo "开始提现";
        $data = $_POST;
        $r = $this->do_withdraw($data);
        return $r;
    }
    private function do_withdraw($data)
    {
        $new_withdraw = new Withdraw();
        $new_withdraw->uid = $this->user_id;
        $new_withdraw->order = createOrderNum(1);
        $new_withdraw->number = $data['amount'];
        $new_withdraw->total = $data['amount'] - $this->withdraw_ratio;
        $new_withdraw->fee = $this->withdraw_ratio;
        $new_withdraw->name = UserBank::get($this->uid)['bank_name'];
        $new_withdraw->card = UserBank::get($this->uid)['bank_card'];
//        print_r($new_withdraw['card']);
//        exit();
        $new_withdraw->time = time();
        if($new_withdraw->save()){
//            echo Db::name('sn_withdraw')->getLastSql();
//            exit();
            $r = msg_handle("成功！",1);
        }else{
            $r = msg_handle("失败！",-1);
        }
        return $r;
    }
}