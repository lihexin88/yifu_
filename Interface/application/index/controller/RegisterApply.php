<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/12
 * Time: 15:11
 */

namespace app\index\controller;


use think\Controller;
use app\index\model\RegisterApply as RegisterApplyModel;
use app\index\model\User;

use think\Request;

class RegisterApply extends Controller
{

    /**
     * 访问模板
     * @return mixed
     */
    public function register()
    {
        return $this->fetch();
    }


    /**
     * 接收前台ajax页面数据
     * @return array|\think\response\Json
     */
    public function post_register_apply()
    {
        $data = $_POST;
        $get_register_apply['phone'] = $data['phone'];
        if(User::get($get_register_apply)){
            $r = msg_handle('该手机号已被注册！',0);
            return $r;
        }
        if(empty($data)){
            $r = msg_handle("数据为空",-1);
            return $r;
        }
        $r = $this->input_register_apply($data);
        return json($r);
    }

    /**
     *  存入申请记录
     * @param $data
     * @return array
     */
    private function input_register_apply($data)
    {
//        将接收到的注册申请信息写入User表
        $input_data = new User();
        $input_data->name = $data['name'];
        $input_data->real_name = $data['real_name'];
        $input_data->type = $data['gContent'];
        $input_data->qq = $data['qq'];
        $input_data->phone = $data['phone'];
        $input_data->time = time();
        $input_data->login_ip = $_SERVER['REMOTE_ADDR'];
//        print_r($input_data);
//        exit;
//        $input_data = new RegisterApplyModel();
//        $input_data->register_apply_name = $data['name'];
//        $input_data->register_apply_qq = $data['qq'];
//        $input_data->register_apply_tel = $data['phone'];
//        $input_data->register_apply_type = $data['gContent'];
//        $input_data->register_apply_create_time = time();
        if($input_data->save()){
            $r = msg_handle("提交成功！",1,$input_data);
        }else{
            $r = msg_handle("提交失败",-1,$input_data);
        }
        return  $r;
    }


}