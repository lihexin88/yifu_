<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/20
 * Time: 16:47
 */

namespace app\index\controller;


use think\Controller;
//使用model
use app\common\model\UserConfig as UserConfigModel;
use think\Request;

class UserConfig extends Controller
{
    /**
     * 返回用户系统参数前端页面
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 获取用户配置信息
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function take_user_config()
    {
//        未读取到数据
        if(!$_POST){
            $r = msg_handle('前端数据错误！',-1);
            return $r;
        }
        $get_user_config = UserConfigModel::get(['uid'=>$_POST['uid']]);
        if(!$get_user_config){
            $r = msg_handle('数据库中未读取到数据！',-1);
            return $r;
        }
        $this->assign('user_config',$get_user_config);
        return $this->fetch();
    }

    /**
     * 更新用户系统设置
     * @return array
     * @throws \think\exception\DbException
     */
    public function post_user_config()
    {
//        outpause($_POST);
        $data = Request::instance('post');
//        outpause($data);
        if(!UserConfigModel::get(['uid'=>$_POST['uid']])){
            $r = msg_handle('数据库中未读取到该数据！',-1);
            return $r;
        }
        $new_user_config = UserConfigModel::get(['uid'=>$_POST['uid']]);
        $new_user_config['open_num'] = $_POST['open_num'];
        $new_user_config['decla_num'] = $_POST['decla_num'];
        $new_user_config['open_ber'] = $_POST['open_ber'];
        $new_user_config['contract'] = $_POST['contract'];
        $new_user_config['place_order'] = $_POST['place_order'];
        $new_user_config['clear'] = $_POST['clear'];
        $new_user_config['click'] = $_POST['click'];
        $new_user_config['switch'] = $_POST['switch'];
        $new_user_config['type'] = $_POST['type'];
        $new_user_config['status'] = $_POST['status'];
        try{
            if($new_user_config->save()) {
               $r = msg_handle('更新成功！',1);
            }else{
                $r = msg_handle('数据未更新！',-1);
            }
            return $r;
        }catch (\Exception $e){
            $r = msg_handle($e->getMessage(),-1);
            return $r;
        }

    }
}