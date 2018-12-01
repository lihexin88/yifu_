<?php

namespace app\index\controller;

use app\index\model\Depot;
use app\index\model\CapitalFlow;
use app\index\model\Contract;
use app\index\model\Entrust;
use app\index\model\Futures;
use app\index\model\Stock;
use app\index\model\UserAccount;
use think\Request;

class TradeSell extends Common
{
    private $Depot;
    private $UserAccount;
    private $Stock;
    private $Contract;
    private $Futures;
    private $Entrust;
    private $CapitalFlow;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Depot = new Depot();
        $this->UserAccount = new UserAccount();
        $this->Stock = new Stock();
        $this->Contract = new Contract();
        $this->Futures = new Futures();
        $this->Entrust = new Entrust();
        $this->CapitalFlow = new CapitalFlow();
    }

    public function index($id, $order_id, $contract, $pattern, $direction, $mode, $classify, $price, $number)
    {
        $order = $this->Depot->where(array("id" => $order_id, "status" => ["<", 2]))->find();
        $surplus = $order["number"] - $order["frozen"] - $order["finish"];
        if (!$order) {
            $r = msg_handle('请确认订单', 0);
        } else if ($number > $surplus) {
            $r = msg_handle('请确认持仓数量', 0);
        } else {
            $futures = $this->Futures->where(array('id' => $contract['futures']))->find();
            $stock = $this->Stock->where(array('code' => $futures['code']))->find();
            $account = $this->UserAccount->where(array('uid' => $id))->find();
            $total = $number * $stock['fee'];
            if ($total > $account['account']) {
                $r = msg_handle('你的账户资金不足，请及时充值！', 0);
            } else {
                $classify = empty($data['type']) ? 0 : $data['type']; // 0 限价 1：最新价 2：对手价 3：挂单价 4:快速价
                // $price = $this->classify_price($contract['code'], $classify, $price);
                if (empty($price)) {
                    $r = msg_handle('平仓价格不能为空', 0);
                } else {
                    $r = $this->data_handle($id, $order_id, $contract, $pattern, $direction, $mode, $classify, $price, $number, $account, $stock);
                }
            }
        }
        return $r;
    }

    //平仓委托
    public function data_handle($id, $order_id, $contract, $pattern, $direction, $mode, $classify, $price, $number, $account, $stock)
    {
        $order = $this->Depot->where(array("id" => $order_id))->find();
        $bond = 0;
        $fee = $number * $stock['fee'];
        $balance = $account['account'] - $bond;
        $balance1 = $balance - $fee;
        $this->UserAccount->startTrans();
        $res1 = $this->UserAccount->entrust_buy($account, $bond, $fee);
        $res2 = $this->Depot->reduce_position($order_id, $number, $order["frozen"]);
        $res3 = $this->CapitalFlow->add_log($id, $fee, $balance1, 3, $account);
        $res4 = $this->Entrust->add_log($id, $contract, $number, $price, $fee, $bond, $mode, $classify, $direction, $pattern, $stock, $account, $order_id);
        if ($res1 && $res2 && $res3 && $res4) {
            $this->UserAccount->commit();
            $r = msg_handle('申请委托成功', 1);
        } else {
            $this->UserAccount->rollback();
            $r = msg_handle('申请委托失败', 0);
        }
        return $r;
    }

    public function classify_price($code, $classify, $price)
    {
        $list = $this->stock_price($code);
        if ($classify == 1) {
            $price = floatval($list[10]);
        } elseif ($classify == 2) {
            $price = floatval($list[16]);
        } elseif ($classify == 3) {
            $price = floatval($list[4]);
        }
        return $price;
    }

    public function stock_price($code)
    {
        $code = $code . '0';
        $param = [
            'type' => 'CT',
            'cmd' => $code,
            'sty' => 'FDPBPFBTA',
            'st' => 'z',
            'sr' => '',
            'p' => '',
            'ps' => '',
            'cb' => $code,
            'js' => '([[(x)]])',
            'token' => '7bc05d0d4c3c22ef9fca8c2a912d779c',
            '_' => time() * 1000,
        ];
        $url = 'http://nufm.dfcfw.com/EM_Finance2014NumericApplication/JS.aspx' . '?' . $this->bind_param($param);;
        $list = do_get($url);
        $list = str_replace(array("\"]])"), "", $list);
        $list = str_replace(array($code . "([[\""), "", $list);
        $list = explode(',', $list);
        return $list;
    }

    /**
     * 组合参数
     * @param $param
     * @return string
     */
    private function bind_param($param)
    {
        $u = [];
        $sort_rank = [];
        foreach ($param as $k => $v) {
            $u[] = $k . "=" . urlencode($v);
            $sort_rank[] = ord($k);
        }
        asort($u);
        return implode('&', $u);
    }
}
