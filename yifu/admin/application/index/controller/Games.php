<?php

namespace app\index\controller;

use app\common\model\Info;
use think\Request;

class Games extends Common
{
    private $Info;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Info = new Info();
    }

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $data = $this->Info->query_log();
        $this->assign('list', $data);
        return $this->fetch();
    }

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function modify_log()
    {
        if (input('?get.id')) {
            $map['id'] = $_GET['id'];
            $list = $this->Info->where($map)->find();
            if ($list) {
                $this->assign('list', $list);
            } else {
                $this->redirect('Games/index');
            }
        } else {
            $this->redirect('Games/index');
        }
        return $this->fetch();
    }

    /**
     * 处理
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function modify_handle()
    {
        if (request()->isAjax()) {
            $data = $_POST;
            if (empty($data['id'])) {
                $r = msg_handle('修改错误', 0);
            } elseif (empty($data['name'])) {
                $r = msg_handle('名称不能为空', 0);
            } elseif (empty($data['content'])) {
                $r = msg_handle('内容不能为空', 0);
            } else {
                $list = $this->Info->where(array('id' => $data['id']))->find();
                if (empty($list)) {
                    $r = msg_handle('修改信息不存在', 0);
                } else {
                    if ($this->Info->modify_log($data)) {
                        $d['uid']=session('admin')['id'];
                        $d['time']=time();
                        $d['desc']='管理员'.session('admin')['name'].'修改了平台介绍';
                        $this->admin_log($d);
                        $r = msg_handle('修改成功', 1);
                    } else {
                        $r = msg_handle('修改失败', 0);
                    }
                }
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return json($r);
    }

}
