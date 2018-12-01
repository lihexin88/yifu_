<?php

namespace app\agent\controller;

use think\Request;
use app\common\model\User;
use app\common\model\Bank;
use app\common\model\UserBanks;

class System extends Common
{

    private $Rechar;
    protected $User;
    protected $UserBank;
    protected $Bank;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->User = new User();
        $this->UserBanks = new UserBanks();
        $this->Bank = new Bank();

    }

    /**
     * 用户设置
     * @return mixed
     */
    public function index()
    {
        $dladmin = session('dladmin');
        $user = $this->User->where('id', $dladmin['id'])->find();
        $this->assign('list', $user);
        return $this->fetch();
    }

    /**
     * 用户银行卡信息
     * @return mixed
     */
    public function bank()
    {
        $dladmin = session('dladmin');
        $id = $dladmin['id'];
        $bank = $this->UserBanks->where('uid', $id)->select();
        foreach ($bank as $key => &$value) {
         $value['time'] = detail_time($value['time']);  
        }
        $this->assign('bank', $bank);
        return $this->fetch();
    }

    /**
     * 执行修改
     * @return array
     */
    public function system_add_edit()
    {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['real_name'])) {
                $r = msg_handle('真实姓名不能为空', 0);
            } /*elseif (empty($data['we_chat'])) {
                $r = msg_handle('微信号不能为空', 0);
            } */ elseif (empty($data['we_chat_pay'])) {
                $r = msg_handle('微信二维码不能为空', 0);
            } else {
                $map['real_name'] = $data['real_name'];
                $map['wechat_cust'] = $data['wechat_cust'];
                $map['we_chat_pay'] = $data['we_chat_pay'];
                $map['alipay_pay'] = $data['alipay_pay'];
//                $map['alipay'] = $data['alipay'];
                $list = $this->User->where('id=' . $data['id'])->update($map);
                if ($list) {
                    //设置成功后跳转页面的地址
                    $this->success('操作成功', 'System/index');
                } else {
                    //错误页面的默认跳转页面是返回前一页，通常不需要设置
                    $this->error('操作失败,未改动数据!');
                }
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
    }
    /**
     * 添加/修改功能
     * @return mixed
     */
    public function edit()
    {
        $id = Request::instance()->param('id');
        if ($id) {
            $list = $this->UserBanks->where('id', $id)->find();
            if ($list) {
                $this->assign('list', $list);
            } else {
                $this->redirect('System/bank');
            }
        } else {
            $list = array('id' => '', 'bank_name' => '', 'bank_card' => '', 'real_name' => '','bank_img' => '');
            $this->assign('list', $list);
        }
        $bank = $this->Bank->select();
        $this->assign('bank', $bank);
        return $this->fetch();
    }
    public function add_edit()
    {
        $dladmin = session('dladmin');
        $adminid = $dladmin['id'];
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            $map['time'] = time();
            if (empty($data['bank_card'])) {
                $r = msg_handle('请添加银行卡号', 0);
                return $r;
            }elseif(empty($data['bank_name'])){
                $r = msg_handle('请添加银行名称', 0);
                return $r;
            }elseif(empty($data['real_name'])){
                $r = msg_handle('请添加开户人姓名', 0);
                return $r;
            } elseif (empty($data['id'])) {
                $bank = $this->Bank->where('name',$data['bank_name'])->find();
                $map['bank_name'] = $bank['name'];
                $map['bank_card'] = $data['bank_card'];
                $map['real_name'] = $data['real_name'];
                $map['bank_img']  =  $bank['pic'];
                $map['uid']  =  $adminid;
                $list = $this->UserBanks->insert($map);
            } else {
                $bank = $this->Bank->where('name',$data['bank_name'])->find();
                $map['bank_name'] = $bank['name'];
                $map['bank_card'] = $data['bank_card'];
                $map['real_name'] = $data['real_name'];
                $map['bank_img']  =  $bank['pic'];
                $list = $this->UserBanks->where('id=' . $data['id'])->update($map);
            }
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'System/bank');

            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败,未改动数据!');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
    }

}



















