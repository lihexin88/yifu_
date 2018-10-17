<?php

namespace app\monitor\controller;

use think\Request;
// use app\common\model\ConfigRatio;
use app\common\model\Rechar;
use app\common\model\Level;

class System extends Common
{
    // private $ConfigRatio;
    private $Rechar;
    private $Level;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        // $this->ConfigRatio = new ConfigRatio();
        $this->Rechar = new Rechar();
        $this->Level = new Level();
     
    }

     /*
     * 系统设置
     * 
     */
    public function index(){
        $map="";
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Rechar->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }
    public function edit(){
        $id = $_GET['id'];
        $list = $this->Rechar->where('id='.$id)->find();
        $this->assign('list', $list);
        return $this->fetch();
    }
    public function system_add_edit(){

        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['title'])) {
                $r = msg_handle('网站名称不能为空', 0);
            }elseif(empty($data['tell'])){
                $r = msg_handle('电话不能为空', 0);
            }elseif(empty($data['qq'])){
                $r = msg_handle('客服qq不能为空', 0);
            }elseif(empty($data['web'])){
                $r = msg_handle('网站网址不能为空', 0);
            }elseif(empty($data['copyright'])){
                $r = msg_handle('网站版权信息不能为空', 0);
            }elseif(empty($data['img'])){
                $r = msg_handle('logo图片不能为空', 0);
            }elseif(empty($data['wechat_img'])){
                $r = msg_handle('微信二维码不能为空', 0);
            }elseif(empty($data['app_img'])){
                $r = msg_handle('APP下载图片不能为空', 0);
            }else{
                $map['title']=$data['title'];
                $map['tell']=$data['tell'];
                $map['qq'] = $data['qq'];
                $map['web'] = $data['web'];
                $map['copyright'] = $data['copyright'];
                $map['img'] = $data['img'];
                $map['wechat_img'] = $data['wechat_img'];
                $map['app_img'] = $data['app_img'];
                $list = $this->Rechar->where('id='.$data['id'])->update($map);
                if ($list) {
                    //设置成功后跳转页面的地址
                    $this->success('操作成功', 'System1/index');
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
 
}



















