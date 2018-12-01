<?php

namespace app\agents\controller;

use think\Request;

class Home extends Common {

    public function __construct(Request $request = null) {
        parent::__construct($request);

        $this->Agent = new \app\agents\model\Agent();
        $user = session('dladmin');
        $map['roid'] = $user['ro_id'];
        $left = $this->Relation->query($map);
        $this->assign('left', $left);
        $this->assign('user', $user);
    }

    public function password() {
        $user = session('ygadmin');
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
        $uid = session('ygadmin_id');
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
