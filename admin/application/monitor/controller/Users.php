<?php

namespace app\monitor\controller;

use think\Request;
use app\common\model\User;
use app\common\model\Agent;
use app\common\model\Miner;
use app\common\model\Account;
use app\common\model\Flow;
use app\common\model\Recharge;


class Users extends Common
{

    private $Users;
    private $Miner;
    private $Account;
    private $Recharge;
    private $Flow;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->User = new User();
        $this->Miner = new Miner();
        $this->Account = new Account();
        $this->Recharge = new Recharge();
        $this->Flow = new Flow();
        $this->Agent = new Agent();

    }
    
    /*
     *会员列表
     */
    public function index()
    {
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['real_name|phone'] = ['like', $name];
        }
        $admin_id = session('admin_id');
        $map["agent"]=$admin_id;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->User->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $sum=0;
        foreach($list["data"] as $k=>$v){
            $sum+=$v["account"]["account"];
        }
        //$sum = $this->Account->sum('account');
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        $this->assign('sum', $sum);
        return $this->fetch();
    }

     /*
      *用户资金流水
      */
    public function user_flowlog()
    {
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name|phone'] = ['like', $name];
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Flow->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $sum['reduce'] = $this->Flow->where('mold', 0)->sum('number');
        $sum['increase'] = $this->Flow->where('mold', 1)->sum('number');
// dump($sum);exit;

        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        $this->assign('sum', $sum);
        return $this->fetch();
    }

    public function edit()
    {
        $id = Request::instance()->param('id');
        if (empty($id)) {
            $r = msg_handle('操作失败', 0);
        } else {
            $user = $this->User->find($id);
            if ($user['status'] != 1) {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }
            $data['modify_time'] = time();
            $res = $this->User->where('id',$id)->update($data);
            if ($res) {
                $r = msg_handle('操作成功', 1);
            } else {
                $r = msg_handle('操作失败', 0);

            }
        }
        return json($r);
    }

    /**
     * @return mixed
     * 用户信息修改
     */
    public function user_edit()
    {
        $id = Request::instance()->param('id');
        $list = $this->User->where('id=' . $id)->field('id,real_name,card')->find();
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 用户信息修改执行
     * @return array|\think\response\Json
     */
    public function user_edits()
    {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['real_name'])) {
                $r = msg_handle('请输入姓名', 0);
                return $r;
            } elseif (empty($data['card'])) {
                $r = msg_handle('请输入身份证号', 0);
                return $r;
            } else {
                $list = $this->User->update($data);
            }
            if ($list) {
                //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                $this->success('操作成功', 'Users/index');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败,未改动数据!');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return json($r);
    }

    /**
     * @return mixed用户充值
     */
    public function rechange()
    {
        $id = Request::instance()->param('id');
        $this->assign('id', $id);
        return $this->fetch();
    }

    /**
     * 用户充值执行
     * @return \think\response\Json
     */
    public function rechange_info()
    {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            $pre1 = '/^[0-9]+(.[0-9]{1,2})?$/';
            if (empty($data['number'])) {
                $r = msg_handle('请输入充值金额', 0);
                return $r;
            } elseif (!preg_match($pre1, $data['number'])) {
                $r = msg_handle('充值金额参数不正确', 0);
                return $r;
            } elseif (!preg_match($pre1, $data['fee'])) {
                $r = msg_handle('手续费参数不正确', 0);
                return $r;
            } elseif (empty($data['account'])) {
                $r = msg_handle('请输入到账金额', 0);
                return $r;
            } elseif (!preg_match($pre1, $data['account'])) {
                $r = msg_handle('到账金额参数不正确', 0);
                return $r;
            } else {
                $list = $this->Account->where('uid', $data['id'])->setInc('account', $data['account']);
            }
            // dump($data);exit;
            $dat['uid'] = $data['id'];
            $dat['number'] = $data['number'];
            $dat['fee'] = $data['fee'];
            $dat['type'] = $data['type'];
            $dat['time'] = time();
            $dat['status'] = 1;
            $dat['remark'] = $data['remark'];
            $dat['order'] = createOrderNum(1);
            $dat['pay_time'] = time() + 600;
            $map = $this->Recharge->insert($dat);
            if ($list) {
                //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                $this->success('充值成功', 'Users/index');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('充值成功,请稍后重试!');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return json($r);
    }
    public function user_admin(){

        $admin_id = session('admin_id');
        $map["id"]=$admin_id;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Agent->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);

        $sum = $this->Account->sum('account');
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        $this->assign('sum', $sum);
        return $this->fetch();
    }


}


?>