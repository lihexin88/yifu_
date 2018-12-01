<?php

namespace app\monitor\controller;

use think\Request;

use app\common\model\Play;

class Artient extends Common
{
    private $Play;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Play = new Play();
     
    }

     /*
     * 广告
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
        $list = $this->Play->query_log($map, $current_page, $this->num);
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
    public function artient_edit(){
        $id=Request::instance()->param('id');
        if ($id) {
            $list = $this->Play->where('id='.$id)->find();
            if ($list) {
                $this->assign('list', $list);
            } else {
                $this->redirect('Artient/index');
            }
        }else{
            $list=array('id'=>'','sort'=>'','source'=>'','pic'=>'','status'=>'1','url'=>'','type'=>'1','name'=>'','alias'=>'');
            $this->assign('list', $list);
        }
        return $this->fetch();
    }
    public function artient_add_edit(){
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            $data['time'] = time();
            if (empty($data['name'])) {
                $r = msg_handle('请输入广告标题', 0);
                return json($r);
            }elseif (empty($data['id'])){
                $list = $this->Play->insert($data);
            }else{
                $map['name']=$data['name'];
                $map['sort']=$data['sort'];
                $map['source'] = $data['source'];
                $map['pic'] = $data['pic'];
                $map['status'] = $data['status'];
                $map['type'] = $data['type'];
                $map['url'] = $data['url'];
                $list = $this->Play->where('id',$data['id'])->update($map);
            }
            if ($list) {
                    //设置成功后跳转页面的地址
                    $this->success('操作成功', 'Artient/index');
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
            $list=$this->Play->where('id='.$id)->delete();
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Artient/index');
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

