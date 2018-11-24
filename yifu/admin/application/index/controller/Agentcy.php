<?php

namespace app\index\controller;

use think\Request;
use app\common\model\Agent;
use think\Controller;
use think\Db;

class Agentcy extends Common
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Agent = new Agent();

    }

    /*
       代理商列表
      */
    public function index()
    {
        $map = "";
        $name = input('get.name/s');
        if ($name) {
            $map['username|phone'] = ['like', $name];
        }
         $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
         

         $current_page = page_judge(input('get.page/d'));
         $list = $this->Agent->query_log($map, $current_page, $this->num);
         $page = page_handling($list['num'], $current_page, $this->show, $list['total']);

         foreach($list["data"] as $key=>$val){
            if($val["p_agent"] < 1){
                $list["data"][$key]["p_agent"]=array("name"=>"无");
            }else{
                $list["data"][$key]["p_agent"]=$this->Agent->where(array('id'=>$val["p_agent"]))->find();
            }
         }

         if(isset($_GET["excel"])){
            if($_GET["excel"]){
                //$list = $this->User->query($_post["excel"]);
                $this->export_agnet($list['data']);
            }
         }

        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('name', $name);
        $this->assign('start_query', input('get.start_query'));
        $this->assign('end_query', input('get.end_query'));
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    public function generalize(){
        $map = "";
        $name = input('get.name/s');
        if ($name) {
            $map['username|phone'] = ['like', $name];
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
         

         $current_page = page_judge(input('get.page/d'));
         $list = $this->Agent->query_log($map, $current_page, $this->num);
         $page = page_handling($list['num'], $current_page, $this->show, $list['total']);

         foreach($list["data"] as $key=>$val){
            if($val["p_agent"] < 1){
                $list["data"][$key]["p_agent"]=array("name"=>"无");
            }else{
                $list["data"][$key]["p_agent"]=$this->Agent->where(array('id'=>$val["p_agent"]))->find();
            }
         }

         if(isset($_GET["excel"])){
            if($_GET["excel"]){
                //$list = $this->User->query($_post["excel"]);
                $this->export_agnet($list['data']);
            }
         }

        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('name', $name);
        $this->assign('start_query', input('get.start_query'));
        $this->assign('end_query', input('get.end_query'));
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /*
    修改代理商状态
     */
    public function edit()
    {
        $id = Request::instance()->param('id');
        if (empty($id)) {
            $r = msg_handle('操作失败', 0);
        } else {
            $user = $this->Agent->where('id',$id)->find();
            if ($user['status'] != 1) {
                $data['status'] = 1;
            } else {
                $data['status'] = 2;
            }
            $data['modify_time'] = time();
            $res = $this->Agent->where('id',$id)->update($data);
            if ($res) {
                $r = msg_handle('操作成功', 1);
            } else {
                $r = msg_handle('操作失败', 0);

            }
        }
        return json($r);
    }
    /*
    删除代理商
     */
    public function delagen(){

        if(isset($_POST["id"])){
            $delete=$this->Agent->where(array("id"=>$_POST["id"]))->delete();
            if($delete){
                $r = msg_handle('操作成功', 1);
            }else{
                $r = msg_handle('操作失败', 0);
            }
         }else{
            $r = msg_handle('操作失败', 0);
         }
         return json($r);

    }

    /*
    添加代理商
     */
    public function agent_edit(){
        $id=Request::instance()->param('id');
        if(isset($id)){
            $id = Request::instance()->param('id');
            $list = $this->Agent->where('id=' . $id)->find();
            $this->assign('list', $list);
        }else{
            $list="";
            $this->assign('list', $list);
        }
        $users = $this->Agent->select();
        $this->assign("users",$users);
        return $this->fetch();
    }

    /*
    执行添加代理商
     */
    public function update_agent(){
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['username'])) {
                $r = msg_handle('请输入账户名称', 0);
                return $r;
            } elseif (empty($data['name'])) {
                $r = msg_handle('请输入登录名', 0);
                return $r;
            } elseif (empty($data['phone'])) {
                $r = msg_handle('请输入手机号', 0);
                return $r;
            } elseif (empty($data['commission_proportion'])) {
                $r = msg_handle('请选择佣金比例', 0);
                return $r;
            } else {

                $parents=$this->find_parents($data["p_agent"]);
                if ($parents) {//修改用户的path字段
                        $path="0";
                        $suoarray=array_reverse($parents);
                        foreach($suoarray as $key=>$val){
                            $path.=",{$val['id']}";
                        }
                }
                $data["time"]=time();
                if(!empty($data['id'])){
                    $path.=",{$data['id']}";
                    $data['path']=$path;
                    $list = $this->Agent->where('id='.$data['id'])->update($data);
                }else{
                    if (empty($data['password'])) {
                        $r = msg_handle('请输入密码', 0);
                        return $r;
                    }else{
                       $data['password']=md5($data['password']); 
                    }
                    $list = $this->Agent->insertGetId($data);;
                    $path.=",{$list}";
                    $generalize_code="100".$list;
                    $this->Agent->where(array("id"=>$list))->update(array("path"=>$path,"generalize_code"=>$generalize_code));  
                }
                
                
            }
            if ($list) {
                //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                $this->success('操作成功', 'Transaction/index');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败,未改动数据!');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return json($r);
    }

    /*
    导出代理商信息
     */
    public function export_agnet($xlsData){
         //$xlsData = $this->User->select();
        Vendor('PHPExcel.Classes.PHPExcel');//调用类库,路径是基于vendor文件夹的
        Vendor('PHPExcel.Classes.PHPExcel.Worksheet.Drawing');
        Vendor('PHPExcel.Classes.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
 
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G,H,I,J,K,L");
        $arrHeader = array('账户','登录名','手机号','佣金总额','佣金余额','佣金比例','佣金提现总额','上级代理商','账户状态','推荐码','创建时间','注册链接');
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        };
        //填充表格信息
        foreach($xlsData as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['username']);
            $objActSheet->setCellValue('B'.$k, $v['name']);
            // // 图片生成
            // $objDrawing[$k] = new \PHPExcel_Worksheet_Drawing();
            // $objDrawing[$k]->setPath('public/static/admin/images/profile_small.jpg');
            // // 设置宽度高度
            // $objDrawing[$k]->setHeight(40);//照片高度
            // $objDrawing[$k]->setWidth(40); //照片宽度
            // /*设置图片要插入的单元格*/
            // $objDrawing[$k]->setCoordinates('C'.$k);
            // // 图片偏移距离
            // $objDrawing[$k]->setOffsetX(30);
            // $objDrawing[$k]->setOffsetY(12);
            // $objDrawing[$k]->setWorksheet($objPHPExcel->getActiveSheet());
            // 表格内容
            $objActSheet->setCellValue('C'.$k, $v['phone']);
            $objActSheet->setCellValue('D'.$k, $v['commission_all']);
            $objActSheet->setCellValue('E'.$k, $v['commission_balance']);
            $objActSheet->setCellValue('F'.$k, $v['commission_proportion']."%");
            $objActSheet->setCellValue('G'.$k, $v['commission_withdraw_all']);
            $objActSheet->setCellValue('H'.$k, $v['p_agent']['name']);
            $objActSheet->setCellValue('I'.$k, $v['status']);
            $objActSheet->setCellValue('J'.$k, $v['generalize_code']);
            $objActSheet->setCellValue('K'.$k, $v['time']);
            $objActSheet->setCellValue('L'.$k, $v['generalize_link']);
 
 
            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(20);
        }
 
        $width = array(20,20,15,10,10,30,10,15);
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth($width[3]);
        $objActSheet->getColumnDimension('B')->setWidth($width[3]);
        $objActSheet->getColumnDimension('C')->setWidth($width[3]);
        $objActSheet->getColumnDimension('D')->setWidth($width[3]);
        $objActSheet->getColumnDimension('E')->setWidth($width[3]);
        $objActSheet->getColumnDimension('F')->setWidth($width[3]);
        $objActSheet->getColumnDimension('G')->setWidth($width[3]);
        $objActSheet->getColumnDimension('H')->setWidth($width[3]);
        $objActSheet->getColumnDimension('I')->setWidth($width[3]);
        $objActSheet->getColumnDimension('J')->setWidth($width[3]);
        $objActSheet->getColumnDimension('K')->setWidth($width[3]);
        $objActSheet->getColumnDimension('L')->setWidth($width[3]);

        $outfile = "代理商信息表.xls";
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outfile.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }





    public function find_me($id){
        $user=$this->Agent->where(array("id"=>$id))->find();
        return $user;
    }

    public function find_parents($id){
        $zishen=$this->find_me($id);
        $shuzu=array();
        $shuzu[1]=$zishen;
        if($zishen){
            if($zishen["p_agent"] != 0){
                $shuzu[1]=$zishen;
                $preferral=$zishen["p_agent"];
                $count=$this->User->count();
                $i=1;
                do{
                    if($preferral != 0){
                            $zishens=$this->find_me($preferral);
                            $preferral=$zishens["p_agent"];
                            $i++;
                            $shuzu[$i]=$zishens;
                        }else{
                            $i=$count+1;
                        }
                }while($i<$count);
            }else{
                $shuzu[1]=$zishen;
            }
        }
        return $shuzu;
    }


}


?>