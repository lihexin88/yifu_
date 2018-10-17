<?php

namespace app\monitor\controller;

use think\Request;

use app\common\model\Agentinfo;

class Agentlast extends Common
{
    private $Agentinfo;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Agentinfo = new Agentinfo();
     
    }

     /*
     * 员工
     * 
     */
    public function index(){
        $map='';
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map['aid'] = 1;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Agentinfo->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
   
     /**
     * 添加/修改
     * @return mixed
     */
    public function edit(){
        $id=Request::instance()->param('id');
        $aid=Request::instance()->param('aid');
        if ($id) {
            $list = $this->Agentinfo->where('id='.$id)->find();
            if ($list) {
                $this->assign('list', $list);
            } else {
                $this->redirect('Agentlast/index');
            }
        }else{
            $list=array('id'=>'','bank'=>'','num_name'=>'','password'=>'','cont_name'=>'','bond'=>'','bank_status'=>'1','name'=>'','status'=>'1','cont_phone'=>'','time'=>'','com_fee'=>'','defe_ratio'=>'','desc'=>'');
            $list['aid'] = $aid;
            $this->assign('list', $list);
        }
        return $this->fetch();
    }
    public function agent_edit(){

        if (request()->isAjax()) {
            $data = $_POST['arr'];
            $data['time'] = time();
            if (empty($data['id'])) {
                $da = $this->Agentinfo->where('num_name='.$data['num_name'])->find();
            }else{
                $da = $this->Agentinfo->where(" id != {$data['id']}  AND num_name='{$data['num_name']}' ")->find();
            }
            if (empty($data['num_name'])) {
                $r = msg_handle('员工编号不能为空', 0);
            }elseif(empty($data['password'])){
                $r = msg_handle('密码不能为空', 0);
            }elseif($da){
                $r = msg_handle('员工编号已存在', 0);
            }else{
                if (empty($data['id'])){
                    $list = $this->Agentinfo->insert($data);
                }else{
                    $map['name']=$data['name'];
                    $map['bank'] = $data['bank'];
                    $map['num_name'] = $data['num_name'];
                    $map['password'] = $data['password'];
                    $map['cont_name'] = $data['cont_name'];
                    $map['bond'] = $data['bond'];
                    $map['bank_status'] = $data['bank_status'];
                    $map['status'] = $data['status'];
                    $map['cont_phone'] = $data['cont_phone'];
                    $map['com_fee'] = $data['com_fee'];
                    $map['desc'] = $data['desc'];
                    $map['defe_ratio'] = $data['defe_ratio'];
                    $list = $this->Agentinfo->where('id='.$data['id'])->update($map);
                }
                if ($list) {
                    //设置成功后跳转页面的地址
                    $this->success('操作成功', 'Agentlast/index');
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
     * 删除
     * @return mixed
     */
    public function del(){
        if (request()->isAjax()) {
            $id = $_POST['id'];
            $list=$this->Agentinfo->where('id='.$id)->delete();
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Agentlast/index');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
    }
}



















