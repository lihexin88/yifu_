<?php

namespace app\index\controller;

use think\Request;
use app\common\model\Closed;
use app\common\model\Variety;
use app\common\model\Contract;
use app\common\model\Exchange;
use app\common\model\Config;
use think\Session;

class Transaction extends Common
{
    private $Closed;
    private $Variety;
    private $Contract;
    private $Exchange;
    private $Config;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Exchange = new Exchange();
        $this->Contract = new Contract();
        $this->Variety = new Variety();
        $this->Closed = new Closed();
        $this->Config = new Config();
    }

    /*
    * 品种管理
    */
    public function index()
    {
        $map = '';
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Variety->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);


        if (isset($_GET["excel"])) {
            if ($_GET["excel"]) {
                $lists = $this->Variety->query_log($map, 1, 10000);

                //$list = $this->User->query($_post["excel"]);
                $this->export_exchange($lists['data']);
            }
        }
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /*
     * 合约管理
     */
    public function contract()
    {
        $map = '';
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Contract->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        foreach ($list["data"] as $k => &$v) {
            $list["data"][$k]["variety"] = $this->Variety->where(array("id" => $v["bourse"]))->find();
        }

        if (isset($_GET["excel"])) {
            if ($_GET["excel"]) {
                $lists = $this->Contract->query_log($map, 1, 10000);
                foreach ($lists["data"] as $k => &$v) {
                    $lists["data"][$k]["variety"] = $this->Variety->where(array("id" => $v["bourse"]))->find();
                }
                //$list = $this->User->query($_post["excel"]);
                $this->export_contract($lists['data']);
            }
        }
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /*
     *交易所管理
     */
    public function exchange(){
        $map='';
        $name = input('get.name');
        if ($name) {
            $user=$this->Exchange->where(array("code|name"=>['like',$name]))->find();
            if($user){
                $map['id'] = ['like', $user["id"]];
            }else{
                $map['id'] = ['like', 0];
            }
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Exchange->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);

        if(isset($_GET["excel"])){
            if($_GET["excel"]){
                $lists = $this->Exchange->query_log($map, 1, 10000);
                //$list = $this->User->query($_post["excel"]);
                $this->export_contract($lists['data']);
            }
         }
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /*
     * 修改交易所状态
     */
    public function mod_status(){
        $id=$_POST["id"];
        $status=$_POST["status"];
        if($status == 1){
            $data["status"]=0;
        }else{
            $data["status"]=1;
        }
        $list=$this->Exchange->where(array("id"=>$id))->update($data);
        if($list){
            $this->success('修改成功', 'Transaction/exchange');
        }else{
            $this->error('修改失败');
        }
    }

    /*
     * 添加修改交易所
     */
    public function exchange_add(){
        $id = input('get.id');
        if(isset($id)){
            $list=$this->Exchange->where(array("id"=>$id))->find();
        }else{
            $list="";
        }
        $this->assign('list', $list);
        return $this->fetch();
    }

    /*
     * 执行交易所添加修改操作
     */
    public function exchange_adds(){
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['name'])) {
                $r = msg_handle('请输入交易所名称', 0);
                return $r;
            } elseif (empty($data['code'])) {
                $r = msg_handle('请输入交易所代码', 0);
                return $r;
            }  elseif (empty($data['short'])) {
                $r = msg_handle('请输入交易所简称', 0);
                return $r;
            }  elseif (empty($data['sort'])) {
                $r = msg_handle('请输入交易所排序', 0);
                return $r;
            } else {

                $id=$data["id"];
                if($id>0){
                    $data["time"]=time();
                    $list = $this->Exchange->where(array("id"=>$id))->update($data);
                }else{
                    $data["time"]=time();
                    $list = $this->Exchange->insertGetId($data);
                }
                
            }
            if ($list) {
                //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                $this->success('操作成功', 'Transaction/exchange');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败,未改动数据!');
            }
        }
    }

    /*
    节假日管理
     */
    public function holiday()
    {
        $map = '';
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Closed->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);

        if (isset($_GET["excel"])) {
            if ($_GET["excel"]) {
                //$list = $this->User->query($_post["excel"]);
                $this->export_holiday($list['data']);
            }
        }
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /*
    默认参数设置
     */
    public function parameter()
    {
        $list = $this->Config->where('uid=0')->find();
        $this->assign('list', $list);
        return $this->fetch();
    }

    /*
    修改交易参数
     */
    public function parameter_edit()
    {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['open_transaction_number'])) {
                $r = msg_handle('请输入开仓默认手数', 0);
                return $r;
            } elseif (empty($data['customs_max_number'])) {
                $r = msg_handle('请输入报单最大手数', 0);
                return $r;
            } else {
                $list = $this->Config->where('uid=0')->update($data);
            }
            if ($list) {
                //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                $this->success('操作成功', 'Transaction/holiday');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败,未改动数据!');
            }
        }
    }

    /*
    删除假期
     */
    public function del_holiday()
    {
        if (request()->isAjax()) {
            $id = $_POST['id'];
            $list = $this->Closed->where('id=' . $id)->delete();
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Article/holiday');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
    }

    /*
    添加假期
     */
    public function holiday_edit()
    {
        if (request()->isAjax()) {
            $admin_user = Session::get('admin');
            $data = $_POST['arr'];
            if (empty($data['explain'])) {
                $r = msg_handle('请输入假期名称', 0);
                return $r;
            } elseif (empty($data['time'])) {
                $r = msg_handle('请输入开始时间', 0);
                return $r;
            } elseif (empty($data['closed_time'])) {
                $r = msg_handle('请输入结束时间', 0);
                return $r;
            } else {
                $data["time"] = strtotime($data['time']);
                $data["closed_time"] = strtotime($data['closed_time']);
                $data["name"] = $admin_user["name"];
                $data["add_time"] = time();
                $list = $this->Closed->insertGetId($data);
            }
            if ($list) {
                //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                $this->success('操作成功', 'Transaction/holiday');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败,未改动数据!');
            }
        }
        return $this->fetch();
    }

    /*
    合约修改
     */

    public function contract_edit()
    {
        $id = Request::instance()->param('id');
        if (isset($id)) {
            $id = Request::instance()->param('id');
            $list = $this->Contract->where('id=' . $id)->find();
            if ($list['time']) {
                $list['time'] = date('Y-m-d', $list['time']);
            }
            $this->assign('list', $list);
        } else {
            $list = "";
            $this->assign('list', $list);
        }
        $exchange = $this->Variety->select();
        $this->assign('exchange', $exchange);
        return $this->fetch();
    }

    /*
    执行修改合约
     */

    public function update_contract()
    {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['name'])) {
                $r = msg_handle('请输入合约名称', 0);
            } elseif (empty($data['code'])) {
                $r = msg_handle('请输入合约代码', 0);
            } elseif (empty($data['short'])) {
                $r = msg_handle('请输入合约短码', 0);
            } else {

                $data['time'] = time();
                $futures = $this->Variety->where(array("id" => $data["futures"]))->find();
                $exchange = $this->Exchange->where(array("id" => $futures["bourse"]))->find();
                if (!empty($data['id'])) {
                    $data["bourse"] = $exchange["id"];
                    $data["bourse_name"] = $exchange["name"];
                    $data["bourse_code"] = $exchange["code"];
                    $data["time"] = time();
                    $list = $this->Contract->where('id=' . $data['id'])->update($data);
                } else {
                    $data["bourse"] = $exchange["id"];
                    $data["bourse_name"] = $exchange["name"];
                    $data["bourse_code"] = $exchange["code"];
                    $data["time"] = time();
                    $list = $this->Contract->insertGetId($data);;
                }
                if ($list) {
                    //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                    $this->success('操作成功', 'Transaction/index');
                } else {
                    //错误页面的默认跳转页面是返回前一页，通常不需要设置
                    $this->error('操作失败,未改动数据!');
                }


            }

        } else {
            $r = msg_handle('错误操作', 0);
        }
        return json($r);
    }

    /*
    删除合约
     */

    public function del_contract()
    {
        if (request()->isAjax()) {
            $id = $_POST['id'];
            $list = $this->Contract->where('id=' . $id)->delete();
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Article/index');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;

    }

    /*
    添加品种
     */
    public function add_var()
    {
        $exchange = $this->Exchange->select();
        $this->assign('exchange', $exchange);
        return $this->fetch();
    }

    /*
    品种修改
     */
    public function var_edit()
    {
        $id = Request::instance()->param('id');
        $list = $this->Variety->where('id=' . $id)->find();
        $exchange = $this->Exchange->select();
        $this->assign('exchange', $exchange);
        $this->assign('list', $list);
        return $this->fetch();
    }

    /*
    执行修改品类
     */
    public function update_edit()
    {
        if (request()->isAjax()) {
            $data = $_POST['arr'];

            if (empty($data['name'])) {
                $r = msg_handle('请输入品种名称', 0);
            } elseif (empty($data['code'])) {
                $r = msg_handle('请输入合约代码', 0);
            } elseif (empty($data['short'])) {
                $r = msg_handle('请输入合约短码', 0);
            } elseif (empty($data['industry'])) {
                $r = msg_handle('请输入合约类型', 0);
            } else {

                $exchange = $this->Exchange->where(array("id" => $data["bourse"]))->find();
                if (!empty($data['id'])) {
                    $data["bourse_name"] = $exchange["short"];
                    $data["bourse_code"] = $exchange["code"];
                    $data["time"] = time();
                    $list = $this->Variety->where('id=' . $data['id'])->update($data);
                } else {
                    $data["bourse_name"] = $exchange["short"];
                    $data["bourse_code"] = $exchange["code"];
                    $data["time"] = time();
                    $list = $this->Variety->insertGetId($data);
                }
                if ($list) {
                    //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                    $this->success('操作成功', 'Transaction/index');
                } else {
                    //错误页面的默认跳转页面是返回前一页，通常不需要设置
                    $this->error('操作失败,未改动数据!');
                }

            }

        } else {
            $r = msg_handle('错误操作', 0);
        }
        return json($r);
    }


    public function export_exchange($xlsData)
    {//导出Excel表格操作
        //$xlsData = $this->User->select();
        Vendor('PHPExcel.Classes.PHPExcel');//调用类库,路径是基于vendor文件夹的
        Vendor('PHPExcel.Classes.PHPExcel.Worksheet.Drawing');
        Vendor('PHPExcel.Classes.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');

        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter = explode(',', "A,B,C,D,E,F,G");
        $arrHeader = array('交易所名称', '交易所类型', '品种名称', '短码', '代码', '类型', '添加时间');
        //填充表头信息
        $lenth = count($arrHeader);
        for ($i = 0; $i < $lenth; $i++) {
            $objActSheet->setCellValue("$letter[$i]1", "$arrHeader[$i]");
        };
        //填充表格信息
        foreach ($xlsData as $k => $v) {
            $k += 2;
            $objActSheet->setCellValue('A' . $k, $v['bourse_name']);
            $objActSheet->setCellValue('B' . $k, $v['type']);
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
            $objActSheet->setCellValue('C' . $k, $v['name']);
            $objActSheet->setCellValue('D' . $k, $v['short']);
            $objActSheet->setCellValue('E' . $k, $v['code']);
            $objActSheet->setCellValue('F' . $k, $v['industry']);
            $objActSheet->setCellValue('G' . $k, $v['time']);


            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(20);
        }

        $width = array(20, 20, 15, 10, 10, 10, 30);
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth($width[3]);
        $objActSheet->getColumnDimension('B')->setWidth($width[3]);
        $objActSheet->getColumnDimension('C')->setWidth($width[3]);
        $objActSheet->getColumnDimension('D')->setWidth($width[3]);
        $objActSheet->getColumnDimension('E')->setWidth($width[3]);
        $objActSheet->getColumnDimension('F')->setWidth($width[3]);
        $objActSheet->getColumnDimension('G')->setWidth($width[3]);

        $outfile = "品种信息表.xls";
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $outfile . '"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }


    public function export_contract($xlsData)
    {//导出Excel表格操作
        //$xlsData = $this->User->select();
        Vendor('PHPExcel.Classes.PHPExcel');//调用类库,路径是基于vendor文件夹的
        Vendor('PHPExcel.Classes.PHPExcel.Worksheet.Drawing');
        Vendor('PHPExcel.Classes.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');

        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter = explode(',', "A,B,C,D,E,F,G");
        $arrHeader = array('品种名称', '交易所代码', '合约名称', '合约代码', '合约短码', '合约类型', '合约交割日');
        //填充表头信息
        $lenth = count($arrHeader);
        for ($i = 0; $i < $lenth; $i++) {
            $objActSheet->setCellValue("$letter[$i]1", "$arrHeader[$i]");
        };
        //填充表格信息
        foreach ($xlsData as $k => $v) {
            $k += 2;
            $objActSheet->setCellValue('A' . $k, $v['variety']['name'] . "(" . $v['variety']['bourse_name'] . ")");
            $objActSheet->setCellValue('B' . $k, $v['bourse_code']);
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
            $objActSheet->setCellValue('C' . $k, $v['name']);
            $objActSheet->setCellValue('D' . $k, $v['code']);
            $objActSheet->setCellValue('E' . $k, $v['short']);
            $objActSheet->setCellValue('F' . $k, $v['type']);
            $objActSheet->setCellValue('G' . $k, $v['variety']['trading_time']);


            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(20);
        }

        $width = array(30, 20, 15, 10, 10, 30, 10, 15);
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth($width[5]);
        $objActSheet->getColumnDimension('B')->setWidth($width[3]);
        $objActSheet->getColumnDimension('C')->setWidth($width[3]);
        $objActSheet->getColumnDimension('D')->setWidth($width[3]);
        $objActSheet->getColumnDimension('E')->setWidth($width[3]);
        $objActSheet->getColumnDimension('F')->setWidth($width[3]);
        $objActSheet->getColumnDimension('G')->setWidth($width[3]);

        $outfile = "合约信息表.xls";
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $outfile . '"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }


    public function export_holiday($xlsData)
    {//导出假期Excel表格操作
        //$xlsData = $this->User->select();
        Vendor('PHPExcel.Classes.PHPExcel');//调用类库,路径是基于vendor文件夹的
        Vendor('PHPExcel.Classes.PHPExcel.Worksheet.Drawing');
        Vendor('PHPExcel.Classes.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');

        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter = explode(',', "A,B,C,D,E");
        $arrHeader = array('假期名称', '开始时间', '结束时间', '创建人', '备注');
        //填充表头信息
        $lenth = count($arrHeader);
        for ($i = 0; $i < $lenth; $i++) {
            $objActSheet->setCellValue("$letter[$i]1", "$arrHeader[$i]");
        };
        //填充表格信息
        foreach ($xlsData as $k => $v) {
            $k += 2;
            $objActSheet->setCellValue('A' . $k, $v['explain']);
            $objActSheet->setCellValue('B' . $k, $v['time']);
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
            $objActSheet->setCellValue('C' . $k, $v['closed_time']);
            $objActSheet->setCellValue('D' . $k, $v['name']);
            $objActSheet->setCellValue('E' . $k, $v['desc']);


            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(20);
        }

        $width = array(20, 20, 15, 10, 10, 30, 10, 15);
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth($width[3]);
        $objActSheet->getColumnDimension('B')->setWidth($width[3]);
        $objActSheet->getColumnDimension('C')->setWidth($width[3]);
        $objActSheet->getColumnDimension('D')->setWidth($width[3]);
        $objActSheet->getColumnDimension('E')->setWidth($width[3]);

        $outfile = "假期信息表.xls";
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $outfile . '"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

}



















