<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/15
 * Time: 9:49
 */

namespace app\index\controller;

//引用模型
use app\index\model\UserBank;
use app\index\model\User;
use app\index\model\Agree;
use app\index\model\Bank as BankModel;

//引用控制器等
use think\Controller;
use think\Db;
use think\Request;

class bank extends IndexController
{


    public function index()
    {
        return $this->fetch();
    }
    public function unbind()
    {
//        print_r($_POST);
//        exit;
        if(empty($_POST)){
            $r = msg_handle("传递为空！",-1);
            return $r;
        }
        $user_map['uid'] = $_POST['user_id'];
        $get_user = UserBank::get($user_map);
        if(!$get_user){
            $r = msg_handle("未查询到该用户!",-1);
            return $r;
        }
        if($get_user->delete()){
            $r = msg_handle("已成功解绑！",1);
            return $r;
            exit();
        }
        return $r = msg_handle("失败！",-1);

    }
    public function efsupportbanks(){
        $get_all_bank = BankModel::all();
        $r = msg_handle("成功！",1,$get_all_bank);
        return $r;
    }
//    查询绑定银行卡
    public function bind()
    {
        $user_info['uid'] = $this->user_id;
        $get_user_bank_info = UserBank::get($user_info);
        $this->assign("user_bank_info",$get_user_bank_info);
        return $this->fetch();
    }
//    绑定银行卡操作
    public function bind_card()
    {
        if(empty($_POST)){
            $r = msg_handle("传递数据为空！",-1);
            return $r;
        }
//        获取用户信息
        $user_info_get['id'] = $_POST['user_id'];
        $user_info = User::get($user_info_get);

//        获取银行信息
        $bank_info['name'] = $_POST['bank_name'];
        $get_bank_info = BankModel::get($bank_info);

//        print_r($user_info);
//        exit;
//        获取用户绑定银行卡信息
        $user_bank = new UserBank();
        $user_bank->uid = $user_info['id'];
        $user_bank->name = $user_info['name'];
        $user_bank->bank_id = $get_bank_info['id'];
        $user_bank->bank_name = $get_bank_info['name'];
        $user_bank->bank_card = $_POST['bank_card'];
        if($user_bank->save()){
            $r = msg_handle("绑定银行卡成功！",1);
            return $r;
        }
    }
}