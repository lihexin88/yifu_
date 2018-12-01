<?php

namespace app\monitor\controller;
use think\Request;
class Test extends Common
{
//    private $Bank;

    public function __construct(Request $request)
    {
        parent::__construct($request);
//        $this->User = new User();
    }

    public function index()
    {
        include_once VENDOR_PATH . 'llpay/llpaySdk.php';
        //商户付款流水号
//        $no_order = '201807270002082007';
        $no_order = createOrderNum(1);
        //商户时间
        $dt_order = '20161128165917';
        //金额
        $money_order = '0.01';
        //收款人姓名
        $acct_name = '张顺';
        //银行账号
        $card_no = '6217002430045774511';
        //订单描述
        $info_order = 'test测试1';
        //对私标记
        $flag_card = '0';
        //服务器异步通知地址
        $notify_url = 'http://' . $_SERVER['HTTP_HOST'] . '/Test/lala';
        //平台来源
        $platform = '';
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
//        dump($parameter);exit();
        //建立请求
        $llpaySubmit = new \LLpaySubmit($llpay_config);
//        return json($llpay_config);
        //对参数排序加签名
        $sortPara = $llpaySubmit->buildRequestPara($parameter);
        //建立请求
        $llpaySubmit = new \LLpaySubmit($llpay_config);
        //传json字符串
        $json = json_encode($sortPara);
        $parameterRequest = array(
            "oid_partner" => trim($llpay_config['oid_partner']),
            "pay_load" => ll_encrypt($json, $llpay_config['LIANLIAN_PUBLICK_KEY']) //请求参数加密
        );
        $html_text = $llpaySubmit->buildRequestJSON($parameterRequest, $llpay_payment_url);
        //调用付款申请接口，同步返回0000，是指创建连连支付单成功，订单处于付款处理中状态，最终的付款状态由异步通知告知
        //出现1002，2005，4006，4007，4009，9999这6个返回码时或者没返回码，抛exception（或者对除了0000之后的code都查询一遍查询接口）调用付款结果查询接口，明确订单状态，不能私自设置订单为失败状态，以免造成这笔订单在连连付款成功了，
        //而商户设置为失败,用户重新发起付款请求,造成重复付款，商户资金损失

        //对连连响应报文内容需要用连连公钥验签
        echo $html_text;

    }
    public function lala()
    {
        $uid = session('id');
        if ($uid == 0) {
            $this->redirect('Index/index');
            $r = msg_handle('未登录，无法操作', 0);
            return json($r);
        }
        dump('支付成功');exit;
        return $this->fetch();
    }

    /**
     * 提现操作
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function withdraw()
    {
        if (Request()->isAjax()) {
            $id = session('id');
            $r = $this->verify_login($id);
            if ($r['code']) {
                $order = str_shuffle(time() . str_shuffle(sprintf('%05d', $id)));
                $html = $this->recharge($id, $order, 0.01, '01050000', '张顺');
                $r = msg_handle('', 1, $html);
            }
        }
        return json($r);
    }
    /*=======================================================================================================================*/
    /**
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function verify_login($id)
    {
        if (empty($id)) {
            $r = msg_handle('未登录，无法操作', 0);
        } else {
            $user = $this->User->where(array('id' => $id))->find();
            if (empty($user)) {
                $r = msg_handle('登录超时,请重新登录', 0);
            } else {
                $r = msg_handle('', 1);
            }
        }
        return $r;
    }
}