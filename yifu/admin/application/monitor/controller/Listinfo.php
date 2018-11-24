<?php

namespace app\monitor\controller;

use think\Request;

use app\common\model\Lists;

class Listinfo extends Common
{
    private $Lists;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Lists = new Lists();
     
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
        $list = $this->Lists->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
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
            $list = $this->Lists->where('id='.$id)->find();
            if ($list) {
                $this->assign('list', $list);
            } else {
                $this->redirect('Listinfo/index');
            }
        }else{
            $list=array('id'=>'','shares'=>'','ran'=>'','num'=>'','profit'=>'','name'=>'','buy_time'=>'','type'=>'1','phone'=>'');
            $this->assign('list', $list);
        }
        return $this->fetch();
    }
    public function list_add_edit(){

        if (request()->isAjax()) {
            $data = $_POST['arr'];
            $data['time'] = time();
            if (empty($data['name'])) {
                $r = msg_handle('会员名不能为空', 0);
            }elseif(empty($data['phone'])){
                $r = msg_handle('手机号不能为空', 0);
            }elseif(empty($data['profit'])){
                $r = msg_handle('盈利不能为空', 0);
            }elseif(empty($data['num'])){
                $r = msg_handle('策略数不能为空', 0);
            }elseif(empty($data['ran'])){
                $r = msg_handle('排名不能为空', 0);
            }elseif(empty($data['shares'])){
                $r = msg_handle('股票不能为空', 0);
            }else{
                if (empty($data['id'])){
                    $list = $this->Lists->insert($data);
                }else{
                    $map['name']=$data['name'];
                    $map['shares']=$data['shares'];
                    $map['ran'] = $data['ran'];
                    $map['num'] = $data['num'];
                    $map['type'] = $data['type'];
                    $map['buy_time'] = $data['buy_time'];
                    $map['phone'] = $data['phone'];
                    $map['profit'] = $data['profit'];
                    $list = $this->Lists->where('id='.$data['id'])->update($map);
                }
                if ($list) {
                    //设置成功后跳转页面的地址
                    $this->success('操作成功', 'Listinfo/index');
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
            $list=$this->Lists->where('id='.$id)->delete();
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Listinfo/index');
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



















