<?php

namespace app\index\controller;

use think\Request;
use app\common\model\Flow;
use app\common\model\User;
use app\common\model\Account;
use app\common\model\Order;
use app\common\model\Agent;
use app\common\model\Entrust;
use app\common\model\Contract;
use app\common\model\CapitalFlow;
use app\common\model\Deal;
use app\common\model\Depot;

use think\Controller;
use think\Db;
use think\Session;

class Reporting extends Common
{
    private $Flow;
    private $Users;
    private $Account;
    private $Order;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Flow = new Flow();
        $this->User = new User();
        $this->Account = new Account();
        $this->Order = new Order();
        $this->Agent = new Agent();
        $this->Enturst = new Entrust();
        $this->Constract = new Contract();
        $this->CapitalFlow = new CapitalFlow();
        $this->Deal = new Deal();
        $this->Depot = new Depot();
    }

     /*
     * 委托记录
     */
    public function index(){
        $map =array();
        $name = input('get.name/s');
        if ($name) {
            $user=$this->User->where(array("phone|name"=>['like',$name]))->find();
            if($user){
                $map['uid'] = ['like', $user["id"]];
            }else{
                $map['uid'] = ['like', 0];
            }
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $map["status"]=['<','2'];
        $list = $this->Enturst->query_log($map, $current_page, $this->num);
//        return json($map);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        foreach($list["data"] as $k=>$v){
            $user=$this->User->where(array("id"=>$v["uid"]))->find();
            $p_user=$this->User->where(array("id"=>$user["reid"]))->find();
            $agent=$this->Agent->where(array("id"=>$user["agent"]))->find();
            $list["data"][$k]["user"]=$user;
            $list["data"][$k]["p_user"]=$p_user;
//            outpause($p_user);
            $list["data"][$k]["agent"]=$agent;
        }

        if(isset($_GET["excel"])){
            if($_GET["excel"]){
                //$list = $this->User->query($_post["excel"]);
                $this->export_index($list['data']);
            }
         }
        $this->assign('name', $name);
        $this->assign('start_query', input('get.start_query'));
        $this->assign('end_query', input('get.end_query'));
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /*
    资金记录
     */
    public function capital(){
        $map = "";
        $name = input('get.name/s');
        if ($name) {
            $user=$this->User->where(array("phone|name"=>['like',$name]))->find();
            if($user){
                $map['uid'] = ['like', $user["id"]];
            }else{
                $map['uid'] = ['like', 0];
            }
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->CapitalFlow->query_log($map, $current_page, $this->num);
//        outpause($list);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        foreach($list["data"] as $k=>$v){
            $list["data"][$k]["user"]=$this->User->where(array("id"=>$v["uid"]))->find();
            $list["data"][$k]["account"]=$this->Account->where(array("uid"=>$v["uid"]))->find();
        }

        if(isset($_GET["excel"])){
            if($_GET["excel"]){
                //$list = $this->User->query($_post["excel"]);
                $this->export_capital($list['data']);
            }
         }
        $this->assign('name', $name);
        $this->assign('start_query', input('get.start_query'));
        $this->assign('end_query', input('get.end_query'));
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /*
    成交记录
     */
    public function bargain(){
        $map = "";
        $name = input('get.name/s');
        if ($name) {
            $user=$this->User->where(array("phone|name"=>['like',$name]))->find();
            if($user){
                $map['uid'] = ['like', $user["id"]];
            }else{
                $map['uid'] = ['like', 0];
            }
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
//        $map["status"]=2;
        $list = $this->Deal->query_log($map, $current_page, $this->num);
//        outpause($list);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        foreach($list["data"] as $k=>$v){
            $user=$this->User->where(array("id"=>$v["uid"]))->find();
            $p_user=$this->User->where(array("id"=>$user["reid"]))->find();
//            outpause($user);
            $agent=$this->Agent->where(array("id"=>$user["agent"]))->find();
//            sql('sn_agent');
//            outpause($agent);
            $list["data"][$k]["user"]=$user;
            $list["data"][$k]["p_user"]=$p_user;
            $list["data"][$k]["agent"]=$agent['name'];
        }

        if(isset($_GET["excel"])){
            if($_GET["excel"]){
                //$list = $this->User->query($_post["excel"]);
                $this->export_bargain($list['data']);
            }
         }
        $this->assign('name', $name);
        $this->assign('start_query', input('get.start_query'));
        $this->assign('end_query', input('get.end_query'));
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /*
    平仓记录
     */
    public function close(){
        $map = "";
        $name = input('get.name/s');
        if ($name) {
            $user=$this->User->where(array("phone|name"=>['like',$name]))->find();
            if($user){
                $map['uid'] = ['like', $user["id"]];
            }else{
                $map['uid'] = ['like', 0];
            }
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
//        $map["status"]=2;
//        $map["mold"]=1;
        $list = $this->Depot->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        foreach($list["data"] as $k=>$v){
            $user=$this->User->where(array("id"=>$v["uid"]))->find();
            $p_user=$this->User->where(array("id"=>$user["reid"]))->find();
            $agent=$this->Agent->where(array("id"=>$user["agent"]))->find();

            $list["data"][$k]["user"]=$user;
            $list["data"][$k]["p_user"]=$p_user;
            $list["data"][$k]["agent"]=$agent;
        }

        if(isset($_GET["excel"])){
            if($_GET["excel"]){
                //$list = $this->User->query($_post["excel"]);
                $this->export_close($list['data']);
            }
         }
        $this->assign('name', $name);
        $this->assign('start_query', input('get.start_query'));
        $this->assign('end_query', input('get.end_query'));
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /*
    删除成交记录
     */
    
    public function delete_bar(){
        if(isset($_POST["id"])){
            $delete=$this->Depot->where(array("id"=>$_POST["id"]))->delete();
            if($delete){
                $r = 1;
            }else{
                $r = 0;
            }
           echo json_encode($r); 
         }
    }
    /*
    删除资金记录
     */
    public function delete_enturst()
    {
        if(isset($_POST["id"])){
            $delete=$this->Enturst->where(array("id"=>$_POST["id"]))->delete();
            if($delete){
                $r = 1;
            }else{
                $r = 0;
            }
            echo json_encode($r);
        }
    }

    public function delete_cap(){
        if(isset($_POST["id"])){
            $delete=$this->CapitalFlow  ->where(array("id"=>$_POST["id"]))->delete();
            if($delete){
                $r = 1;
            }else{
                $r = 0;
            }
           echo json_encode($r); 
         }
    }

    /*
    导出委托记录
     */
     public function export_index($xlsData){
        //$xlsData = $this->User->select();
        Vendor('PHPExcel.Classes.PHPExcel');//调用类库,路径是基于vendor文件夹的
        Vendor('PHPExcel.Classes.PHPExcel.Worksheet.Drawing');
        Vendor('PHPExcel.Classes.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
 
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G,H,I,J,K,L,M");
        $arrHeader = array('交易编号','账户名','账户名','合约名','方向','委托类型','交易价格','成交手数','代理商','推荐人','资金账号','委托状态','委托时间');
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        };
        //填充表格信息
        foreach($xlsData as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['order_sn']);
            $objActSheet->setCellValue('B'.$k, $v['user']['phone']);
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
            $objActSheet->setCellValue('C'.$k, $v['user']['name']);
            $objActSheet->setCellValue('D'.$k, $v['code']);
            $objActSheet->setCellValue('E'.$k, $v['direction'] > 1?'卖':'买');
            $objActSheet->setCellValue('F'.$k, $v['order_type']);
            $objActSheet->setCellValue('G'.$k, $v['buy_price']);
            $objActSheet->setCellValue('H'.$k, $v['number']);
            $objActSheet->setCellValue('I'.$k, $v['agent']['name']);
            $objActSheet->setCellValue('J'.$k, $v['p_user']['name']);
            $objActSheet->setCellValue('K'.$k, $v['cap_name']);
            $objActSheet->setCellValue('L'.$k, $v['status']);
            $objActSheet->setCellValue('M'.$k, $v['time']);
 
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
        $objActSheet->getColumnDimension('M')->setWidth($width[3]);


        $outfile = "委托记录表.xls";
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

    /*
    导出成交记录
     */
     public function export_bargain($xlsData){
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
        $arrHeader = array('交易编号','账户名','账户名','合约名','方向','成交类型','成交价格','成交手数','代理商','推荐人','资金账号','成交时间');
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        };
        //填充表格信息
        foreach($xlsData as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['order_sn']);
            $objActSheet->setCellValue('B'.$k, $v['user']['phone']);
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
            $objActSheet->setCellValue('C'.$k, $v['user']['name']);
            $objActSheet->setCellValue('D'.$k, $v['code']);
            $objActSheet->setCellValue('E'.$k, $v['direction'] > 1?'卖':'买');
            $objActSheet->setCellValue('F'.$k, $v['order_type']);
            $objActSheet->setCellValue('G'.$k, $v['buy_price']);
            $objActSheet->setCellValue('H'.$k, $v['number']);
            $objActSheet->setCellValue('I'.$k, $v['agent']['name']);
            $objActSheet->setCellValue('J'.$k, $v['p_user']['name']);
            $objActSheet->setCellValue('K'.$k, $v['cap_name']);
            $objActSheet->setCellValue('L'.$k, $v['buy_time']);
 
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


        $outfile = "成交记录表.xls";
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

    /*
    导出平仓记录
     */
     public function export_close($xlsData){
        //$xlsData = $this->User->select();
        Vendor('PHPExcel.Classes.PHPExcel');//调用类库,路径是基于vendor文件夹的
        Vendor('PHPExcel.Classes.PHPExcel.Worksheet.Drawing');
        Vendor('PHPExcel.Classes.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
 
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R");
        $arrHeader = array('交易编号','合约编号','手机号','账户名','合约名','方向','开仓价格','履约保证金','开仓综合费','开仓时间','平仓价格','成交手数','平仓综合费','平仓时间','清算盈亏','资金账号','代理商','结算状态');
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        };
        //填充表格信息
        foreach($xlsData as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['order_sn']);
            $objActSheet->setCellValue('B'.$k, $v['contract_id']);
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
            $objActSheet->setCellValue('C'.$k, $v['user']['name']);
            $objActSheet->setCellValue('D'.$k, $v['user']['phone']);
            $objActSheet->setCellValue('E'.$k, $v['code']);
            $objActSheet->setCellValue('F'.$k, $v['direction'] > 1?'卖':'买');

            $objActSheet->setCellValue('G'.$k, $v['buy_price']);
            $objActSheet->setCellValue('H'.$k, $v['guarantee']);
            $objActSheet->setCellValue('I'.$k, $v['open_synthesize_money']);
            $objActSheet->setCellValue('J'.$k, $v['buy_time']);
            $objActSheet->setCellValue('K'.$k, $v['sell_price']);
            $objActSheet->setCellValue('L'.$k, $v['order_lot']);

            $objActSheet->setCellValue('M'.$k, $v['close_synthesize_money']);
            $objActSheet->setCellValue('N'.$k, $v['sell_time']);
            $objActSheet->setCellValue('O'.$k, $v['sell_price']-$v['buy_price']);
            $objActSheet->setCellValue('P'.$k, $v['cap_name']);
            $objActSheet->setCellValue('Q'.$k, $v['agent']['name']);
            $objActSheet->setCellValue('R'.$k, $v['status']);

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
        $objActSheet->getColumnDimension('M')->setWidth($width[3]);
        $objActSheet->getColumnDimension('N')->setWidth($width[3]);
        $objActSheet->getColumnDimension('O')->setWidth($width[3]);
        $objActSheet->getColumnDimension('P')->setWidth($width[3]);
        $objActSheet->getColumnDimension('Q')->setWidth($width[3]);
        $objActSheet->getColumnDimension('R')->setWidth($width[3]);


        $outfile = "平仓记录表.xls";
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

    /*
    导出资金记录Excel
     */
    public function export_capital($xlsData){
        //$xlsData = $this->User->select();
        Vendor('PHPExcel.Classes.PHPExcel');//调用类库,路径是基于vendor文件夹的
        Vendor('PHPExcel.Classes.PHPExcel.Worksheet.Drawing');
        Vendor('PHPExcel.Classes.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
 
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G,H");
        $arrHeader = array('手机号','账户名','业务类型','变动金额','账户余额','备注','时间','状态');
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        };
        //填充表格信息
        foreach($xlsData as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['user']['phone']);
            $objActSheet->setCellValue('B'.$k, $v['user']['name']);
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
            $objActSheet->setCellValue('C'.$k, $v['name']);
            $objActSheet->setCellValue('D'.$k, $v['number']);
            $objActSheet->setCellValue('E'.$k, $v['account']['account']);
            $objActSheet->setCellValue('F'.$k, $v['desc']);
            $objActSheet->setCellValue('G'.$k, $v['time']);
            $objActSheet->setCellValue('H'.$k, $v['status']);
 
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


        $outfile = "会员资金记录表.xls";
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


}