<?php

namespace app\monitor\controller;

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
            $res = $this->User->where('phone', 'like', "%$name%")->find();
            if (!empty($res)) {
                $map['uid'] = $res['id'];
            } else {
                $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
                $current_page = page_judge(input('get.page'));
                $list = $this->Withdraw->query_log($map, $current_page, $this->num);
                $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
                $this->assign('arr', $this->arr_info(input('get.')));
                $this->assign('empty', $this->null_html(12));
                $this->assign('page', $page);
                $this->assign('list', $list['data']);
                return $this->fetch();
            }
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Withdraw->query_log($map, $current_page, $this->num);
        $sum['withdraw_num'] = $this->Withdraw->sum('number');//提现申请总和
        $sum['fee_num'] = $this->Withdraw->sum('fee');//手续费总和
        $sum['num'] = $sum['withdraw_num'] - $sum['fee_num'];//到账总和
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('sum', $sum);
        $this->assign('list', $list['data']);
        return $this->fetch();
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
        $user = $this->User->where('id', $info['uid'])->field('id,real_name')->find();
        $user_bank = $this->UserBanks->where('uid', $info['uid'])->find();
        if (empty($user['real_name']) || empty($user_bank['card'])) {
            $r = msg_handle('用户信息有误,请核对后重试!', 0);
        } else {
            $account = $this->UserAccount->where('uid=' . $info['uid'])->find();
            if ($edit_type == 1) {
                $r = $this->agree_data($account, $info, $user, $user_bank);
            } else {
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
        $res1 = $this->UserAccount->where('uid', $account['uid'])->setInc('wit_total', $info['number']); //交易账户累计提现
        $data['pay_time'] = time();
        $data['status'] = 1;
        $res2 = $this->Withdraw->where('id', $info['id'])->update($data);
        if ($res1 && $res2) {
            $this->UserAccount->commit();
            $r = $this->pay_data($user, $user_bank, $info['number'], $info, $account);
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
            $html['ret_code']='0000';
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
        $da = ['account' => $account['account'] + $info['number'] + $info['fee']];
        $this->UserAccount->startTrans();
        $res1 = $this->UserAccount->where('uid', $account['uid'])->update($da); //返回提现金额
        $data['pay_time'] = time();
        $data['status'] = 2;
        $res2 = $this->Withdraw->where('id', $info['id'])->update($data);
        if ($res1 && $res2) {
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
    public function recharge()
    {
        $map = array();
        $name = trim(input('get.name'));
        if ($name) {
            $res = $this->User->where('phone', 'like', "%$name%")->find();
            if (!empty($res)) {
                $map['uid'] = $res['id'];
            } else {
                $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
                $current_page = page_judge(input('get.page'));
                $list = $this->Recharge->query_log($map, $current_page, $this->num);
                $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
                $this->assign('arr', $this->arr_info(input('get.')));
                $this->assign('empty', $this->null_html(12));
                $this->assign('page', $page);
                $this->assign('list', $list['data']);
                return $this->fetch();
            }
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Recharge->query_log($map, $current_page, $this->num);
        $sum['recharge_num'] = $this->Recharge->sum('number');
        $sum['fee_num'] = $this->Recharge->sum('fee');
        $sum['num'] = $sum['recharge_num'] - $sum['fee_num'];
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('sum', $sum);
        $this->assign('list', $list['data']);
//        dump($list['data']);exit;
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


}

