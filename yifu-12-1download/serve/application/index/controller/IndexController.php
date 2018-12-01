<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/15
 * Time: 14:46
 */

namespace app\index\controller;

use app\index\model\Config;
use app\index\model\User;
use app\index\model\Agree;
use app\index\model\UserAccount;

use think\Controller;
use think\Request;

class IndexController extends Controller
{
    public $User;
    public $Agree;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
//验证是否登录
        if(User::is_user_login()){
        }else{
            $this->redirect("/index.php/login/login_index");
        }
//声明基本参数
		$this->User = new User();
		$this->Agree = new Agree();
		$this->user_id = $_SESSION['think']['id'];

//查询系统配置信息
        //充值手续费率
        $this->recharge_ratio = Config::get(1)['recharge_ratio'];
        //提现手续费率
        $this->withdraw_ratio = Config::get(1)['withdraw_ratio'];
        //人民币美元汇率
        $this->usdt_rmb = Config::get(1)['usdt_rmb'];

//查询用户信息
        $this->uid['uid'] = $this->user_id;
        $this->user_info['id'] =$this->user_id;
        $this->get_user_info = $this->User->query_info($this->user_info);
        $this->username =$this->get_user_info['name'];
        $this->assign("user_info",$this->get_user_info);

//查询用户账户信息
		$get_user_account_where['uid'] = $this->user_id;
        $get_user_account_info = UserAccount::get($get_user_account_where);
        $this->assign("user_account_info",$get_user_account_info);
    }
}