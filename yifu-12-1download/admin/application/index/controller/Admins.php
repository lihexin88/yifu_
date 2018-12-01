<?php

namespace app\index\controller;
use think\Request;
use app\common\model\Admin;
use app\common\model\AdminRole;
use app\common\model\AdminFunction;
use app\common\model\Relation;

/*
 * 用户申请控制器
 */

class Admins extends Common
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Admin = new Admin();
        $this->AdminRole = new AdminRole();
        $this->AdminFunction = new AdminFunction();
        $this->Relation = new Relation();

    }

    /**
     * @return mixed
     * 管理员列表
     */
    public function index()
    {
        header("Content-type: text/html; charset=utf-8");
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Admin->query_log($map, $current_page, $this->num);
//         return json($list);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
    /**
     * 角色管理员
     * @return mixed
     */
    public function role(){
        $map = "";
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->AdminRole->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('data', $list['data']);
        return $this->fetch();
    }

    /**
     * 添加角色
     */
    public function edit_role(){
        $id=Request::instance()->param('id');
        $functions=$this->AdminFunction->where('pid',0)->select();
        if($id){
            if(request()->isGet()) {
                $list = $this->AdminRole->where(array('id' => $_GET['id']))->find();
                if ($list) {
                    $this->assign('list', $list);
                } else {
                    $this->redirect('Admins/index');
                }
                $da = $this->Relation->where('roid='.$id)->select();
                if ($da) {
                    foreach($da as $row){
                        $rids[]=$row['fuid'];
                    }
                    $this->assign('rids', $rids);
                }else{
                    $this->redirect('Admins/index');
                }
            }else {
                $this->redirect('Admins/index');
            }
        }else{
            $list=array('id'=>'','name'=>'','rids'=>'');
            $this->assign('list', $list);
        }
        $this->assign('func',$functions);
        return $this->fetch();
    }
     /**
     * 添加角色
     */
    public function edit_roles(){
        $id=Request::instance()->param('id');
        $functions=$this->AdminFunction->where('pid',0)->select();
        if($id){
            if(request()->isGet()) {
                $list = $this->AdminRole->where(array('id' => $_GET['id']))->find();
                if ($list) {
                    $this->assign('list', $list);
                } else {
                    $this->redirect('Admins/index');
                }
                $da = $this->Relation->where('roid='.$id)->select();
                if ($da) {
                    foreach($da as $row){
                        $rids[]=$row['fuid'];
                    }
                    $this->assign('rids', $rids);
                }else{
                    $this->redirect('Admins/index');
                }
            }else {
                $this->redirect('Admins/index');
            }
        }else{
            $list=array('id'=>'','name'=>'','rids'=>'');
            $this->assign('list', $list);
        }
        $this->assign('func',$functions);
        return $this->fetch();
    }

     /**
     * 执行添加修改角色
     */
     public function role_add(){
        if (request()->isAjax()) {
            $id=$_POST['id'];
            $data = $_POST['arr'];
            $func = $_POST['func'];
            $cou= count($func);
            unset($data['functions[']);

            if ($id) {
                $name = $this->AdminRole->where(" id != {$id}  AND name='{$data['name']}' ")->find();
            }else{
                $name = $this->AdminRole->where(array('name' => $data['name']))->find();
            }
            if (empty($data['name'])) {
                $r = msg_handle('请输入角色名称', 0);
                return $r;
            } elseif (empty($func)) {
                $r = msg_handle('请选择角色', 0);
                return $r;
            } elseif ($name) {
                $r = msg_handle('角色名称已存在', 0);
                return $r;
            }
           if ($id) {
                $this ->Relation->where('roid='.$id)->delete();
                $this->AdminRole ->where('id',$id)->setField('name',  $data['name']);
                if ($cou==1) {
                    $d['roid']=$id;
                    $d['fuid'] = $func;
                    $list= $this->Relation->add($d);
                }else{
                    foreach($func as $v){
                        $d['roid']=$id;
                        $d['fuid'] = $v;
                       $list= $this->Relation->add($d);
                    }
                }

               //修改
            }else{
                //添加
                $list = $this->AdminRole->add($data);
                if ($cou==1) {
                    $d['roid']=$list;
                    $d['fuid'] = $func;
                    $this->Relation->add($d);
                }else{
                    foreach($func as $v){
                        $d['roid']=$list;
                        $d['fuid'] = $v;
                        $this->Relation->add($d);
                    }
                }
            }
        if ($list) {
            //设置成功后跳转页面的地址
            $this->success('操作成功', 'Admins/index');
        } else {
            //错误页面的默认跳转页面是返回前一页，通常不需要设置
            $this->error('操作失败');
        }

        }else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
     }

     /**
     * 删除
     * @return mixed
     */
    public function role_del(){
        $id = $_GET['id'];
        $list=$this->AdminRole->where('id='.$id)->delete();
        $this ->Relation->where('roid='.$id)->delete();
        if ($list) {
            //设置成功后跳转页面的地址
            $this->success('操作成功', 'Admins/role');
        } else {
            //错误页面的默认跳转页面是返回前一页，通常不需要设置
            $this->error('操作失败');
        }
    }
     /**
     * 添加修改管理员
     */
    public function admin(){
        $id=Request::instance()->param('id');
        $functions=$this->Admin->select();
        $role=$this->AdminRole->select();
        if($id){
            if(request()->isGet()) {
                $list = $this->Admin->where(array('id' => $_GET['id']))->find();
                if ($list) {
                    $this->assign('list', $list);
                } else {
                    $this->redirect('Admins/admin');
                }
            }else {
                $this->redirect('Admins/index');
            }
        }else{
            $list=array('id'=>'','name'=>'','password'=>'','status'=>'','ro_id'=>'');
            $this->assign('list', $list);
        }
        $this->assign('func',$functions);
        $this->assign('role',$role);
        return $this->fetch();
    }
    
     /**
     * 执行添加修改角色管理员
     */
     public function admin_add(){
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['id'])) {
                $name = $this->Admin->where(array('name' => $data['name']))->find();
            }else{
                $name = $this->Admin->where(" id != {$data['id']}  AND name='{$data['name']}' ")->find();
            }
            if (empty($data['name'])) {
                $r = msg_handle('请输入管理员名称', 0);
            } elseif (empty($data['password'])) {
                $r = msg_handle('请输入密码', 0);
            } elseif (empty($data['pass'])) {
                $r = msg_handle('请输入确认密码', 0);
            } elseif ($data['password'] != $data['pass']) {
                $r = msg_handle('两次密码输入不一致', 0);
            } elseif (empty($data['ro_id'])) {
                $r = msg_handle('请选择角色', 0);
            } elseif ($name) {
                $r = msg_handle('管理员名称已存在', 0);
            } else {
                $list = $this->Admin->add_edit($data);
                $da['uid'] = session('admin')['id'];
                $da['time'] = time();
                if ($data['id']) {
                    $da['desc'] = '管理员' . session('admin')['name'] . '修改了管理员' . $data['name'];
                }else{
                    $da['desc'] = '管理员' . session('admin')['name'] . '添加了管理员' . $data['name'];
                }
                $this->admin_log($da);
                if ($list) {
                    //设置成功后跳转页面的地址
                    $this->success('操作成功', 'Admins/index');
                } else {
                    //错误页面的默认跳转页面是返回前一页，通常不需要设置
                    $this->error('操作失败');
                }

            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
     }
    /**
     * 权限功能列表
     * @return mixed
     */
    public function functions(){
        $name=input('get.name');
        $map['name']=['like',"%$name%"];
        $current_page = page_judge(input('get.page'));
        $list = $this->AdminFunction->query_log($map, $current_page,$this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(15));
        $this->assign('page', $page);
        $this->assign('data', $list['data']);
        $this->assign('name', $name);
        return $this->fetch();
    }
    /**
     * 添加/修改权限功能
     * @return mixed
     */
    public function functions_add(){
        $id=Request::instance()->param('id');
        $functions=$this->AdminFunction->where('pid',0)->select();
        if ($id) {
            $list = $this->AdminFunction->where('id='.$id)->find();
            if ($list) {
                $this->assign('list', $list);
            } else {
                $this->redirect('Admins/functions');
            }
        }else{
            $list=array('id'=>'','name'=>'','url'=>'','pid'=>'','code'=>'');
            $this->assign('list', $list);
        }
        $this->assign('func',$functions);
        return $this->fetch();
    }
    public function functions_add_edit(){
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            $data['time'] = time();
            if (empty($data['name'])) {
                $r = msg_handle('请输入功能名称', 0);
            }elseif (empty($data['id'])){
                $list = $this->AdminFunction->insert($data);
            }else{
                $map['pid']=$data['pid'];
                $map['url']=$data['url'];
                $map['name'] = $data['name'];
                $map['time'] = time();
                $list = $this->AdminFunction->where('id='.$data['id'])->update($map);
            }
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Admins/functions');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
    }
    /**
     * 删除
     * @return mixed
     */
    public function del(){
        $id = $_GET['id'];
        $list=$this->AdminFunction->where('id='.$id)->delete();
        if ($list) {
            //设置成功后跳转页面的地址
            $this->success('操作成功', 'Admins/functions');
        } else {
            //错误页面的默认跳转页面是返回前一页，通常不需要设置
            $this->error('操作失败');
        }
    }

}