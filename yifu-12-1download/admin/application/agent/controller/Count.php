<?php

namespace app\agent\controller;

use app\agent\model\Deal;
use app\agent\model\Depot;
use app\agent\model\User;
use app\agent\model\Recharge;
use app\agent\model\Entrust;
use app\agent\model\Withdraw;
use think\Request;
use think\Session;
use think\Db;

class Count extends Common
{
        public function __construct(Request $request = null)
        {
            parent::__construct($request);
            $this->Deal = new Deal();
            $this->Depot = new Depot();
            $this->Entrust = new Entrust();
            $this->User = new User();
            $this->Recharge = new Recharge();
            $this->Withdraw = new Withdraw();
        }
        /**
         * 持仓列表
         * @return mixed
         * @throws \think\db\exception\DataNotFoundException
         * @throws \think\db\exception\ModelNotFoundException
         * @throws \think\exception\DbException
         */
        public function depot(){
            $user = session('dladmin');
            $map  =$ids= array();
            $name = trim(input('get.name'));
            if ($name) {
                $ma['name'] = $name;
            }
            $ma['agent'] = $user['id'];
            $agent = $this->User->where($ma)->select()->toArray();
            foreach ($agent as $key =>&$value) {
                array_push($ids, $value['id']);
            }
            $map['status'] = 0;
            $m['uid']=$map['uid']=['in',$ids];
            $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
            $current_page = page_judge(input('get.page'));
            $list = $this->Depot->query_log($map, $current_page, $this->num,'');
            $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
            // $bond = $this->Deal->where($map)->sum('bond');
            // $this->assign('bond', $bond);
            $this->assign('arr', $this->arr_info(input('get.')));
            $this->assign('empty', $this->null_html(12));
            $this->assign('page', $page);
            $this->assign('list', $list['data']);
            return $this->fetch();
        }
        /**
         * 委托记录
         * @return mixed
         * @throws \think\db\exception\DataNotFoundException
         * @throws \think\db\exception\ModelNotFoundException
         * @throws \think\exception\DbException
         */
        public function entrust(){
            $user = session('dladmin');
            $map  =$ids= array();
            $name = trim(input('get.name'));
            if ($name) {
                $ma['name'] = $name;
            }
            $ma['agent'] = $user['id'];
            $agent = $this->User->where($ma)->select()->toArray();
            foreach ($agent as $key =>&$value) {
                array_push($ids, $value['id']);
            }
            $m['uid']=$map['uid']=['in',$ids];
            $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
            $current_page = page_judge(input('get.page'));
            $list = $this->Entrust->query_log($map, $current_page, $this->num,'');
            $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
            // $bond = $this->Deal->where($map)->sum('bond');
            // $this->assign('bond', $bond);
            $this->assign('arr', $this->arr_info(input('get.')));
            $this->assign('empty', $this->null_html(12));
            $this->assign('page', $page);
            $this->assign('list', $list['data']);
            return $this->fetch();
        }
        /**
         * 交易记录
         * @return mixed
         * @throws \think\db\exception\DataNotFoundException
         * @throws \think\db\exception\ModelNotFoundException
         * @throws \think\exception\DbException
         */
        public function deal(){
            $user = session('dladmin');
            $map  =$ids= array();
            $name = trim(input('get.name'));
            if ($name) {
                $ma['name'] = $name;
            }
            $ma['agent'] = $user['id'];
            $agent = $this->User->where($ma)->select()->toArray();
            foreach ($agent as $key =>&$value) {
                array_push($ids, $value['id']);
            }
            $m['uid']=$map['uid']=['in',$ids];
            $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
            $current_page = page_judge(input('get.page'));
            $list = $this->Deal->query_log($map, $current_page, $this->num,'');
            $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
            // $bond = $this->Deal->where($map)->sum('bond');
            // $this->assign('bond', $bond);
            $this->assign('arr', $this->arr_info(input('get.')));
            $this->assign('empty', $this->null_html(12));
            $this->assign('page', $page);
            $this->assign('list', $list['data']);
            return $this->fetch();
        }

        /**
         * 充值列表
         * @return mixed
         * @throws \think\db\exception\DataNotFoundException
         * @throws \think\db\exception\ModelNotFoundException
         * @throws \think\exception\DbException
         */
        public function recharge()
        {
            $user = session('dladmin');
            $map  =$ids= array();
            $name = trim(input('get.name'));
            if ($name) {
                $ma['name'] = $name;
            }
            $ma['agent'] = $user['id'];
            $agent = $this->User->where($ma)->select()->toArray();
            foreach ($agent as $key =>&$value) {
                array_push($ids, $value['id']);
            }
            $m['uid']=$map['uid']=['in',$ids];
            $map = $this->query_time1($map, input('get.start_query'), input('get.end_query'));
            $current_page = page_judge(input('get.page'));
            $list = $this->Recharge->query_log($map, $current_page, $this->num);
    //        return json($list);exit();
            $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
            $re_num = $this->Recharge->where($map)->sum("number");
            $this->assign('re_num', $re_num);
            $this->assign('arr', $this->arr_info(input('get.')));
            $this->assign('empty', $this->null_html(12));
            $this->assign('page', $page);
            $this->assign('list', $list['data']);
            return $this->fetch();
        }
         /**
         * 提现列表
         * @return mixed
         * @throws \think\db\exception\DataNotFoundException
         * @throws \think\db\exception\ModelNotFoundException
         * @throws \think\exception\DbException
         */
        public function withdraw()
        {
            $user = session('dladmin');
            $map  =$ids= array();
            $name = trim(input('get.name'));
            if ($name) {
                $ma['name'] = $name;
            }
            $ma['agent'] = $user['id'];
            $agent = $this->User->where($ma)->select()->toArray();
            foreach ($agent as $key =>&$value) {
                array_push($ids, $value['id']);
            }
            $m['uid']=$map['uid']=['in',$ids];
            $map = $this->query_time1($map, input('get.start_query'), input('get.end_query'));
            $current_page = page_judge(input('get.page'));
            $list = $this->Withdraw->query_log($map, $current_page, $this->num);
            $map['status'] = 1;
            $sum['withdraw_num'] = $this->Withdraw->where($map)->sum('number');//提现申请总和
            $sum['fee_num'] = $this->Withdraw->where($map)->sum('fee');//手续费总和
            $sum['num'] = $sum['withdraw_num'] - $sum['fee_num'];//到账总和
            $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
            $this->assign('sum',$sum);
            $this->assign('arr', $this->arr_info(input('get.')));
            $this->assign('empty', $this->null_html(12));
            $this->assign('page', $page);
            $this->assign('list', $list['data']);
            return $this->fetch();
        }

    /**
     * 当日成交
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function today_deal()
    {
        $dladmin = session('dladmin');
        $map['reid']= $dladmin['id'];
        $map = $start=$end="";
        $name = trim(input('get.name'));
        $order = trim(input('get.order'));
        $end = input('get.end_query');
        if($start){
            $start = input('get.start_query');
        }else{
            $start = date('Y-m-d', time());
        }
        if($end){
            $end = input('get.end_query');
        }else{ 
            $end = date('Y-m-d H:i:s', time());
        }
        if ($name) {
             $map_user['real_name|name|phone|id'] = $name;
             $user = $this->User->where($map_user)->find();
             if ($user) {
                 $map['uid'] = $user['id'];
             } else {
                 $map['order'] = $name;
             }
        }
        if ($order){
            $map['reid'] = $order;
        }
        $map = $this->query_time1($map, $start, $end);
        $map = $this->query_time1($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Deal->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $num = $this->Deal->where($map)->sum("number");
        $fee = $this->Deal->where($map)->sum("fee");
        $profit_loss = $this->Deal->where($map)->sum("profit_loss");
        $this->assign('num', $num);
        $this->assign('profit_loss', $profit_loss);
        $this->assign('fee', $fee);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

   
/**
     * @return mixed
     * 修改
     */
    public function edits()
    {
        $id = input('id');
        $list = $this->Deal->where('id=' . $id)->find();
        if (empty($list['time'])) {
            $list['time'] = '';
        }else{
            $list['time'] = date("Y-m-d H:i:s",$list['time']);
        }
        if (empty($list['clear_time'])) {
            $list['clear_time'] = '';
        }else{
            $list['clear_time'] = date("Y-m-d H:i:s",$list['clear_time']);
        }
        $this->assign('list', $list);
        return $this->fetch();
    }
    /**
     * 修改订单
     * @return \think\response\Json
     */
    public function time_edit()
    {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            $data['time'] = strtotime($data['time']);
            $data['clear_time'] = strtotime($data['clear_time']);
            if (empty($data['time'])) {
                $r = msg_handle('请输入下单时间', 0);
                return $r;
            } elseif (empty($data['clear_time'])) {
                $r = msg_handle('请输入结算时间', 0);
                return $r;
            } elseif (empty($data['buy_price'])) {
                $r = msg_handle('请输入成交价格', 0);
                return $r;
            } elseif (empty($data['selling_price'])) {
                $r = msg_handle('请输入结算价格', 0);
                return $r;
            } else {
                $data['status'] = 1;
                $list = $this->Deal->update($data);
                if ($list) {
                    //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                    $this->success('操作成功', 'Count/order');
                } else {
                    //错误页面的默认跳转页面是返回前一页，通常不需要设置
                    $this->error('操作失败');
                }
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return json($r);
    }
    /**
     * 设置盈亏
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit()
    {
        $id = $_POST['id'];
        $status = $_POST['status'];
        if (empty($id)) {
            $r = msg_handle('操作失败', 0);
        } else {
            $order = $this->Deal->find($id);
            if ($order['status'] == 1) {
                $r = msg_handle('订单已结算,无法设置', 0);
                return json($r);
            }
            $data['is_win'] = $status;
            $res = $this->Deal->where('id', $id)->update($data);
            if ($res) {
                $r = msg_handle('操作成功', 1);
            } else {
                $r = msg_handle('操作失败', 0);
            }
        }
        return json($r);
    }
    
       
    /**
     * 处理提现申请 同意OR拒绝
     * @throws \Exception
     */
    public function modify_withdrawals()
    {
        $id = $_POST['id'];
        $edit_type = $_POST['edit_type'];
        if (!$id) {
            $this->redirect('Count/withdrawals');
        }
        $info = $this->Withdraw->where('id=' . $id)->find();
        $user = $this->User->where('id', $info['uid'])->field('id,real_name')->find();
        $user_bank = $this->UserBanks->where('uid', $info['uid'])->find();
        if (empty($user['real_name']) || empty($user_bank['bank_card'])) {
            $r = msg_handle('用户信息有误,请核对后重试!', 0);
        } else {
            $account = $this->UserAccount->where('uid',$info['uid'])->find();
            if ($edit_type == 1) {
                $r = $this->agree_data($account, $info, $user, $user_bank);
            } else {
                $r = $this->reject_data($account, $info);
            }
        }
        return json($r);
    }
}
