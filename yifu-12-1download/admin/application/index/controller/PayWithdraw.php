<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

class PayWithdraw extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }

    public function index($no_order, $money_order, $acct_name, $card_no, $info_order)
    {
        include_once VENDOR_PATH . 'llpay/llpaySdk.php';
        $dt_order = date('YmdHis');
        $flag_card = '0';
        //服务器异步通知地址
        $notify_url = 'http://' . $_SERVER['HTTP_HOST'] . '/pay_withdraw/lala';
        //平台来源
        $platform = 'admin';
        //版本号
        $api_version = '1.0';
        //实时付款交易接口地址
        $llpay_payment_url = 'https://instantpay.lianlianpay.com/paymentapi/payment.htm';
        //需http://格式的完整路径，不能加?id=123这类自定义参数
        /************************************************************/
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "oid_partner" => trim($llpay_config['oid_partner']),
            "sign_type" => trim($llpay_config['sign_type']),
            "no_order" => $no_order,
            "dt_order" => $dt_order,
            "money_order" => $money_order,
            "acct_name" => $acct_name,
            "card_no" => $card_no,
            "info_order" => $info_order,
            "flag_card" => $flag_card,
            "notify_url" => $notify_url,
            "platform" => $platform,
            "api_version" => $api_version
        );
        //建立请求
        $llpaySubmit = new \LLpaySubmit($llpay_config);
        //对参数排序加签名
        $sortPara = $llpaySubmit->buildRequestPara($parameter);
        //建立请求
        $llpaySubmit = new \LLpaySubmit($llpay_config);
        //传json字符串
        $json = json_encode($sortPara);
        $parameterRequest = array(
            "oid_partner" => trim($llpay_config['oid_partner']),
            "pay_load" => ll_encrypt($json, $llpay_config['LIANLIAN_PUBLICK_KEY'])
        );
        $html_text = $llpaySubmit->buildRequestJSON($parameterRequest, $llpay_payment_url);
        return json_decode($html_text, true);
    }

    /**
     * 回调信息
     * @return bool
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function lala()
    {
        $str = file_get_contents("php://input");
        file_put_contents("log.txt", $str, FILE_APPEND);
        $str = json_decode($str, true);
        if ($str) {
            $list = json_decode($str, true);
            $oid_partner = $list['oid_partner'];
            $sign_type = $list['sign_type'];
            $sign = $list['sign'];
            $dt_order = $list['dt_order'];
            $no_order = $list['no_order'];
            $money_order = $list['money_order'];
            $oid_paybill = $list['oid_paybill'];
            $info_order = $list['info_order'];
            $result_pay = $list['result_pay'];
            $settle_date = $list['settle_date'];
            if (trim($llpay_config['oid_partner']) != $oid_partner) {   //商户号错误
                return false;
            } else {
                $parameter = array(
                    'oid_partner' => $oid_partner, 'sign_type' => $sign_type,
                    'dt_order' => $dt_order, 'no_order' => $no_order,
                    'oid_paybill' => $oid_paybill, 'money_order' => $money_order,
                    'result_pay' => $result_pay, 'settle_date' => $settle_date,
                    'info_order' => $info_order,
                );
                $result_pay = $parameter['result_pay'];//支付结果，SUCCESS：为支付成功
                if ($result_pay == "SUCCESS") {
                    $no_order = $parameter['no_order'];//商户订单号
                    $money_order = $parameter['money_order'];// 支付金额
                    $this->recharge_data($no_order, $money_order, $str);
                }
                file_put_contents("log.txt", "异步通知 验证成功\n", FILE_APPEND);
                die("{'ret_code':'0000','ret_msg':'交易成功'}"); //请不要修改或删除
            }
        } else {
            die("{'ret_code':'9999','ret_msg':'没有返回信息'}");
        }
    }

    /**
     * @param $order
     * @param $money
     * @param $data
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function recharge_data($order, $money, $data)
    {
        $list = $this->query_info($order, $money);
        if ($list) {
            $this->modify_recharge($list, $data);
        }
    }

    /**
     * @param $list
     * @param $data
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function modify_recharge($list, $data)
    {
        $map['id'] = $list['id'];
        $map['status'] = 1;
        $map['pay_time'] = time();
        $map['callback'] = $data;
        return Db::name('withdraw')->update($map);
    }

    /**
     * @param $order string 订单编号
     * @param $number float 数量
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function query_info($order, $number)
    {
        $map['pay_order'] = $order;
        $map['number'] = $number;
        $map['status'] = 0;
        return  Db::name('withdraw')->where($map)->find();
    }
}