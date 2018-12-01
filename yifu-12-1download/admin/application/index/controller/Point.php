<?php

namespace app\index\controller;

use think\Request;

use app\common\model\Points;
use app\common\model\Account;

class Point extends Common
{
    private $Points;
    private $Account;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Points = new Points();
        $this->Account = new Account();
     
    }

     /*
     * 帮助中心
     * 
     */
    public function index(){
        $map='';
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Points->query_log($map, $current_page, $this->num);
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
            $list = $this->Points->where('id='.$id)->find();
            if ($list) {
                $this->assign('list', $list);
            } else {
                $this->redirect('Point/index');
            }
        }else{
            $list=array('id'=>'','time'=>'','update_time'=>'','number'=>'','admin'=>'','ip'=>'');
            $this->assign('list', $list);
        }
        return $this->fetch();
    }
    public function point_edit(){

        if (request()->isAjax()) {
            $data = $_POST['arr'];
            $data['time'] = time();
            $data['ip'] = request()->ip();
            if (empty($data['number'])) {
                $r = msg_handle('金额不能为空', 0);
            }else{
                if (empty($data['id'])){
                    $list = $this->Points->insert($data);
                }else{
                    $map['number']=$data['number'];
                    $map['admin'] = $data['admin'];
                    $map['update_time'] = time();
                    $map['ip'] = request()->ip();
                    $list = $this->Points->where('id='.$data['id'])->update($map);
                }
                if ($list) {
                    //设置成功后跳转页面的地址
                    $this->success('操作成功', 'Point/index');
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
            $list=$this->Points->where('id='.$id)->delete();
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Point/index');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
    }
     public function operate(){

        $map='';
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Account->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $account=$this->Account->sum('account');
        $total=$this->Account->sum('total');
        $wit_total=$this->Account->sum('wit_total');
        $rec_total=$this->Account->sum('rec_total');
        $capital_total=$this->Account->sum('capital_total');
        $trade_total=$this->Account->sum('trade_total');
        $fee_total=$this->Account->sum('fee_total');
        $push_total=$this->Account->sum('push_total');
        $server_total=$this->Account->sum('server_total');
        $this->assign('account', $account);
        $this->assign('total', $total);
        $this->assign('wit_total', $wit_total);
        $this->assign('rec_total', $rec_total);
        $this->assign('capital_total', $capital_total);
        $this->assign('trade_total', $trade_total);
        $this->assign('fee_total', $fee_total);
        $this->assign('push_total', $push_total);
        $this->assign('server_total', $server_total);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

}



















