<?php

namespace app\index\controller;

use think\Request;
use app\common\model\User;
use app\common\model\Agent;
use app\common\model\Account;
use app\common\model\Flow;
use app\common\model\Feedback as FeedbackModel;
use app\common\model\Recharge;
use app\common\model\RegisterApply;
use app\common\model\UserAccount;
use think\Db;

class Users extends Common
{
    protected $User;
    private $Account;
    private $Recharge;
    private $Flow;
    private $RegisterApply;
    private $UserAccount;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->User = new User();
        $this->Account = new Account();
        $this->Recharge = new Recharge();
        $this->Flow = new Flow();
        $this->Agent = new Agent();
        $this->RegisterApply = new RegisterApply();
        $this->UserAccount = new UserAccount();
    }

    /**
     * 后台读取用户反馈信息
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function feedback()
    {
//        按条件查询
        $where = null;
        $start_time = null;
        $end_time = null;
        if ($_POST) {
            if ($_POST['uid']) {
                $where['uid'] = $_POST['uid'];
            }
            if ($_POST['question_id']) {
                $where['id'] = $_POST['question_id'];
            }
            if (strtotime($_POST['start_query'])) {
//                开始时间为空，则按照时间为0查询记录
                $start_time = strtotime($_POST['start_query']);
            } else {
                $start_time = 0;
            }
            if (strtotime($_POST['end_query'])) {
                $end_time = strtotime($_POST['end_query']);
            } else {
                $end_time = time();
            }
            $where['time'] = ['between time', [$start_time, $end_time]];
        }
//        未定义变量，查询全部反馈信息
        $all_feedback_log = new FeedbackModel;
        if (empty($all_feedback_log['question_id'])) {
            $r = msg_handle("最近没有反馈记录", 0);
            $this->assign("noRecord", $r);
        }
        $page_size = 10;
        $get_all_feedback = new FeedbackModel();
        $get_all_feedback = $get_all_feedback->where($where)->paginate($page_size);
//        echo Db::name('sn_feedback')->getLastSql();
        $page = $get_all_feedback->render();
        $map = "";
        $name = input('get.name/s');
        if ($name) {
            $map['name|phone'] = ['like', $name];
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page/d'));
        $list = $this->User->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        if (isset($_GET["excel"])) {
            if ($_GET["excel"]) {
                //$list = $this->User->query($_post["excel"]);
                $this->export_users($list['data']);
            }
        }
        $sum = $this->Account->sum('account');
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('feedback_log', $get_all_feedback);
        $this->assign('name', $name);
        $this->assign('start_query', input('get.start_query'));
        $this->assign('end_query', input('get.end_query'));
        $this->assign('list', $list['data']);
        $this->assign('sum', $sum);
        return $this->fetch();
    }

    /*
     * 修改问题反馈信息
     */
    public function edit_feedback()
    {
//        print_r($_POST);
//        echo "get";return;
//        接收post传递过来的数据
        $data['id'] = $_POST['question_id'];
//        print_r($data);
//        按数据读取
        $get_this_question = FeedbackModel::get($data);
//        echo Db::name('sn_feedback')->getLastSql();
//        获取到信息
        if ($get_this_question) {
            $data['headle_msg'] = $_POST['question_suggest'];
//            echo "已赋值";
//            抛出异常
            try {
                $data['headle_time'] = time();
                $data['headle'] = 1;
                $get_this_question->save($data);
//                echo Db::name('sn_feedback')->getLastSql();
                $r = msg_handle('问题已处理', 1);
                return $r;
            } catch (\Exception $e) {
                throw $e;
            }
        }
//        未获取到信息
        $r = msg_handle('处理失败！', -1);
        return $r;
    }

    /**
     * 获取一条反馈信息
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function post_question()
    {
        $data = $_POST;
        if (empty($data)) {
            $r = msg_handle("失败", -1, "未接收到数据");
        }
        $r = $this->get_one_question($data);
        return json($r);
    }

    /**
     * 展示一条反馈数据
     * @param $data
     * @return array
     * @throws \think\exception\DbException
     */
    private function get_one_question($data)
    {
//        outpause($data);
        $get_one_question = FeedbackModel::get($data['question_id']);
//        outpause($get_one_question);
        if ($get_one_question) {
            $get_one_question->time = date('Y-m-d H:i:s', $get_one_question->time);
            $get_one_question->headle_time = date('Y-m-d H:i:s', $get_one_question->headle_time);
//            print_r($get_one_question);
//            exit();
            $r = msg_handle("成功", 1, $get_one_question);
        } else {
            $r = msg_handle("数据库中没有该数据", -1);
        }
        return $r;
    }

    /**
     * 会员列表
     * @return mixed
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $map = array();
        $name = input('get.name/s');
        if ($name) {
            $map['name|phone'] = ['like', $name];
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page/d'));
        $list = $this->User->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        //如果url里含有excel参数，则将结果导出到Excel
        if (isset($_GET["excel"])) {
            if ($_GET["excel"]) {
                //$list = $this->User->query($_post["excel"]);
                $this->export_users($list['data']);
            }
        }
        $sum = $this->Account->sum('account');
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('name', $name);
        $this->assign('start_query', input('get.start_query'));
        $this->assign('end_query', input('get.end_query'));
        $this->assign('list', $list['data']);
        $this->assign('sum', $sum);
        return $this->fetch();
    }

    /**
     * 充值记录
     */
    public function recharge_record()
    {
        $map = "";
        $name = input('get.name/s');
        if ($name) {
            $user = $this->User->where(array("phone|name" => ['like', $name]))->find();
            if ($user) {
                $map['uid'] = ['like', $user["id"]];
            } else {
                $map['uid'] = ['like', 0];
            }
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page/d'));
        $list = $this->Recharge->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        if (isset($_GET["excel"])) {
            if ($_GET["excel"]) {
                //$list = $this->User->query($_post["excel"]);
                $this->export_recharge($list['data']);
            }
        }
        foreach ($list["data"] as $key => $val) {
            $bank = $this->User->where(array('id' => $val["uid"]))->find();
            // $user=$this->User->where(array("id"=>$val["uid"]))->find();
            // $list["data"][$key]["phone"]=$user["phone"];
            //$list["data"][$key]["time"]=date("Y-m-d H:i:s",$list["data"][$key]["time"]);
            $list["data"][$key]["user"] = $bank;
        }
        //$sum = $this->Account->sum('account');
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('name', $name);
        $this->assign('start_query', input('get.start_query'));
        $this->assign('end_query', input('get.end_query'));
        $this->assign('list', $list['data']);
        //$this->assign('sum', $sum);
        return $this->fetch();
    }

    /**
     * 删除充值记录
     * @return array
     */
    public function del()
    {
        if (request()->isAjax()) {
            $id = $_POST['id'];
            //var_dump($id);return;
            $list = $this->Recharge->where('id=' . $id)->delete();
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Users/recharge_record');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        var_dump($r);
        return $r;
    }

    /**
     * 用户资金流水
     * @return mixed
     */
    public function user_flowlog()
    {
        $map = "";
        $name = input('get.name');
        if ($name) {
            $map['name|phone'] = ['like', $name];
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Flow->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $sum['reduce'] = $this->Flow->where('mold', 0)->sum('number');
        $sum['increase'] = $this->Flow->where('mold', 1)->sum('number');
// dump($sum);exit;
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        $this->assign('sum', $sum);
        return $this->fetch();
    }

    public function edit()
    {
        $id = Request::instance()->param('id');
        if (empty($id)) {
            $r = msg_handle('操作失败', 0);
        } else {
            $user = $this->User->find($id);
            if ($user['status'] != 1) {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }
            $data['modify_time'] = time();
            $res = $this->User->where('id', $id)->update($data);
            if ($res) {
                $r = msg_handle('操作成功', 1);
            } else {
                $r = msg_handle('操作失败', 0);
            }
        }
        return json($r);
    }

    public function editReStatus()
    {
        $id = Request::instance()->param('id/d');
        $type = Request::instance()->param('type');
        if (empty($id)) {
            $r = msg_handle('操作失败', 0);
        } else {
            $user = $this->User->find($id);
            if ($type == 'agree') {
                $data['re_status'] = 1;
            } else {
                $data['re_status'] = 2;
            }
            $data['modify_time'] = time();
            $res = $this->User->where('id', $id)->update($data);
            if ($res) {
                $r = msg_handle('操作成功', 1);
            } else {
                $r = msg_handle('操作失败', 0);
            }
        }
        return json($r);
    }

    /**
     * @return mixed
     * 用户信息修改
     */
    public function user_edit()
    {
        $id = Request::instance()->param('id');
        $list = $this->User->where('id=' . $id)->find();
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 用户信息修改执行
     * @return array|\think\response\Json
     */
    public function user_edits()
    {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
//            var_dump($data);
            if (empty($data['real_name'])) {
                $r = msg_handle('请输入姓名', 0);
                return $r;
            } elseif (empty($data['card'])) {
                $r = msg_handle('请输入身份证号', 0);
                return $r;
            } elseif (empty($data['phone'])) {
                $r = msg_handle('请输入手机号', 0);
                return $r;
            } elseif (!(string)$data['type']) {
                $r = msg_handle('请选择账号类型', 0);
                return $r;
            } elseif (!(string)$data['qq']) {
                $r = msg_handle('请输入QQ！', 0);
                return $r;
            }
            if ($data['type'] == '2') {
                $data['type'] = 1;
            } else {
                $data['type'] = 0;
            }
            if (empty($data['password'])) {
                $data['password'] = md5("123456");
            } else {
                $data['password'] = md5($data['password']);
            }
            if (empty($data['trade_password'])) {
                $data['trade_password'] = md5("123456");
            } else {
                $data['trade_password'] = md5($data['trade_password']);
            }
            $data['modify_time'] = time();
            $list = $this->User->update($data);
            if ($list) {
                $r = msg_handle('操作成功！', 1);
            } else {
                $r = msg_handle('操作失败！', -1);
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return json($r);
    }

    /**
     * @return mixed用户充值
     */
    public function rechange()
    {
        $id = Request::instance()->param('id');
        $this->assign('id', $id);
        $list = $this->User->where('id=' . $id)->find();
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 用户充值执行
     * @return \think\response\Json
     */
    public function rechange_info()
    {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            $pre1 = '/^[0-9]+(.[0-9]{1,2})?$/';
            if (empty($data['number'])) {
                $r = msg_handle('请输入充值金额', 0);
                return $r;
            } elseif (!preg_match($pre1, $data['number'])) {
                $r = msg_handle('充值金额参数不正确', 0);
                return $r;
            } elseif (!preg_match($pre1, $data['fee'])) {
                $r = msg_handle('手续费参数不正确', 0);
                return $r;
            } elseif (empty($data['account'])) {
                $r = msg_handle('请输入到账金额', 0);
                return $r;
            } elseif (!preg_match($pre1, $data['account'])) {
                $r = msg_handle('到账金额参数不正确', 0);
                return $r;
            } else {
                $this->Account->startTrans();
                $list = $this->Account->where('uid', $data['id'])->setInc('account', $data['account']);
            }
            // dump($data);exit;
            $dat['uid'] = $data['id'];
            $dat['number'] = $data['number'];
            $dat['fee'] = $data['fee'];
            $dat['pay_type'] = $data['pay_type'];
            $dat['type'] = 1;
            $dat['time'] = time();
            $dat['status'] = 1;
            $dat['remark'] = $data['remark'];
            $dat['order'] = createOrderNum(1);
            $dat['pay_time'] = time() + 600;
            $map = $this->Recharge->insert($dat);
            $flow_data["uid"] = $data["id"];
            $flow_data["type"] = 1;
            $flow_data["name"] = "后台充值";
            $flow_data["number"] = $data['number'] - $data['fee'];
            $flow_data["fee"] = $data['fee'];
            $flow_data["status"] = 1;
            $flow_data["time"] = time();
            $flow_data["desc"] = $data['remark'];
            $map = $this->Flow->insert($flow_data);
            if ($list && $map) {
                $this->Account->commit();
                //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                $this->success('充值成功', 'Users/index');
            } else {
                $this->Account->rollback();
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('充值失败,请稍后重试!');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return json($r);
    }

    /**
     * 处理用户账号申请
     * @return \think\response\Json
     */
    public function hand_apply()
    {
        if (request()->isAjax()) {
            $id = $_POST['id'];
            if (empty($id)) {
                $r = msg_handle('缺少参数', 0);
            } else {
                $r = $this->RegisterApply->where('id', $id)->update(
                    array(
                        'status' => 1,
                        'hand_time' => time(),
                    ));
                if ($r) {
                    $r = msg_handle('操作失败', 0);
                } else {
                    $r = msg_handle('操作成功', 1);
                }
            }
        }
        return json($r);
    }

    /**
     * 删除用户申请信息
     * @return \think\response\Json
     */
    public function del_apply()
    {
        if (request()->isAjax()) {
            $id = $_POST['id'];
            $list = $this->RegisterApply->where('id', $id)->delete();
            if ($list) {
                $r = msg_handle('操作成功', 1);
            } else {
                $r = msg_handle('操作失败', 0);
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return json($r);
    }

    /**
     * 添加用户/加载页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function user_add()
    {
        $agents = $this->Agent->select();
//        return json($agents);
        $id = input('id');
        $apply = $this->RegisterApply->where('id', $id)->find();
        $applys = array('id' => '0', 'name' => '', 'tel' => '', 'qq' => '', 'type' => '');
        $list = $id ? $apply : $applys;
        $this->assign("list", $list);
        $this->assign("agent", $agents);
        return $this->fetch();
    }

    /**
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function user_adds()
    {
        if ($_POST) {
            $data = $_POST['arr'];
            if (empty($data["real_name"])) {
                $r = msg_handle('请输入用户姓名', 0);
                return $r;
            } else if (empty($data["phone"])) {
                $r = msg_handle('请输入用户手机号', 0);
                return $r;
            } else if (empty($data["password"])) {
                $r = msg_handle('请输入用户密码', 0);
                return $r;
            } else if (empty($data["trade_password"])) {
                $r = msg_handle('请输入用户支付密码', 0);
                return $r;
            } else {
                //查询账号是否已存在
                $user = $this->User->where(array('phone' => $data['phone']))->find();
                if ($user) {
                    $r = msg_handle('手机号码已使用', 0);
                    return $r;
                }
                $data["reid"] = $data["reid"] ? $data["reid"] : 0;
                $this->User->startTrans();
                $res1 = $this->User->register($data);
                $res2 = $this->UserAccount->add_log($res1, $data['phone']);
                if ($data['id']) {
                    $res3 = $this->RegisterApply->where('id', $data['id'])->update(
                        array(
                            'status' => 1,
                            'hand_time' => time(),
                        ));
                }
                if ($res1 && $res2) {
                    $this->User->commit();
                    $r = msg_handle('注册成功!', 1);
                } else {
                    $this->User->rollback();
                    $r = msg_handle('注册失败！', 0);
                }
            }
        }
        return json($r);
    }

    public function p_name()
    {
        if ($_POST) {
            $data = $_POST['arr'];
            $p_user = $this->User->where(array("phone" => $data))->find();
            if ($p_user) {
                if ($p_user["real_name"] != "") {
                    $p_name = $p_user["real_name"];
                    $r = msg_handle("{$p_name}", 1);
                    return $r;
                } else {
                    $p_name = $p_user["name"];
                    $r = msg_handle("{$p_name}", 1);
                    return $r;
                }
            } else {
                $r = msg_handle('推荐人不存在', 0);
                return $r;
            }
        }
    }

    public function find_me($id)
    {
        $user = $this->User->where(array("id" => $id))->find();
        return $user;
    }

    public function find_parents($id)
    {
        $zishen = $this->find_me($id);
        $shuzu = array();
        $shuzu[1] = $zishen;
        if ($zishen) {
            if ($zishen["reid"] != 0) {
                $shuzu[1] = $zishen;
                $preferral = $zishen["reid"];
                $count = $this->User->count();
                $i = 1;
                do {
                    if ($preferral != 0) {
                        $zishens = $this->find_me($preferral);
                        $preferral = $zishens["reid"];
                        $i++;
                        $shuzu[$i] = $zishens;
                    } else {
                        $i = $count + 1;
                    }
                } while ($i < $count);
            } else {
                $shuzu[1] = $zishen;
            }
        }
        return $shuzu;
    }

    /**
     * 用户申请列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function apply()
    {
        $map = "";
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page/d'));
        $list = $this->RegisterApply->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('start_query', input('get.start_query'));
        $this->assign('end_query', input('get.end_query'));
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    public function banks()
    {
        $p_user = Db::name('bank')->where('id', 1)->find();;
        return $this->fetch();
    }

    public function real()
    {
        return $this->fetch();
    }

    public function export_users($xlsData)
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
        $letter = explode(',', "A,B,C,D,E,F,G,H,I,J,K");
        $arrHeader = array('用户账号', '手机号码', '用户昵称', '用户姓名', '用户状态', '用户身份证', '注册时间', '注册IP', '账户余额', '代理ID', '所属机构');
        //填充表头信息
        $lenth = count($arrHeader);
        for ($i = 0; $i < $lenth; $i++) {
            $objActSheet->setCellValue("$letter[$i]1", "$arrHeader[$i]");
        };
        //填充表格信息
        foreach ($xlsData as $k => $v) {
            $k += 2;
            $objActSheet->setCellValue('A' . $k, $v['id']);
            $objActSheet->setCellValue('B' . $k, $v['phone']);
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
            $objActSheet->setCellValue('D' . $k, $v['real_name']);
            $objActSheet->setCellValue('E' . $k, $v['status']);
            $objActSheet->setCellValue('F' . $k, $v['card']);
            $objActSheet->setCellValue('G' . $k, $v['time']);
            $objActSheet->setCellValue('H' . $k, $v['login_ip']);
            $objActSheet->setCellValue('I' . $k, $v['account']['account']);
            $objActSheet->setCellValue('J' . $k, $v['agent']);
            $objActSheet->setCellValue('K' . $k, $v['subsidiary_organ']);
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
        $outfile = "会员信息表.xls";
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

    public function export_recharge($xlsData)
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
        $arrHeader = array('账户ID', '用户姓名', '手机号', '充值金额', '手续费', '实际到账金额', '创建时间');
        //填充表头信息
        $lenth = count($arrHeader);
        for ($i = 0; $i < $lenth; $i++) {
            $objActSheet->setCellValue("$letter[$i]1", "$arrHeader[$i]");
        };
        //填充表格信息
        foreach ($xlsData as $k => $v) {
            $k += 2;
            $objActSheet->setCellValue('A' . $k, $v['uid']);
            $objActSheet->setCellValue('B' . $k, $v['user']['name']);
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
            $objActSheet->setCellValue('C' . $k, $v['user']['phone']);
            $objActSheet->setCellValue('D' . $k, $v['number']);
            $objActSheet->setCellValue('E' . $k, $v['fee']);
            $objActSheet->setCellValue('F' . $k, $v['number'] - $v['fee']);
            $objActSheet->setCellValue('G' . $k, $v['time']);
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
        $outfile = "会员充值记录表.xls";
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

?>