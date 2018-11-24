<?php

namespace app\monitor\controller;

use think\Request;
use app\common\model\Agent;
use app\common\model\AgentWith;
use app\common\model\AgentAcc;
use app\common\model\Agentinfo;
use app\common\model\StaffAcc;
use app\common\model\AgentFee;
use think\Session;

/*
 * 代理管理
 */

class Agents extends Common
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Agent = new Agent();
        $this->AgentWith = new AgentWith();
        $this->AgentAcc = new AgentAcc();
        $this->StaffAcc = new StaffAcc();
        $this->Agentinfo = new Agentinfo();
        $this->AgentFee = new AgentFee();
    }

    //登录后台
    public function login()
    {
        $requset = \think\Request::instance();
        if ($requset->isGet()) {
            $id = $requset->param('id');
            $where = ['id' => $id];
            $num = $this->Agent->where($where)->find();

//查询出后台管理员表  name=num的信息
            session('dladmin', $num);
            session('dladmin_id', $num['id']);
            if ($num) {
                $data['status'] = 1;
                $data['msg'] = '登陆成功';
                $this->redirect('/agent/index/index');
            } else {
                $data['status'] = 0;
                $data['msg'] = '登陆失败';
            }
            return json($data);
        }
    }
        //登录员工后台
    public function login_agent() {
        $requset = \think\Request::instance();
        if ($requset->isGet()) {
            $id = $requset->param('id');
            $where = ['id' => $id];
            $num = $this->Agentinfo->where($where)->find();

//查询出后台管理员表  name=num的信息
            session('ygadmin', $num);
            session('ygadmin_id', $num['id']);
//            dump($num);die;
            if ($num) {
                $data['status'] = 1;
                $data['msg'] = '登陆成功';
                $this->redirect('/agents/index/index');
            } else {
                $data['status'] = 0;
                $data['msg'] = '登陆失败';
            }
            return json($data);
        }
    }

    //登录后台
    public function logins()
    {
        $requset = \think\Request::instance();
        if ($requset->isGet()) {
            $id = $requset->param('id');
            $where = ['id' => $id];
            $num = $this->Agentinfo->where($where)->find();
//查询出后台管理员表  name=num的信息
            session('dladmin', $num);
            session('dladmin_id', $num['id']);
            if ($num) {
                $data['status'] = 1;
                $data['msg'] = '登陆成功';
                $this->redirect('/agents/index/index');
            } else {
                $data['status'] = 0;
                $data['msg'] = '登陆失败';
            }
            return json($data);
        }
    }

    /**
     * 代理列表
     * @return mixed
     */
    public function index()
    {
        $arr=input('get.');
        $m = array();
        $map = $this->query_time($m, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Agent->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $fee_ratio = $this->Agent->sum('fee_ratio');
        $defer_ratio = $this->Agent->sum('defer_ratio');
        $wit_ratio = $this->Agent->sum('wit_ratio');
        $this->assign('fee_ratio', $fee_ratio);
        $this->assign('defer_ratio', $defer_ratio);
        $this->assign('wit_ratio', $wit_ratio);
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    public function del()
    {
        $request = \think\Request::instance();
        if ($request->isPost()) {
            $result = $request->Post();
            $re = $this->Agent->where('id', $result['id'])->delete(); //删除轮播图      
            if ($re) {
                $data['status'] = '1';
                $data['msg'] = "删除成功";
            } else {
                $data['status'] = '0';
                $data['msg'] = "删除失败";
            }
            return json($data);
        }
    }

     /**
     * 代理添加
     * @return mixed
     *
     */
    public function add_agent() {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['number'])) {
                $r = msg_handle('代理编号不能为空', 0);
            } elseif (empty($data['password'])) {
                $r = msg_handle('密码不能为空', 0);
            } elseif (!preg_match(REG_PASS, $data['password'])) {
                $r = msg_handle('密码为6-12位字母数字组合', 0);
            } elseif (empty($data['name'])) {
                $r = msg_handle('代理名称不能为空', 0);
            } elseif (empty($data['status'])) {
                $r = msg_handle('请选择代理状态', 0);
            } elseif (empty($data['bank_name'])) {
                $r = msg_handle('开户行不能为空', 0);
            } elseif (empty($data['bank_address'])) {
                $r = msg_handle('开户分行不能为空', 0);
            } elseif (empty($data['bank_account'])) {
                $r = msg_handle('开户姓名不能为空', 0);
            } elseif (empty($data['bank_card'])) {
                $r = msg_handle('提现银行卡号不能为空', 0);
            } elseif (!preg_match(REG_BANKCARD, $data['bank_card'])) {
                $r = msg_handle('银行卡号格式错误', 0);
            } elseif (empty($data['withdrawals_status'])) {
                $r = msg_handle('请选择提现状态', 0);
            } elseif (empty($data['agent_url'])) {
                $r = msg_handle('代理域名不能为空', 0);
            } elseif (empty($data['real_name'])) {
                $r = msg_handle('真实姓名不能为空', 0);
            } elseif (empty($data['phone'])) {
                $r = msg_handle('手机号不能为空', 0);
            } elseif (!preg_match(REG_PHONE, $data['phone'])) {
                $r = msg_handle('手机号格式错误', 0);
            } elseif (empty($data['bond'])) {
                $r = msg_handle('保证金不能为空', 0);
            } elseif (empty($data['wit_ratio'])) {
                $r = msg_handle('盈利分配提成比例不能为空', 0);
            } elseif (empty($data['fee_ratio'])) {
                $r = msg_handle('手续费提成比例不能为空', 0);
            } elseif (empty($data['defer_ratio'])) {
                $r = msg_handle('递延费提成比例不能为空', 0);
            } else {
                $user = $this->Agent->where('phone', $data['phone'])->find();
                if ($user) {
                    $r = msg_handle('该手机号已存在', 0);
                    return json($r);
                }
                $data['password']=md5($data['password']);
                $res = $this->Agent->add($data);
                $da['uid'] = session('admin')['id'];
                $da['time'] = time();
                $da['desc'] = '管理员' . session('admin')['name'] . '添加了编号为' . $data['number'] . '的代理';
                $this->admin_log($da);
                if ($res) {
                    $u = $this->Agent->where('phone', $data['phone'])->find();
                    $admin = ['uid' => $u['id']];
                    $id = $this->AgentAcc->insert($admin);
                    if ($id) {
                        $r = msg_handle('操作成功', 1);
                    } else {
                        $r = msg_handle('操作失败', 0);
                    }
                } else {
                    $r = msg_handle('操作失败', 0);
                }
            }
            return json($r);
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $this->fetch();
    }

    /**
     * 下级代理添加
     * @return mixed
     *
     */
    public function add() {
        $id = Request::instance()->param('id');

        $agent = $this->Agent->where('grade', '<', '3')->select();
        if ($id) {
            if (request()->isGet()) {
                $list = $this->Agent->where(array('id' => $_GET['id']))->find();
                if ($list) {
                    session('list', $list);
                    $this->assign('list', $list);
                } else {
                    $this->redirect('Agents/index');
                }
            } else {
                $this->redirect('Agents/index');
            }
        } else {
            $list = array('id' => '', 'name' => '', 'agent' => '', 'number' => '', 'bank_name' => '', 'bank_address' => '', 'bank_account' => '', 'bank_card' => '', 'withdrawals_status' => '1', 'agent_url' => '', 'password' => '', 'real_name' => '', 'bond' => '', 'ratio' => '', 'phone' => '', 'pic' => '', 'status' => '1',);
            $this->assign('list', $list);
        }
        $this->assign('agent', $agent);
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['number'])) {
                $r = msg_handle('代理编号不能为空', 0);
            } elseif (empty($data['password'])) {
                $r = msg_handle('密码不能为空', 0);
            } elseif (!preg_match(REG_PASS, $data['password'])) {
                $r = msg_handle('密码为6-12位字母数字组合', 0);
            } elseif (empty($data['name'])) {
                $r = msg_handle('代理名称不能为空', 0);
            } elseif (empty($data['status'])) {
                $r = msg_handle('请选择代理状态', 0);
            } elseif (empty($data['bank_name'])) {
                $r = msg_handle('开户行不能为空', 0);
            } elseif (empty($data['bank_address'])) {
                $r = msg_handle('开户分行不能为空', 0);
            } elseif (empty($data['bank_account'])) {
                $r = msg_handle('开户姓名不能为空', 0);
            } elseif (empty($data['bank_card'])) {
                $r = msg_handle('提现银行卡号不能为空', 0);
            } elseif (!preg_match(REG_BANKCARD, $data['bank_card'])) {
                $r = msg_handle('银行卡号格式错误', 0);
            } elseif (empty($data['withdrawals_status'])) {
                $r = msg_handle('请选择提现状态', 0);
            } elseif (empty($data['agent_url'])) {
                $r = msg_handle('代理域名不能为空', 0);
            } elseif (empty($data['real_name'])) {
                $r = msg_handle('真实姓名不能为空', 0);
            } elseif (empty($data['phone'])) {
                $r = msg_handle('手机号不能为空', 0);
            } elseif (!preg_match(REG_PHONE, $data['phone'])) {
                $r = msg_handle('手机号格式错误', 0);
            } elseif (empty($data['bond'])) {
                $r = msg_handle('保证金不能为空', 0);
            } elseif (empty($data['wit_ratio'])) {
                $r = msg_handle('盈利分配提成比例不能为空', 0);
            } elseif (empty($data['fee_ratio'])) {
                $r = msg_handle('手续费提成比例不能为空', 0);
            } elseif (empty($data['defer_ratio'])) {
                $r = msg_handle('递延费提成比例不能为空', 0);
            } else {
                $user = $this->Agent->where('phone', $data['phone'])->find();
                if ($user) {
                    $r = msg_handle('该手机号已存在', 0);
                    return json($r);
                }
                $li = session('list');
                $data['password']=md5($data['password']);
                $res = $this->Agent->add($data, $li);
                $da['uid'] = session('admin')['id'];
                $da['time'] = time();
                $da['desc'] = '管理员' . session('admin')['name'] . '添加了编号为' . $data['number'] . '的代理';
                $this->admin_log($da);
                if ($res) {
                    $u = $this->Agent->where('phone', $data['phone'])->find();
                    $admin = ['uid' => $u['id']];
                    $id = $this->AgentAcc->insert($admin);
   
                    if ($id) {
                        $r = msg_handle('操作成功', 1);
                    } else {
                        $r = msg_handle('操作失败', 0);
                    }
                } else {
                    $r = msg_handle('操作失败', 0);
                }
            }
            return json($r);
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $this->fetch();
    }

    /**
     * 员工列表
     * @return mixed
     *
     */
    public function staff()
    {
        $id = Request::instance()->param('id');
        $m = ['aid'=>$id];
        $map = $this->query_time($m, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Agentinfo->log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
        /**
     * 员工添加
     * @return mixed
     *
     */
    public function add_staff() {
        $agent = $this->Agent->select();
        if ($agent) {
            $this->assign('agent', $agent);
        } else {
            $list = array('id' => '', 'name' => '', 'agent' => '', 'number' => '', 'bank_name' => '', 'bank_address' => '', 'bank_account' => '', 'bank_card' => '', 'withdrawals_status' => '1', 'agent_url' => '', 'password' => '', 'real_name' => '', 'bond' => '', 'ratio' => '', 'phone' => '', 'pic' => '', 'status' => '1',);
            $this->assign('list', $list);
        }
        $this->assign('agent', $agent);
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['num_name'])) {
                $r = msg_handle('员工编号不能为空', 0);
            } elseif (empty($data['password'])) {
                $r = msg_handle('密码不能为空', 0);
            } elseif (!preg_match(REG_PASS, $data['password'])) {
                $r = msg_handle('密码为6-12位字母数字组合', 0);
            } elseif (empty($data['name'])) {
                $r = msg_handle('员工名称不能为空', 0);
            } elseif (empty($data['status'])) {
                $r = msg_handle('请选择员工状态', 0);
            } elseif (empty($data['bank'])) {
                $r = msg_handle('提现银行卡号不能为空', 0);
            } elseif (!preg_match(REG_BANKCARD, $data['bank'])) {
                $r = msg_handle('银行卡号格式错误', 0);
            } elseif (empty($data['bank_status'])) {
                $r = msg_handle('请选择提现状态', 0);
            } elseif (empty($data['cont_name'])) {
                $r = msg_handle('真实姓名不能为空', 0);
            } elseif (empty($data['cont_phone'])) {
                $r = msg_handle('手机号不能为空', 0);
            } elseif (!preg_match(REG_PHONE, $data['cont_phone'])) {
                $r = msg_handle('手机号格式错误', 0);
            } elseif (empty($data['bond'])) {
                $r = msg_handle('保证金不能为空', 0);
            } elseif (empty($data['com_fee'])) {
                $r = msg_handle('手续费提成比例不能为空', 0);
            } elseif (empty($data['defer_ratio'])) {
                $r = msg_handle('递延费提成比例不能为空', 0);
            } else {
                $user = $this->Agentinfo->where('cont_phone', $data['cont_phone'])->find();
                if ($user) {
                    $r = msg_handle('该手机号已存在', 0);
                    return json($r);
                }
                $data['password']=md5($data['password']);
                $res = $this->Agentinfo->insert($data);
                $da['uid'] = session('admin')['id'];
                $da['time'] = time();
                $da['desc'] = '管理员' . session('admin')['name'] . '添加了代理ID为' . $data['aid'] . '的,编号为' . $data['num_name'] . '的员工';
                $this->admin_log($da);
                if ($res) {
                    $u = $this->Agentinfo->where('cont_phone', $data['cont_phone'])->find();
                    $admin = ['uid' => $u['id']];
                    $id = $this->StaffAcc->insert($admin);
                    if ($id) {
                        $r = msg_handle('操作成功', 1);
                    } else {
                        $r = msg_handle('操作失败', 0);
                    }
                } else {
                    $r = msg_handle('操作失败', 0);
                }
            }
            return json($r);
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $this->fetch();
    }
    /**
     * 修改员工信息
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit_staff() {
        $id = Request::instance()->param('id');
        $agent = $this->Agent->select();
        $list = $this->Agentinfo->where(array('id' => $_GET['id']))->find()->toArray();
        $this->assign('list', $list);
        $this->assign('agent', $agent);

        return $this->fetch();
    }

    public function staff_rech() {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['password'])) {
                $r = msg_handle('密码不能为空', 0);
            } elseif (!preg_match(REG_PASS, $data['password'])) {
                $r = msg_handle('密码为6-12位字母数字组合', 0);
            } elseif (empty($data['name'])) {
                $r = msg_handle('员工名称不能为空', 0);
            } elseif (empty($data['status'])) {
                $r = msg_handle('请选择员工状态', 0);
            } elseif (empty($data['bank'])) {
                $r = msg_handle('提现银行卡号不能为空', 0);
            } elseif (!preg_match(REG_BANKCARD, $data['bank'])) {
                $r = msg_handle('银行卡号格式错误', 0);
            } elseif (empty($data['bank_status'])) {
                $r = msg_handle('请选择提现状态', 0);
            } elseif (empty($data['cont_name'])) {
                $r = msg_handle('真实姓名不能为空', 0);
            } elseif (empty($data['cont_phone'])) {
                $r = msg_handle('手机号不能为空', 0);
            } elseif (!preg_match(REG_PHONE, $data['cont_phone'])) {
                $r = msg_handle('手机号格式错误', 0);
            } elseif (empty($data['bond'])) {
                $r = msg_handle('保证金不能为空', 0);
            } elseif (empty($data['com_fee'])) {
                $r = msg_handle('手续费提成比例不能为空', 0);
            } elseif (empty($data['defer_ratio'])) {
                $r = msg_handle('递延费提成比例不能为空', 0);
            } else {
                $user = $this->Agentinfo->where('cont_phone', $data['cont_phone'])->find();
                if ($user) {
                    $r = msg_handle('该手机号已存在', 0);
                    return json($r);
                }
                $data['password']=md5($data['password']);
                $res = $this->Agentinfo->where('id', $data['id'])->update($data);
                $da['uid'] = session('admin')['id'];
                $da['time'] = time();
                $da['desc'] = '管理员' . session('admin')['name'] . '修改了编号为' . $data['num_name'] . '的员工信息';
                $this->admin_log($da);
                if ($res) {
                    $r = msg_handle('操作成功', 1);
                } else {
                    $r = msg_handle('操作失败', 0);
                }
            }
            return json($r);
        } else {
            $r = msg_handle('错误操作', 0);
        }
    }


    /**
     * 修改信息
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit()
    {
        $id = Request::instance()->param('id');
        $agent = $this->Agent->where('grade', '<', '3')->select();
        if ($id) {
            if (request()->isGet()) {
                $list = $this->Agent->where(array('id' => $_GET['id']))->find();
                if ($list) {
                    $this->assign('list', $list);
                } else {
                    $this->redirect('Agents/index');
                }
            } else {
                $this->redirect('Agents/index');
            }
        } else {
            $list = array('id' => '', 'name' => '', 'agent' => '', 'number' => '', 'bank_name' => '', 'bank_address' => '', 'bank_account' => '', 'bank_card' => '', 'withdrawals_status' => '1', 'agent_url' => '', 'password' => '', 'real_name' => '', 'bond' => '', 'ratio' => '', 'phone' => '', 'pic' => '', 'status' => '1',);
            $this->assign('list', $list);
        }
        $this->assign('agent', $agent);
        return $this->fetch();
    }

    /**
     * 修改代理信息
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function modify_play()
    {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['id'])) {
                $r = msg_handle('所属代理不能为空', 0);
            } elseif (empty($data['number'])) {
                $r = msg_handle('代理编号不能为空', 0);
            } elseif (empty($data['name'])) {
                $r = msg_handle('代理名称不能为空', 0);
            } elseif (empty($data['password'])) {
                $r = msg_handle('密码不能为空', 0);
            } elseif (!preg_match(REG_PASS, $data['password'])) {
                $r = msg_handle('密码格式错误', 0);
            } elseif (empty($data['bank_name'])) {
                $r = msg_handle('开户行不能为空', 0);
            } elseif (empty($data['bank_address'])) {
                $r = msg_handle('开户分行不能为空', 0);
            } elseif (empty($data['bank_account'])) {
                $r = msg_handle('开户姓名不能为空', 0);
            } elseif (empty($data['bank_card'])) {
                $r = msg_handle('提现银行卡号不能为空', 0);
            } elseif (!preg_match(REG_BANKCARD, $data['bank_card'])) {
                $r = msg_handle('银行卡号格式错误', 0);
            } elseif (empty($data['agent_url'])) {
                $r = msg_handle('代理域名不能为空', 0);
            } elseif (empty($data['real_name'])) {
                $r = msg_handle('真实姓名不能为空', 0);
            } elseif (empty($data['phone'])) {
                $r = msg_handle('手机号不能为空', 0);
            } elseif (!preg_match(REG_PHONE, $data['phone'])) {
                $r = msg_handle('手机号格式错误', 0);
            } elseif (empty($data['bond'])) {
                $r = msg_handle('保证金不能为空', 0);
            } elseif (empty($data['wit_ratio'])) {
                $r = msg_handle('盈利分配提成比例不能为空', 0);
            } elseif (empty($data['fee_ratio'])) {
                $r = msg_handle('手续费提成比例不能为空', 0);
            } elseif (empty($data['defer_ratio'])) {
                $r = msg_handle('递延费提成比例不能为空', 0);
            } else {
                $user = $this->Agent->where(['phone' => $data['phone']])->where('id', 'neq', $data['id'])->find();
                if ($user) {
                    $r = msg_handle('该手机号已存在', 0);
                    return json($r);
                }
                $data['password']=md5($data['password']);
                $res = $this->Agent->modify_log($data);
                $da['uid'] = session('admin')['id'];
                $da['time'] = time();
                if ($data['id']) {
                    $da['desc'] = '管理员' . session('admin')['name'] . '修改了id为' . $data['id'] . '代理信息';
                } else {
                    $da['desc'] = '管理员' . session('admin')['name'] . '添加了代理信息';
                }
                $this->admin_log($da);
                if ($res) {
                    $r = msg_handle('操作成功', 1);
                } else {
                    $r = msg_handle('内容无修改', 0);
                }
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
    }

    /**
     * 代理开启/关闭
     * @return mixed
     */
    public function modify_recharge()
    {
        $id = $_POST['id'];
        $edit_type = $_POST['edit_type'];
        if (!$id) {
            $this->redirect('Agents/index');
        }
        if ($edit_type == 1) {
            $data['status'] = 1;
            $data['withdrawals_status'] = 1;
            $res = $this->Agent->where('id', $id)->update($data);
            $da['uid'] = session('admin')['id'];
            $da['time'] = time();
            if ($id) {
                $da['desc'] = '管理员' . session('admin')['name'] . '开启ID为' . $id . '的代理功能';
            } else {
                $r = msg_handle('操作错误', 0);
            }
            $this->admin_log($da);
            if ($res) {
                $r = msg_handle('修改成功', 1);
            } else {
                $r = msg_handle('修改失败', 0);
            }
            return json($r);
        } else {
            $data['status'] = 2;
            $data['withdrawals_status'] = 2;
            $res = $this->Agent->where('id', $id)->update($data);
            $da['uid'] = session('admin')['id'];
            $da['time'] = time();
            if ($id) {
                $da['desc'] = '管理员' . session('admin')['name'] . '锁定ID为' . $id . '的代理功能';
            } else {
                $r = msg_handle('操作错误', 0);
            }
            $this->admin_log($da);
            if ($res) {
                $r = msg_handle('修改成功', 1);
            } else {
                $r = msg_handle('修改失败', 0);
            }
            return json($r);
        }
    }
        /**
     * 用户开启/关闭
     * @return mixed
     */
    public function staff_recharge() {
        $id = $_POST['id'];
        $edit_type = $_POST['edit_type'];
        if (!$id) {
            $this->redirect('Agents/index');
        }
        if ($edit_type == 1) {
            $data['status'] = 1;
            $data['bank_status'] = 1;
            $res = $this->Agentinfo->where('id', $id)->update($data);
            $da['uid'] = session('admin')['id'];
            $da['time'] = time();
            if ($id) {
                $da['desc'] = '管理员' . session('admin')['name'] . '开启ID为' . $id . '的代理功能';
            } else {
                $r = msg_handle('操作错误', 0);
            }
            $this->admin_log($da);
            if ($res) {
                $r = msg_handle('修改成功', 1);
            } else {
                $r = msg_handle('修改失败', 0);
            }
            return json($r);
        } else {
            $data['status'] = 2;
            $data['bank_status'] = 2;
            $res = $this->Agentinfo->where('id', $id)->update($data);
            $da['uid'] = session('admin')['id'];
            $da['time'] = time();
            if ($id) {
                $da['desc'] = '管理员' . session('admin')['name'] . '锁定ID为' . $id . '的代理功能';
            } else {
                $r = msg_handle('操作错误', 0);
            }
            $this->admin_log($da);
            if ($res) {
                $r = msg_handle('修改成功', 1);
            } else {
                $r = msg_handle('修改失败', 0);
            }
            return json($r);
        }
    }


    /**
     * 佣金明细
     * @return mixed
     */
    public function detail(){
        $map = $this->query_time('', input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->AgentFee->total($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /**
     * 佣金提现
     * @return mixed
     */
    public function withdraw() {
        $m = array();
        $phone = trim(input('get.phone'));
        if ($phone) {
            $phone = [
                'phone' => ['like', "%$phone%"]
            ];
            $user = $this->Agent->where($phone)->find();
            $m['uid'] = $user['id'];
        }
        $map = $this->query_time($m, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->AgentWith->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $number = $this->AgentWith->where($map)->sum('number');
        $fee = $this->AgentWith->where($map)->sum('fee');
        $this->assign('number', $number);
        $this->assign('fee', $fee);
        $this->assign('total', $number - $fee);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /**
     * 处理提现申请 同意OR拒绝
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function pass() {
        $id = $_POST['id'];
        $edit_type = $_POST['edit_type'];
        if (!$id) {
            $this->redirect('Agents/withdraw');
        }
        $info = $this->AgentWith->where('id=' . $id)->find()->toArray();
        if ($info['agent'] == 0) {
            $account = $this->StaffAcc->where('uid', $info['staff'])->find();
        } else {
            $account = $this->AgentAcc->where('uid', $info['agent'])->find();
        }
        if ($edit_type == 1) {
            if ($info['agent'] == 0) {
                $res1 = $this->StaffAcc->where('uid', $info['staff'])->setInc('wit_total', $info['number']); //交易账户累计提现
            } else {
                $res1 = $this->AgentAcc->where('uid', $info['agent'])->setInc('wit_total', $info['number']); //交易账户累计提现
            }

            if ($res1) {
                $data['pay_time'] = time();
                $data['status'] = 1;
                $res = $this->AgentWith->where('id', $id)->update($data);
                if ($res) {
                    $r = msg_handle('操作成功', 1);
                } else {
                    $r = msg_handle('操作失败', 0);
                }
                return json($r);
            }
        } else {
            $da = [
                'account' => $account['account'] + $info['number'] + $info['fee']
            ];
            if ($info['agent'] == 0) {
                $res1 = $this->StaffAcc->where('uid', $info['staff'])->update($da); //返回提现金额
            } else {
                $res1 = $this->AgentAcc->where('uid', $info['agent'])->update($da); //返回提现金额
            }
            if ($res1) {
                $data['pay_time'] = time();
                $data['status'] = 2;
                if ($info['agent'] == 0) {
                    $res = $this->AgentWith->where('id', $id)->update($data);
                } else {
                    $res = $this->AgentWith->where('id', $id)->update($data);
                }
                if ($res) {
                    $r = msg_handle('操作成功', 1);
                } else {
                    $r = msg_handle('操作失败', 0);
                }
            }
            return json($r);
        }
    }


    /**
     * 佣金统计
     * @return mixed
     */
    public function total() {
        $m = array();
        $map = $this->query_time($m, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->AgentFee->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

}

?>