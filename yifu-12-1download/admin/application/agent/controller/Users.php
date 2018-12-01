<?php

namespace app\agent\controller;

use think\Session;
use \app\agent\model\Withdraw;
use app\agent\model\UserAccount;
use app\agent\model\UserBanks;
use think\Db;

class Users extends Common 
{

    protected $User;
    private $Withdraw;
    private $Recharge;
    private $CapitalFlow;
    protected $Agent;

    public function __construct(\think\Request $request = null)
    {
        parent::__construct($request);
        $this->Withdraw = new Withdraw();
        $this->User = new \app\agent\model\User();
        $this->UserAccount = new UserAccount();
        $this->Banks = new UserBanks();
    }

    public function inde1x()
    {
        $dladmin = session('dladmin');
        $dladmin['we_chat_pay'] = config('ADMIN_HOST') . $dladmin['we_chat_pay'];
        $this->assign('user', $dladmin);
        return $this->fetch();
    }

    /**
     * 会员列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $dladmin = session('dladmin');
        $map['reid'] = $dladmin['id'];
        $name = input('get.name');
        $map['is_agent'] = 0;
        if ($name) {
            $map['name|phone'] = ['like', $name];
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->User->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $sum = $this->UserAccount->sum('account');
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        $this->assign('sum', $sum);
        return $this->fetch();
    }

    /**
     * 下级代理信息
     * @return mixed
     */
    public function agent()
    {
        /*$rand = rand(1, 3);
        if ($rand == 1) {
            $rands = '0.'.rand(1, 8);
        } elseif ($rand == 2) {
            $rands = '0.0'.rand(1, 8);
        } elseif ($rand == 3) {
            $rands = '0.00'.rand(1, 8);
        }
        dump($rands);exit;*/
        $dladmin = session('dladmin');
        $map['reid'] = $dladmin['id'];
        $name = input('get.name');
        $map['is_agent'] = 1;
        if ($name) {
            $map['name|phone'] = ['like', $name];
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->User->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $sum = $this->UserAccount->sum('account');
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        $this->assign('sum', $sum);
        return $this->fetch();
    }
        /**
     * 下级代理列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function next_agent()
    {
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name|phone'] = ['like', $name];
        }
        $id = $_GET['id'];
        if ($id) {
            $map['reid'] = $id;
        }else{
            $dladmin = session('dladmin');
            $map['reid'] = $dladmin['id'];
        }
        $map['is_agent'] = 1;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->User->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $sum = $this->UserAccount->sum('account');
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        $this->assign('sum', $sum);
        return $this->fetch();
    }

    /**
     * 下级客户列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function next_user()
    {
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name|phone'] = ['like', $name];
        }
        $id = $_GET['id'];
        if ($id) {
            $map['reid'] = $id;
        }else{
            $dladmin = session('dladmin');
            $map['reid'] = $dladmin['id'];
        }
        $map['is_agent'] = 0;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->User->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $sum = $this->UserAccount->sum('account');
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        $this->assign('sum', $sum);
        return $this->fetch();
    }
 /**
     * @return mixed
     * 用户信息修改
     */
    public function user_edit()
    {
        $id = $_GET['id'];
        $list = $this->User->where('id=' . $id)->find();
        $bank = $this->Banks->where('uid='. $id)->field('bank_name,bank_card,branch')->find();
        $this->assign('list', $list);
        $this->assign('bank', $bank);
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
            if(empty($data['password'])){
                $map['password'] = $data['password'];
            }else{
                $dat['password'] = md5($data['password']);
            }
            $dat['real_name'] =$data['real_name'];
            $dat['we_chat'] =$data['we_chat'];
            $dat['we_chat_pay'] =$data['we_chat_pay'];
            $dat['id'] =$data['id'];
            $dat['alipay'] =$data['alipay'];
            $dat['alipay_pay'] =$data['alipay_pay'];
            $bank['bank_name'] =$data['bank_name'];
            $bank['bank_card'] =$data['bank_card'];
            $bank['branch'] =$data['branch'];
            $bank['real_name'] =$data['real_name'];
            $list = $this->User->update($dat);
            $lis = $this->Banks->where('uid='. $dat['id'])->update($bank);
            if ($list || $lis ) {
                /*设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']*/
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
     * dongjie冻结
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function forzen_edit()
    {
        $id = $_POST['id'];
        if (empty($id)) {
            $r = msg_handle('操作失败', 0);
        } else {
            $user = $this->User->find($id);
            if ($user['frozen'] != 1) {
                $data['frozen'] = 1;
            } else {
                $data['frozen'] = 0;
            }
            $res = $this->User->where('id', $id)->update($data);
            if ($res) {
                $r = msg_handle('操作成功', 1);
            } else {
                $r = msg_handle('操作失败', 0);
            }
        }
        return json($r);
    }
    /**
     * 关闭提现
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function close_edit()
    {
        $id = $_POST['id'];
        if (empty($id)) {
            $r = msg_handle('操作失败', 0);
        } else {
            $user = $this->User->find($id);
            if ($user['is_forward'] != 1) {
                $data['is_forward'] = 1;
            } else {
                $data['is_forward'] = 0;
            }
            $res = $this->User->where('id', $id)->update($data);
            if ($res) {
                $r = msg_handle('操作成功', 1);
            } else {
                $r = msg_handle('操作失败', 0);
            }
        }
        return json($r);
    }

}
