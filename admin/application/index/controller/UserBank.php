<?php

namespace app\index\controller;
use think\Request;
use app\common\model\UserBanks;
use app\common\model\Bank;
use app\common\model\City;
use think\Controller;
use think\Db;

class UserBank extends Common {

    private $UserBanks;
    private $Bank;
    private $City;

    public function __construct(\think\Request $request = null) {
        parent::__construct($request);
        $this->UserBanks = new UserBanks;
        $this->Bank = new Bank;
        $this->City = new City;
    }

    public function index() {
        $map = array();
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
        $list = $this->UserBanks->query_log($map, $current_page, $this->num);
        foreach($list["data"] as $key=>$val){
            $user=$this->User->where(array("id"=>$val["uid"]))->find();
            $bank=$this->Bank->where(array("id"=>$val["bank_id"]))->find();
            $list["data"][$key]["phone"]=$user["phone"];
            $list["data"][$key]["number"]=$user["card"];
            $list["data"][$key]["address"]=$bank["code"];
            $list["data"][$key]["username"]=$user["name"];
            $list["data"][$key]["time"]=date("Y-m-d H:i:s",$list["data"][$key]["time"]);
        }
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);

        if(isset($_GET["excel"])){
            if($_GET["excel"]){
                //$list = $this->User->query($_post["excel"]);
                $this->export($list['data']);
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

    public function edit() {

        if (request()->isAjax()) {
            $data = $_POST['arr'];
            var_dump($data);
            if (empty($data['province'])) {
                $r = msg_handle('请选择开户省份', 0);
            } elseif (empty($data['city'])) {
                $r = msg_handle('请选择所属城市', 0);
            } elseif (empty($data['country'])) {
                $r = msg_handle('请选择所属县城', 0);
            } elseif (empty($data['address'])) {
                $r = msg_handle('请输入开户支行', 0);
            } elseif (empty($data['username'])) {
                $r = msg_handle('请输入开户姓名', 0);
            } elseif (empty($data['phone'])) {
                $r = msg_handle('请输入手机号', 0);
            } elseif (!preg_match(REG_PHONE, $data['phone'])) {
                $r = msg_handle('手机号格式错误', 0);
            } elseif (empty($data['number'])) {
                $r = msg_handle('请输入身份证号码', 0);
            } elseif (!preg_match(REG_CARD, $data['number'])) {
                $r = msg_handle('身份证号码格式错误', 0);
            } elseif (empty($data['card'])) {
                $r = msg_handle('请输入银行卡号', 0);
            } elseif (!preg_match(REG_BANKCARD, $data['card'])) {
                $r = msg_handle('银行卡号格式错误', 0);
            } else {

                $bankname = $this->Bank->where(['id' => $data['bank'], 'status' => 1])->find();

                $admin = [
                    'bank' => $data['bank'],
                    'name' => $bankname['name'],
                    'address' => $data['address'],
                    'province' => $data['province'],
                    'city' => $data['city'],
                    'country' => $data['country'],
                    'username' => $data['username'],
                    'card' => $data['card'],
                    'number' => $data['number'],
                    'phone' => $data['phone'],
                    'time'=>time(),
                ];
                // $r = msg_handle('银行卡号格式错误', 0);
                // return json($r);
                $r = $this->UserBanks->where('id', $data['id'])->update($admin);
                if ($r) {
                    $r = msg_handle('修改成功', 1);
                } else {
                    $r = msg_handle('内容无修改', 0);
                }
                return json($r);
            }
            return json($r);
        }
        $id = $_GET["id"];
        $list = $this->UserBanks->getOne($id);
        $info = $this->Bank->getList();

        $where = ['id' => $list['city_id']];
        $province = $this->City->getLi($where);

        $admin = ['id' => $list['city']];
        $city = $this->City->getLi($admin);

        $ab = ['id' => $list['country']];
        $country = $this->City->getLi($ab);
        $province_lixt = $this->City->getListById(0);
        $city_lixt = $this->City->getCity($province['id']);
        $data = array();
        foreach ($city_lixt as $key => $value) {
            $arr['id'] = $value['id'];
            array_push($data, $arr);
        }
        $ac = array_map('current', $data);
        $country_lixt = $this->City->getCountry($ac);
        
        $this->assign('province_lixt', $province_lixt);
        $this->assign('city_lixt', $city_lixt);
        $this->assign('country_lixt', $country_lixt);
        $this->assign('info', $info);
        $this->assign('province', $province);
        $this->assign('city', $city);
        $this->assign('country', $country);
        $this->assign('list', $list);
        $a=Request::instance()->param('a');
        $b=Request::instance()->param('b');
        /*dump($a);
        dump($b);
        dump($id);
        exit;*/
        $this->assign('a', $a);
        $this->assign('b', $b);
        return $this->fetch();
    }


    //删除记录
    public function delete(){
        if(isset($_POST["id"])){
            $delete=$this->UserBanks->where(array("id"=>$_POST["id"]))->delete();
            if($delete){
                $r = 1;
            }else{
                $r = 0;
            }
           echo json_encode($r); 
         }
    }

    //城市联动
    public function getregion() {

        $req = \think\Request::instance();
        if ($req->isPost()) {
            $parent_id = $req->post('parent_id');
            $list = $this->City->getListById($parent_id);
            if ($list) {
                $r['list'] = $list;
                $r['status'] = 1;
            } else {
                $r['status'] = 0;
            }
            echo json_encode($r);
            exit();
        }
    }

     public function export($xlsData){//导出Excel表格操作
        //$xlsData = $this->User->select();
        Vendor('PHPExcel.Classes.PHPExcel');//调用类库,路径是基于vendor文件夹的
        Vendor('PHPExcel.Classes.PHPExcel.Worksheet.Drawing');
        Vendor('PHPExcel.Classes.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
 
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G,H,I");
        $arrHeader = array('账户ID','账户手机','账户身份证','开户行','开户分行','开户名','银行卡号','操作时间','状态');
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        };
        //填充表格信息
        foreach($xlsData as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['uid']);
            $objActSheet->setCellValue('B'.$k, $v['phone']);
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
            $objActSheet->setCellValue('C'.$k, $v['number']);
            $objActSheet->setCellValue('D'.$k, $v['name']);
            $objActSheet->setCellValue('E'.$k, $v['address']);
            $objActSheet->setCellValue('F'.$k, $v['username']);
            $objActSheet->setCellValue('G'.$k, $v['card']);
            $objActSheet->setCellValue('H'.$k, $v['time']);
            $objActSheet->setCellValue('I'.$k, $v['status']);
 
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

        $outfile = "会员银行卡信息表.xls";
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
