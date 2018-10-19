<?php

namespace app\index\controller;

use think\Request;

use app\common\model\Bank;

class Banks extends Common
{
    private $Bank;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Bank = new Bank();
     
    }

     /*
     * 帮助中心
     * 
     */
    public function index(){
        $map='';
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
//        Bank Model 中原地址拼接为
        /**
         *        $value['pic'] =config('HOSTADMIN').$value['pic'].'.jpg' ;
         * 暂时改为固定链接拼接
         */
        $list = $this->Bank->query_log($map, $current_page, $this->num);
//        outpause($list['data'],'图片',0);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
//        echo "<pre>";
//        print_r($list);exit;
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
   
        /**
     * 添加/修改功能
     * @return mixed
     */
    public function edit(){
        $id=Request::instance()->param('id');
        if ($id) {
            $list = $this->Bank->where('id='.$id)->find();
            if ($list) {
                $list['pic'] = 'http://www.admin.com'.$list['pic'].'.jpg';
                $this->assign('list', $list);
            } else {
                $this->redirect('Banks/index');
            }
        }else{
            $list=array('id'=>'','code'=>'','name'=>'','status'=>'1');
            $this->assign('list', $list);
        }
        return $this->fetch();
    }
    public function banks_add_edit(){

        if (request()->isAjax()) {
            $data = $_POST['arr'];
            $data['time'] = time();

            if (empty($data['name'])) {
                $r = msg_handle('银行名称不能为空', 0);
            }else{
                if (empty($data['id'])){
                    $da = $this->Bank->where("name='{$data['name']}' ")->find();
                    if($da){
                        $r = msg_handle('银行名称已存在', 0);
                        return $r;
                    }
                    $list = $this->Bank->insert($data);
                }else{
                    $where="name='{$data['name']}' AND id != {$data['id']}";
                    $da = $this->Bank->where($where)->find();
                    if($da){
                        $r = msg_handle('银行名称已存在', 0);
                        return $r;
                    }
                    $map['name']=$data['name'];
                    $map['code']=$data['code'];
                    $map['status'] = $data['status'];
                    $map['pic'] = $data['pic'];
//                    outpause($map);
                    $map['time'] = time();
                    $list = $this->Bank->where('id='.$data['id'])->update($map);
                }
                if ($list) {
                    //设置成功后跳转页面的地址
                    $this->success('操作成功', 'Banks/index');
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
            $list=$this->Bank->where('id='.$id)->delete();
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Banks/index');
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



















