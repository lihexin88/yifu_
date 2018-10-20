<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/20
 * Time: 14:47
 */

namespace app\index\controller;


use think\Controller;

//引用模型模型
use app\common\model\TradeControll as TradeControllModel;
use app\common\model\Contract;
use think\Db;


class TradeControll extends Controller
{
    /**
     * @return mixed
     */
//    主方法，显示止盈止损合约记录html
    public function index()
    {

        return $this->fetch();
    }


    /**
     *显示止盈止损合约记录
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get_trade_controll()
    {
//        未接收前台数据
        if(!$_POST){
            $r = msg_handle('接收前台数据异常',-1);
        }
//         接收单条用户数据
        if(!empty($_POST['uid'])){
            $get_user = new TradeControllModel();
            $get_user = $get_user->where(['uid'=>$_POST['uid']])->select();
        }else{
            $get_user = new TradeControllModel();
            $get_user = $get_user->select();
        }
            $this->assign('user_trade_controll',$get_user);
            return $this->fetch();
    }

    /**
     * 对前台接收到的数据进行与处理
     * @return array
     * @throws \think\exception\DbException
     */
    public function post_trade()
    {
        //验证前台数据
        if(!$_POST){
            $r = msg_handle('未收到前台数据！',-1);
            return $r;
        }
        if(!$_POST['uid']){
            $r = msg_handle('用户id错误或者不存在！请确认id',-1);
            return $r;
        }

//         获取前台传过来的合约id，在contract表中对比，不存在就返回 -1
        if(!Contract::get(['code'=>$_POST['code']])){
            $r = msg_handle('合约数据不存在！请核实！',-1);
            return $r;
        }
//          将数据写入止盈止损合约记录表
//            数据库读取对象
            $this_trade_controll = TradeControllModel::get(['uid'=>$_POST['uid'],'code'=>$_POST['code']]);
//            若存在数据，进行更新
            if($this_trade_controll){
                //声明对象
                $this_trade_controll['uid'] = $_POST['uid'];
                $this_trade_controll['code'] = $_POST['code'];
                $this_trade_controll['stop_loss'] = $_POST['stop_loss'];
                $this_trade_controll['stop_profit'] = $_POST['stop_profit'];
                $this_trade_controll['status']= $_POST['status'];
                $this_trade_controll['modify_time'] = time();
                if($this_trade_controll->save()){
                    $r = msg_handle('设置成功',1);
                }
//                进行插入操作
            }else{
                $new_controller = new TradeControllModel;
                $new_controller->uid = $_POST['uid'];
                $new_controller->code = $_POST['code'];
                $new_controller->stop_loss = $_POST['stop_loss'];
                $new_controller->stop_profit = $_POST['stop_profit'];
                $new_controller->status= $_POST['status'];
                $new_controller->modify_time = time();
                $new_controller->create_time = time();
                if($new_controller->save()){
                    $r = msg_handle('设置成功！',1);
                }
            }
            return $r;


    }
}