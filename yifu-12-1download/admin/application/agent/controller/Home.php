<?php

namespace app\agent\controller;
use app\agent\model\Rewith;
use app\agent\model\Deal;
use app\agent\model\User;
use think\Request;

class Home extends Common {

    public function __construct(Request $request = null) {
        parent::__construct($request);

        $this->Agent = new \app\agent\model\Agent();
        $this->Rewith = new Rewith();
        $this->User = new User();
        $this->Deal = new Deal();
        $user = session('dladmin');
        $map['roid'] = $user['ro_id'];
        $left = $this->Relation->query($map);
        $this->assign('left', $left);
        $this->assign('user', $user);
    }
    /**
     * 首页方法
     * @return mixed
     */
    public function index()
    {
        $user_count = $this->user_total();
        $order_count = $this->order_info();
        $number = $this->count();
        $this->assign('list', $list);
        $this->assign('number', $number);
        $this->assign('user_count', $user_count);
        $this->assign('order_count', $order_count);
        return $this->fetch();
    }
/**
     * 用户信息
     * @return mixed
     */
    private function user_total()
    {
        $map_day = array();
        $user = session('dladmin');
        $map_day['roid'] = $user['ro_id'];
        $start = date('Y-m-d', time());
        $map_day = $this->query_time($map_day, $start, $start);
        $day_count = $this->User->where($map_day)->count();
        $count = $this->User->count();
        $data['day_count'] = $day_count;
        $data['count'] = $count;
        return $data;
    }

    /**
     * 订单信息
     * @return mixed
     */
    private function order_info()
    {
        $map_day = array();
        $user = session('dladmin');
        $map_day['roid'] = $user['ro_id'];
        $start = date('Y-m-d', time());
        $map_day = $this->query_time($map_day, $start, $start);
        $day_count = $this->Order->where($map_day)->count();
        $count = $this->Order->count();
        $data['day_count'] = $day_count;
        $data['count'] = $count;
        return $data;
    }
    /**
     * 订单信息
     * @return mixed
     */
    private function count()
    {
        $map_day = array();
        $start = date('Y-m-d', time());
        $end = date('Y-m-d', time()+86400);
        $map_day['type']=1;
        $user = session('dladmin');
        $map_day['roid'] = $user['ro_id'];
        $map_day = $this->query_time($map_day, $start, $end);
        $day_count = $this->Rewith->where($map_day)->sum('num');
        return $day_count;
    }
    
    public function password() {
        $user = session('dladmin');
        $a = Request::instance()->param('a');
        $b = Request::instance()->param('b');
        $this->assign('a', $a);
        $this->assign('b', $b);
        $this->assign('user', $user);
        return $this->fetch();
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function password_handle() {
        $uid = session('dladmin_id');
        if (empty($uid)) {
            $r = msg_handle('修改失败，请稍后重试', 0);
            return json($r);
        }
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['password'])) {
                $r = msg_handle('旧密码不能为空', 0);
            } elseif (empty($data['new'])) {
                $r = msg_handle('新密码不能为空', 0);
            } elseif ($data['new'] != $data['confirm']) {
                $r = msg_handle('两次密码不一致', 0);
            } else {
                $user = $this->Agent->where(array('id' => $uid, 'password' => md5($data['password'])))->find();
                if (empty($user)) {
                    $r = msg_handle('旧密码输入错误', 0);
                } else {
                    $map['id'] = $uid;
                    $map['password'] = md5($data['confirm']);
                    $res = $this->Agent->update($map);
                    if ($res) {
                        $r = msg_handle('修改成功', 1);
                    } else {
                        $r = msg_handle('修改失败，请联系网站管理员', 0);
                    }
                }
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return json($r);
    }

}
