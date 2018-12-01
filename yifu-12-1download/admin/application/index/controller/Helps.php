<?php

namespace app\index\controller;

use think\Request;

use app\common\model\Helptype;

class Helps extends Common
{
    private $Helptype;
    private $Help;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Helptype = new Helptype();
     
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
        $list = $this->Helptype->query_log($map, $current_page, $this->num);
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
    public function help_edit(){
        $id=Request::instance()->param('id');
        $data = $this->Helptype->where('pid=0')->select();
        if ($id) {
            $list = $this->Helptype->where('id='.$id)->find();
            if ($list) {
                $this->assign('list', $list);
            } else {
                $this->redirect('Helps/index');
            }
        }else{
            $list=array('id'=>'','sort'=>'','source'=>'','img'=>'','status'=>'1','title'=>'','pid'=>'','name'=>'','desc'=>'');
            $this->assign('list', $list);
        }
        $this->assign('data', $data);
        return $this->fetch();
    }
    public function help_add_edit(){
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            $data['time'] = time();
            if (empty($data['name'])) {
                $r = msg_handle('请输入名称', 0);
            }elseif (empty($data['id'])){
                // return $data;
                $list = $this->Helptype->insert($data);
            }else{
                $map['pid']=$data['pid'];
                $map['name']=$data['name'];
                $map['sort']=$data['sort'];
                $map['source'] = $data['source'];
                $map['desc'] = $data['desc'];
                $list = $this->Helptype->where('id='.$data['id'])->update($map);
            }
            if ($list) {
                    //设置成功后跳转页面的地址
                    $this->success('操作成功', 'Helps/index');
                } else {
                    //错误页面的默认跳转页面是返回前一页，通常不需要设置
                    $this->error('操作失败,未改动数据!');
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
            $pid = $this->Helptype->where('id='.$id)->find();
            $data = $this->Helptype->where('pid='.$pid['id'])->find();
            if ($data) {
                $r = msg_handle('请先删除下级分类', 0);
            }else{
                $list=$this->Helptype->where('id='.$id)->delete();
                if ($list) {
                    //设置成功后跳转页面的地址
                    $this->success('操作成功', 'Helps/index');
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
}



















