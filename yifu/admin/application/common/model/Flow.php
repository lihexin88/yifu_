<?php

namespace app\common\model;

use think\Model;

class Flow extends Model
{

    protected $table = 'sn_flow_log';

    public function user()
    {
        return $this->belongsTo('User', 'uid', 'id');
    }

    public function query_log($map, $page, $size)
    {
        $arr = $this->where($map)->relation(array('user'))->order('uid','asc')->page($page, $size)->select();
        
        foreach ($arr as $key => &$value) {
            //$value['time'] = detail_time($value['time']);
            switch ($value['type']) {
                case 1:
                    $value['type'] = '后台充值';
                    break;
                case 2:
                    $value['type'] = "前台充值 ";
                    break;
                case 3:
                    $value['type'] = "申请提现";
                    break;
                case 4:
                    $value['type'] = "返还综合费";
                    break;
                case 5:
                    $value['type'] = "返还保证金";
                    break;
                case 6:
                    $value['type'] = "扣综合费";
                    break;
                case 7:
                    $value['type'] = "冻结保证金";
                    break;
                case 8:
                    $value['type'] = "手续费";
                    break;
                default:
                    $value['type'] = "其他";
                    break;
            }

            if (empty($value['time'])) {
                $value['time']  = '/';
            }else{
                $value['time'] = detail_time($value['time']);
            }

            switch ($value['status']) {
                case '0':
                    $value['status'] = "未审核";
                    break;
                case '1':
                    $value['status'] = "已成功";
                    break;
                case '2':
                    $value['status'] = "未成功";
                    break;
            }
            if($value['mold'] == 1){
               $value['molds']="减钱"; 
            }else{
               $value['molds']="加钱"; 
            }
        }
        $list['data'] = $arr;
        $list['total'] = count($arr);
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

    public function query_logs($map, $page, $size)
    {
        $arr=array();
        //$all_arr = $this->where($map)->relation(array('user'))->order('time desc')->page($page, $size)->select()->toArray();
        $this->User=new User();
        $id = session('admin_id');
        $agents=$this->User->where(array("agent"=>$id))->select();
        if(isset($map['uid'])){
                $all_arr = $this->where($map)->order('id asc')->page($page, $size)->select();
                foreach($all_arr as $k=>$v){
                    $arr[]=$all_arr[$k];
                }
        }else{
            foreach($agents as $key=>$val){
                $map["uid"]=$val["id"];
                $all_arr = $this->where($map)->order('id asc')->page($page, $size)->select();
                foreach($all_arr as $k=>$v){
                        $arr[]=$all_arr[$k];
                }
            }
        }
        
        foreach ($arr as $key => &$value) {
            //$value['time'] = detail_time($value['time']);
            switch ($value['type']) {
                case 1:
                    $value['type'] = '后台充值';
                    break;
                case 2:
                    $value['type'] = "前台充值 ";
                    break;
                case 3:
                    $value['type'] = "申请提现";
                    break;
                case 4:
                    $value['type'] = "返还综合费";
                    break;
                case 5:
                    $value['type'] = "返还保证金";
                    break;
                case 6:
                    $value['type'] = "扣综合费";
                    break;
                case 7:
                    $value['type'] = "冻结保证金";
                    break;
                case 8:
                    $value['type'] = "手续费";
                    break;
                default:
                    $value['type'] = "其他";
                    break;
            }

            if (empty($value['time'])) {
                $value['time']  = '/';
            }else{
                $value['time'] = detail_time($value['time']);
            }

            switch ($value['status']) {
                case '0':
                    $value['status'] = "未审核";
                    break;
                case '1':
                    $value['status'] = "已成功";
                    break;
                case '2':
                    $value['status'] = "未成功";
                    break;
            }
            if($value['mold'] == 1){
               $value['molds']="减钱"; 
            }else{
               $value['molds']="加钱"; 
            }
        }
        $list['data'] = $arr;
        $list['total'] = count($arr);
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

    /**
     * 添加资金流水记录
     * @param $uid用户id
     * @param $number交易金额
     * @param $mold加钱减钱
     * @param $type操作类型
     * @param $balance当时余额
     * @param $agent代理id
     * @param $staff员工id
     * @return int|string
     */
    public function exit_log($uid, $number, $mold, $type, $balance, $agent, $staff, $desc = '')
    {
        $order = time() . rand(1, 9) . str_shuffle(sprintf('%05d', $uid));
        $map['uid'] = $uid;
        $map['order'] = str_shuffle($order);
        $map['number'] = $number;
        $map['balance'] = $balance;
        $map['mold'] = $mold;
        $map['type'] = $type;
        $map['agent'] = $agent;
        $map['staff'] = $staff;
        $map['time'] = time();
        $map['desc'] = $desc;
        return $this->insert($map);
    }
}
