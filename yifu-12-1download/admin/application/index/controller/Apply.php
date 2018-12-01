<?php

namespace app\index\controller;

use app\common\model\Recharge;
use app\common\model\Withdraw;
use app\common\model\UserAccount;
use app\common\model\UserBanks;
use app\common\model\User;

class Apply extends Common
{
    private $Withdraw;
    private $UserAccount;
    private $UserBanks;
    private $Recharge;
    private $PayWithdraw;

    public function __construct(\think\Request $request = null)
    {
        parent::__construct($request);
        $this->User = new User();
        $this->UserBanks = new UserBanks();
        $this->Withdraw = new Withdraw();
        $this->Recharge = new Recharge();
        $this->UserAccount = new UserAccount();
        $this->PayWithdraw = new PayWithdraw();
    }

    /**
     * 申请提现列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function withdraw()
    {
        $map = array();
        $name = trim(input('get.name'));
        if ($name) {
            $res = $this->User->where('real_name|phone|name', 'like', "%$name%")->find();
            if (!empty($res)) {
                $map['uid'] = $res['id'];
            } else {
                $map['uid'] = $name;
            }
        }
//        $map['status'] = 1;
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Withdraw->query_log($map, $current_page, $this->num);
        foreach ($list["data"] as $key => $val) {
            $bank = $this->UserBanks->where(array('uid' => $val["uid"]))->find();
            $list["data"][$key]["bank"] = $bank;
        }
        $sum['withdraw_num'] = $this->Withdraw->where($map)->sum('number');//提现申请总和
        $sum['fee_num'] = $this->Withdraw->where($map)->sum('fee');//手续费总和
        $sum['num'] = $sum['withdraw_num'] - $sum['fee_num'];//到账总和
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        if (isset($_GET["excel"])) {
            if ($_GET["excel"]) {
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
        $this->assign('sum', $sum);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /**
     * 申请充值列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function recharges()
    {
        $map = array();
        $name = trim(input('get.name'));
        if ($name) {
            $res = $this->User->where('real_name|phone|name', 'like', "%$name%")->find();
            if (!empty($res)) {
                $map['uid'] = $res['id'];
            } else {
                $map['uid'] = $name;
            }
        }
        $map['status'] = array('neq', 1);
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Recharge->query_log($map, $current_page, $this->num);
        foreach ($list["data"] as $key => $val) {
            $bank = $this->UserBanks->where(array('uid' => $val["uid"]))->find();
            // $user=$this->User->where(array("id"=>$val["uid"]))->find();
            // $list["data"][$key]["phone"]=$user["phone"];
            // $list["data"][$key]["number"]=$user["card"];
            $list["data"][$key]['remark'] = json_decode($list["data"][$key]['remark']);
            $list["data"][$key]["bank"] = $bank;
        }
        $sum['recharge_num'] = $this->Recharge->where($map)->sum('number');//充值申请总和
        $sum['usd_num'] = $this->Recharge->where($map)->sum('usd');//美金总和
        $sum['fee_num'] = $this->Recharge->where($map)->sum('fee');//手续费总和
//        return json($list);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        if (isset($_GET["excel"])) {
            if ($_GET["excel"]) {
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
        $this->assign('sum', $sum);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /**
     * 处理申请 同意OR拒绝
     * @throws \Exception
     */
    public function modify_recharges()
    {
        $id = $_POST['id'];
        $edit_type = $_POST['edit_type'];
        if (!$id) {
            $this->redirect('Apply/recharges');
        }
        $info = $this->Recharge->where('id=' . $id)->find();
        $user = $this->User->where('id', $info['uid'])->field('id,real_name')->find();
        $user_bank = $this->UserBanks->where('uid', $info['uid'])->find();
        if (empty($user['real_name']) || empty($user_bank['bank_card'])) {
            $r = msg_handle('用户信息有误,请核对后重试!', 0);
        } else {
            $account = $this->UserAccount->where('uid=' . $info['uid'])->find();
            if ($edit_type == 1) {
                $r = $this->agree_recharges($account, $info, $user, $user_bank);
            } else {
                $r = $this->reject_recharges($info);
            }
        }
        return json($r);
    }

    /**
     * 充值驳回操作
     * @param $account
     * @param $info
     * @return array
     * @throws \think\exception\PDOException
     */
    public function reject_recharges($info)
    {
        $this->Withdraw->startTrans();
        $data['pay_time'] = time();
        $data['status'] = 2;
        $res2 = $this->Recharge->where(array('id' => $info['id']))->update($data);
        if ($res2) {
            $this->Recharge->commit();
            $r = msg_handle('操作成功', 1);
        } else {
            $this->Recharge->rollback();
            $r = msg_handle('操作失败', 0);
        }
        return $r;
    }

    /**
     * @param $account
     * @param $info
     * @param $user
     * @param $user_bank
     * @return array
     * @throws \Exception
     */
    public function agree_recharges($account, $info)
    {
        $this->UserAccount->startTrans();
        $res1 = $this->UserAccount->where('uid', $account['uid'])->setInc('balance', $info['usd'] - $info['fee']); //交易账户累计提现
        $bank = $this->UserBanks->where("uid", $info["uid"])->find();
        $data['pay_time'] = time();
        $data['status'] = 1;
        $res2 = $this->Recharge->where('id', $info['id'])->update($data);
//        sql('sn_recharge','sql',0);
        $rech["uid"] = $info["uid"];
        $rech["number"] = $info['number'];
        //$rech["balance"] = $info['balance'];
        //$rech["bank_name"] = $bank['bank_name'];
        $rech["type"] = 2;
        $rech["fee"] = $info['fee'];
        $rech["time"] = time();
        $rech["status"] = 2;
        $rech["pay_type"] = 2;
        $res3 = $this->Recharge->insertGetId($rech);
        if ($res1 && $res2 && $res3) {
            $this->UserAccount->commit();
//            sql('sn_recharge','sql',0);
            $r = msg_handle('操作成功', 1);
        } else {
            $this->UserAccount->rollback();
            $r = msg_handle('操作失败', 0);
        }
        return $r;
    }

    /**
     * 处理提现申请 同意OR拒绝
     * @throws \Exception
     */
    public function modify_withdrawals()
    {
        $id = $_POST['id'];
        $edit_type = $_POST['edit_type'];
        if (!$id) {
            $this->redirect('Apply/withdrawals');
        }
        $info = $this->Withdraw->where('id=' . $id)->find();
//        outpause($info);
//        outpause($_POST);
        if (!empty($_POST['remark'])) {
            $info['remark'] = $_POST['remark'];
        }
//        outpause($info);
        $user = $this->User->where('id', $info['uid'])->field('id,real_name')->find();
        $user_bank = $this->UserBanks->where('uid', $info['uid'])->find();
        if (empty($user['real_name']) || empty($user_bank['bank_card'])) {
            $r = msg_handle('用户信息有误,请核对后重试!', 0);
        } else {
            $account = $this->UserAccount->where('uid=' . $info['uid'])->find();
            if ($edit_type == 1) {
                $r = $this->agree_data($account, $info, $user, $user_bank);
            } else {
//                outpause($info,'skip',0);
                $r = $this->reject_data($account, $info);
            }
        }
        return json($r);
    }

    /**
     * @param $account
     * @param $info
     * @param $user
     * @param $user_bank
     * @return array
     * @throws \Exception
     */
    public function agree_data($account, $info, $user, $user_bank)
    {
        $this->UserAccount->startTrans();
//        $res1 = $this->UserAccount->where('uid', $account['uid'])->setInc('wit_total', $info['number']); //交易账户累计提现
        if ($account['frozen_bond'] > $info['number']) {
            $frozen = $account['frozen_bond'] - $info['number'];
        } else {
            $frozen = 0;
        }
        $res1 = $this->UserAccount->where('uid', $account['uid'])->update(
            array(
                'balance' => $account['balance'] - $info['number'],
                'wit_total' => $info['number'] + $account['wit_total'],
                'frozen_bond' => $frozen,
            )
        ); //交易账户累计提现
//        outpause($info);
//        sql('sn_user_account',"sql__",1);
        $data['pay_time'] = time();
        $data['status'] = 1;
        if ($info['remark']) {
            $data['remark'] = $info['remark'];
        }
        $res2 = $this->Withdraw->where('id', $info['id'])->update($data);
//        outpause($res1,"1",0);
//        outpause($res2,"2",1);
        if ($res1 && $res2) {
            $this->UserAccount->commit();
            //$r = $this->pay_data($user, $user_bank, $info['number'], $info, $account);
            $r = msg_handle('操作成功', 1);
        } else {
            $this->UserAccount->rollback();
            $r = msg_handle('操作失败', 0);
        }
        return $r;
    }

    /**
     * @param $user
     * @param $user_bank
     * @param float $money_order
     * @param $data
     * @param $account
     * @return array
     * @throws \Exception
     */
    public function pay_data($user, $user_bank, $money_order = 0.01, $data, $account)
    {
        $money_order = 0.01;
        $no_order = createOrderNum(1);
        $acct_name = $user['real_name'];
        $card_no = $user_bank['card'];
        $info_order = $acct_name . '申请提现' . $money_order . '元';
        $map['pay_order'] = $no_order;
        $map['id'] = $data['id'];
        if ($this->Withdraw->update($map)) {
//            $html = $this->PayWithdraw->index($no_order, $money_order, $acct_name, $card_no, $info_order);
            $html['ret_code'] = '0000';
            if ($html['ret_code'] == '0000') {
                $r = msg_handle('操作成功', 1);
            } else {
                $this->roll_back($account, $data);
                $r = msg_handle('操作失败', 0);
            }
        } else {
            $r = msg_handle('发送请求失败', 0);
        }
        return $r;
    }

    /**
     * @param $account
     * @param $info
     * @throws \Exception
     */
    public function roll_back($account, $info)
    {
        $this->UserAccount->startTrans();
        $res1 = $this->UserAccount->where('uid', $account['uid'])->setDec('wit_total', $info['number']); //交易账户累计提现
        $data['pay_time'] = '';
        $data['status'] = 0;
        $res2 = $this->Withdraw->where('id', $info['id'])->update($data);
        if ($res1 && $res2) {
            $this->UserAccount->commit();
        } else {
            $this->UserAccount->rollback();
        }
    }

    /**
     * 驳回操作
     * @param $account
     * @param $info
     * @return array
     * @throws \think\exception\PDOException
     */
    public function reject_data($account, $info)
    {
        $da['account'] = $account['account'] + $info['number'] + $info['fee'];
        if ($account['frozen_bond'] > $info['number']) {
            $frozen = $account['frozen_bond'] - $info['number'];
        } else {
            $frozen = 0;
        }
//        开始一个事务
        $this->UserAccount->startTrans();
        $res1 = $this->UserAccount->where('uid', $account['uid'])->update($da); //返回提现金额
        $data['pay_time'] = time();
        $data['status'] = 2;
        $data['remark'] = $info['remark'];
        $res2 = $this->Withdraw->where('id', $info['id'])->update($data);
        if (isset($res1) && $res2) {
            $this->UserAccount->commit();
            $r = msg_handle('操作成功', 1);
        } else {
            $this->UserAccount->rollback();
            $r = msg_handle('操作失败', 0);
        }
        return $r;
    }
    /**
     * 申请充值列表recharge
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
//    用户充值不需要申请，此方法弃用
    public function recharge()
    {
//        $map = array();
//        $name = trim(input('get.name'));
//        if ($name) {
//            $res = $this->User->where('phone', 'like', "%$name%")->find();
//            if (!empty($res)) {
//                $map['uid'] = $res['id'];
//            }
//        }
//        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
//        $current_page = page_judge(input('get.page'));
//        $list = $this->Recharge->query_log($map, $current_page, $this->num);
//        $sum['recharge_num'] = $this->Recharge->where($map)->sum('number');
//        $sum['fee_num'] = $this->Recharge->where($map)->sum('fee');
//        $sum['num'] = $sum['recharge_num'] - $sum['fee_num'];
//        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
//        $this->assign('arr', $this->arr_info(input('get.')));
//        $this->assign('empty', $this->null_html(12));
//        $this->assign('page', $page);
//        $this->assign('sum', $sum);
//        $this->assign('list', $list['data']);
////        dump($list['data']);exit;
        return $this->fetch();
    }

    /**
     * 处理充值申请 同意OR拒绝
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function modify_recharge()
    {
        $id = $_POST['id'];
        $edit_type = $_POST['edit_type'];
        if (!$id) {
            $this->redirect('Apply/recharge');
        }
        $info = $this->Withdraw->where('id=' . $id)->find();
        $account = $this->UserAccount->where('uid=' . $info['uid'])->find();
        if ($edit_type == 1) {
            $res1 = $this->UserAccount->where('uid', $info['uid'])->setInc(['rec_total' => $info['number'], 'account' => $account['account'] + $info['number']]); //累计充值  余额增加
            if ($res1) {
                $data['pay_time'] = time();
                $data['status'] = 1;
                $res = $this->Recharge->where('id', $id)->update($data);
                if ($res) {
                    $r = msg_handle('修改成功', 1);
                } else {
                    $r = msg_handle('修改失败', 0);
                }
                return json($r);
            }
        } else {
            $data['pay_time'] = time();
            $data['status'] = 2;
            $res = $this->Recharge->where('id', $id)->update($data);
            if ($res) {
                $r = msg_handle('修改成功', 1);
            } else {
                $r = msg_handle('修改失败', 0);
            }
            return json($r);
        }
    }

    /*
     *充值申请Excel表格导出
     */
    public function export($xlsData)
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
        $letter = explode(',', "A,B,C,D,E,F,G,H,I,J,K,L,M");
        $arrHeader = array('订单号', '申请用户', '姓名', '申请金额', '手续费', '开户名', '开户银行', '银行卡号', '申请时间', '处理时间', '处理状态', '备注');
        //填充表头信息
        $lenth = count($arrHeader);
        for ($i = 0; $i < $lenth; $i++) {
            $objActSheet->setCellValue("$letter[$i]1", "$arrHeader[$i]");
        };
        //填充表格信息
        foreach ($xlsData as $k => $v) {
            $k += 2;
            $objActSheet->setCellValue('A' . $k, $v['order']);
            $objActSheet->setCellValue('B' . $k, $v['user']['phone']);
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
            $objActSheet->setCellValue('C' . $k, $v['user']['real_name']);
            $objActSheet->setCellValue('D' . $k, $v['number']);
            $objActSheet->setCellValue('E' . $k, $v['fee']);
            $objActSheet->setCellValue('F' . $k, strval($v['bank']['name']));
            $objActSheet->setCellValue('G' . $k, $v['bank']['bank_name']);
            $objActSheet->setCellValue('H' . $k, $v['bank']['bank_card']);
            $objActSheet->setCellValue('I' . $k, $v['time']);
            $objActSheet->setCellValue('J' . $k, $v['pay_time'] ? $v['pay_time'] : "未处理");
            if ($v['status'] == 1) {
                $objActSheet->setCellValue('K' . $k, "已同意");
            } else if ($v['status'] == 2) {
                $objActSheet->setCellValue('K' . $k, "已驳回");
            } else {
                $objActSheet->setCellValue('K' . $k, "未处理");
            }
            $objActSheet->setCellValue('L' . $k, $v['remark']);
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
        $objActSheet->getColumnDimension('F')->setWidth($width[3]);
        $objActSheet->getColumnDimension('G')->setWidth($width[3]);
        $objActSheet->getColumnDimension('H')->setWidth($width[3]);
        $objActSheet->getColumnDimension('I')->setWidth($width[3]);
        $objActSheet->getColumnDimension('J')->setWidth($width[3]);
        $objActSheet->getColumnDimension('K')->setWidth($width[3]);
        $objActSheet->getColumnDimension('L')->setWidth($width[3]);
        $objActSheet->getColumnDimension('M')->setWidth($width[3]);
        $outfile = "出入金记录表.xls";
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
