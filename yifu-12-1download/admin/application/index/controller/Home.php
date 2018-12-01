<?php

namespace app\index\controller;

use app\common\model\Admin;
use app\common\model\Acc;
use app\common\model\Deal;
use think\Request;

class Home extends Common
{
    private $Admin;
    private $Deal;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $this->Admin = new Admin();
        $this->Acc = new Acc();
        $this->Deal = new Deal();
        $user = session('admin');
        $left = session('left');
        $this->assign('left', $left);
        $this->assign('user', $user);
    }

    public function password()
    {
        $user = session('admin');
        $this->assign('user', $user);
        return $this->fetch();
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function password_handle()
    {
        $uid = session('admin_id');
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
                $user = $this->Admin->where(array('id' => $uid, 'password' => md5($data['password'])))->find();
                if (empty($user)) {
                    $r = msg_handle('旧密码输入错误', 0);
                } else {
                    $map['id'] = $uid;
                    $map['password'] = md5($data['confirm']);
                    $res = $this->Admin->update($map);
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

    /**
     * 首页方法
     * @return mixed
     */
    public function index()
    {
        $map['id'] = 1;
        $list['money'] = 10333;
        $list['sum_deal'] = 9999;
        $list['sum_deal'] = 9999;
        $list['sum_deal'] = $this->sum_deal();
        $list['sum_day_deal'] = $this->sum_day_deal();
        $list['sum_people'] = $this->sum_people();
        $list['sum_people'] = $this->sum_people();
        $list['disable_people'] = $this->disable_people();
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 总人数
     * @return int|string
     */
    private function sum_people()
    {
        return $this->User->count();
    }

    /**
     * 禁用人数
     * @return int|string
     */
    private function disable_people()
    {
        return $this->User->where('status', 0)->count();
    }

    /**
     * 总交易
     * @return int|string
     */
    private function sum_deal()
    {
        return $this->Deal->sum('number');
    }
    /**
     * 总交易
     * @return int|string
     */
    private function sum_day_deal()
    {
        $ltime=strtotime(date('Y-m-d',time()));
        $map['time']=['gt',$ltime];
        $map['time']=['lt',time()];
        return $this->Deal->where($map)->sum('number');
    }

    private function trade_total()
    {
        $map['status'] = 3;
        $number = 0;
        $list = $this->Order->where($map)->select();
        foreach ($list as $key => $value) {
            if ($value['status'] == 3) {
                $number++;
            }
        }
        $no_num = 0;
        $list = $this->Order->select();
        foreach ($list as $key => $value) {
            $no_num++;
        }

        $data['number'] = $number;
        $data['no_num'] = $no_num;
        return $data;
    }

    private function account_total()
    {
        $number = 0;
        $num = 0;
        $list = $this->Shop->select();
        foreach ($list as $key => $value) {
            $number++;
            if ($value['status'] == 1) {
                $num++;
            }
        }
        $data['number'] = $number;
        $data['num'] = $num;
        return $data;
    }

    public function fish_total()
    {
        $map['death'] = 0;
        $number = 0;//$this->FishData->where($map)->sum('number');
        $data['number'] = $this->wan_number($number);
        return $data;
    }

    public function split_total()
    {
        $bonus = 0;//$this->SplitLog->sum('number');
        $map['time'] = array('egt', day_time());
        $bonus_day = 0;//$this->SplitLog->where($map)->sum('number');
        $data['number'] = $this->wan_number($bonus);
        $data['day'] = $bonus_day;
        return $data;
    }

    private function bonus_total()
    {
        $bonus = 0;//$this->Bonus->sum('number');
        $map['time'] = array('egt', day_time());
        $bonus_day = 0;//$this->Bonus->where($map)->sum('number');
        $data['bonus'] = $bonus;
        $data['bonus_day'] = $bonus_day;
        return $data;
    }

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function query_user()
    {
        $user = $this->User->select();
        $num = $no_num = $frozen = $today = $total = $today_activate = 0;
        $time = day_time();
        foreach ($user as $key => $value) {
            $total++;
            if ($value['time'] >= $time) {
                $today++;
            }
            if ($value['time'] >= $time && $value['type'] != 0) {
                $today_activate++;
            }
            if ($value['level'] == 1) {
                $no_num++;
            }
            if ($value['status'] == 0) {
                $frozen++;
            }
            if ($value['status'] != 0 && $value['type'] != 0) {
                $num++;
            }
        }
        $data['today'] = $today;
        $data['no_num'] = $no_num;
        $data['frozen'] = $frozen;
        $data['num'] = $num;
        $data['total'] = $total;
        $data['today_activate'] = $today_activate;
        return $data;
    }

    public function wan_number($number)
    {
        if ($number > 10000 && $number <= 10000000) {
            $name = round($number / 10000, 2) . '<small style="font-size: 16px;"> 万</small>';
        } elseif ($number > 10000000) {
            $name = round($number / 10000000, 2) . '<small style="font-size: 16px;">千万</small>';
        } else {
            $name = $number;
        }
        return $name;
    }

}

















