<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/13
 * Time: 18:29
 */

namespace app\index\controller;

use app\index\model\UserAccount;
use app\index\model\UserBank;
use app\index\model\User;
use app\index\model\Agree;
use think\Controller;
use think\Db;
use think\Request;
class Security extends IndexController
{

//    安全首页
    public function index()
    {
        $get_user_bind['uid'] = $this->user_id;
        $this->assign("user_info",$this->get_user_info);
        $this->assign("is_user_bind",UserBank::get($get_user_bind));
        return $this->fetch();
    }
//    显示修改密码
    public function loginpassword()
    {
        return $this->fetch();
    }
//    接受前台传过来的数据，进行验证并且改密码
    public function change_password()
    {
        $user_map['id'] = $_POST['user_id'];
        $user_map['password'] = md5($_POST['old_password']);
        $get_user_info = User::get($user_map);
//        echo Db::name('sn_user')->getLastSql();
//        exit;
        if(!$get_user_info){
            $r = msg_handle("原始密码错误！",0);
            return $r;
        }
        $get_user_info->password = md5($_POST['new_password']);
        if($get_user_info->save()){
            $r = msg_handle("修改成功！",1);
            return $r;
        }
    }

}