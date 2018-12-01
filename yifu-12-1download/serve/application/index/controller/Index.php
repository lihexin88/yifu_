<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

class Index extends Controller
{
    private $token;
    private $Login;
    private $Home;
    private $Account;
    private $EntrustInfo;
    private $DealInfo;
    private $Quotation;
    private $DepotInfo;
    private $Selection;
    private $Trade;
    private $EntrustCancel;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->token = -1;
        $this->Login = new Login();
        $this->Home = new Home();
        $this->Account = new Account();
        $this->EntrustInfo = new EntrustInfo();
        $this->DealInfo = new DealInfo();
        $this->Quotation = new Quotation();
        $this->DepotInfo = new DepotInfo();
        $this->Selection = new Selection();
        $this->Trade = new Trade();
        $this->EntrustCancel = new EntrustCancel();
    }

    public function index1()
    {
        $list = $this->Selection->batch_cancel_concern(1);
//        $param = [
//            'type' => 'CT',
//            'cmd' => 'CL18Z0',
//            'sty' => 'FDPBPFBTA',
//            'st' => 'z',
//            'sr' => '',
//            'p' => '',
//            'ps' => '',
//            'cb' => 'CL18Z0',
//            'js' => '([[(x)]])',
//            'token' => '7bc05d0d4c3c22ef9fca8c2a912d779c',
//            '_' => time() * 1000,
//        ];
//        $url = 'http://nufm.dfcfw.com/EM_Finance2014NumericApplication/JS.aspx' . '?' . $this->bind_param($param);;
//        $list = do_get($url);
//        $list = str_replace(array("\"]])"), "", $list);
//        $list = str_replace(array("CL18Z0([[\""), "", $list);
//        $list = explode(',', $list);
        print_r($list);
    }

    /**
     * @return \think\response\Json
     */
    public function index()
    {
        $nozzle = input('post.nozzle');
        $data = input('post.');
        switch ($nozzle) {
            case 'index_info':          //登录信息
                $r = $this->index_info();
                break;
            case 'company':          //开户公司
                $r = $this->company();
                break;
            case 'exchange_rate':       //汇率信息
                $r = $this->exchange_rate();
                break;
            case 'bourse':             //交易所信息
                $r = $this->bourse();
                break;
            case 'variety':             //品种信息
                $r = $this->variety($data);
                break;
            case 'contract':             //合约信息
                $r = $this->contract($data);
                break;
            case 'quotation':             //行情信息
                $r = $this->quotation();
                break;
            case 'bourse_variety':       //添加自选品种信息
                $r = $this->bourse_variety($data);
                break;
            case 'futures_contract':       //查找自选品种信息
                $r = $this->futures_contract($data);
                break;
            case 'futures_type':       //合约类型
                $r = $this->futures_type();
                break;
            case 'query_contract':       //查询合约
                $r = $this->query_contract($data);
                break;
            case 'about':             //关于信息
                $r = $this->about();
                break;
            case 'login':               //登录操作    //phone password type
                $r = $this->login($data);
                break;
            case 'protocol':            //协议信息   //type 2
                $r = $this->protocol($data);
                break;
            case 'account':             //账户信息
                $r = $this->account($data);
                break;
            case 'entrust_info':        //委托信息
                $r = $this->entrust_info($data);
                break;
            case 'cancel_index':        //可撤销委托
                $r = $this->cancel_index($data);
                break;
            case 'deal_info':           //成交信息
                $r = $this->deal_info($data);
                break;
            case 'depot_info':           //持仓信息
                $r = $this->depot_info($data);
                break;
            case 'query_concern':           //查询自选 code IC1811
                $r = $this->query_concern($data);
                break;
            case 'concern':           //我的自选
                $r = $this->concern($data);
                break;
            case 'join_optional':           //加入自选 code IC1811
                $r = $this->join_optional($data);
                break;
            case 'cancel_concern':           //取消自选 code IC1811
                $r = $this->cancel_concern($data);
                break;
            case 'batch_cancel':           //取消自选 code IC1811,ICO
                $r = $this->batch_cancel($data);
                break;
            case 'trade':
                $r = $this->trade($data);
                break;
            case 'cancel_order':
                $r = $this->cancel_order($data);
                break;
            case 'all_entrust':
                $r = $this->all_entrust($data);
                break;

            case 'find_user_config'://查找用户配置
                $r = $this->find_user_config($data);
                break;
            case 'find_check_config'://查找用户止盈止损配置
                $r = $this->find_check_config($data);
                break;
            case 'update_user_config'://修改用户配置
                $r = $this->update_user_config($data);
                break;
            case 'update_check_config'://修改用户止盈止损配置
                $r = $this->update_check_config($data);
                break;
            case 'edit_pass'://修改安全密码APP
                $r = $this->edit_pass($data);
                break;
            case 'stock_price'://修改安全密码APP
                $r = $this->stock_price($data);
                break;
            case 'find_history_order'://查找历史订单
                $r = $this->find_history_order($data);
                break;
            case 'set_check_full'://设置止盈止损
                $r = $this->set_check_full($data);
                break;
            case 'find_user_depot'://查找可设置止盈止损的订单
                $r = $this->find_user_depot($data);
                break;
            case 'find_repeal_order'://查找用户可撤销订单
                $r = $this->find_repeal_order($data);
                break;
            case 'dispose_profit_loss'://计划任务  处理止盈止损订单
                $r = $this->dispose_profit_loss($data);
                break;
            case 'set_entrust_condition'://条件单设置
                $r = $this->set_entrust_condition($data);
                break;
            case 'find_entrust_condition'://查找条件单
                $r = $this->find_entrust_condition($data);
                break;
            case 'del_entrust_condition'://删除条件单
                $r = $this->del_entrust_condition($data);
                break;
            case 'del_entrust_condition_all'://删除全部条件单
                $r = $this->del_entrust_condition_all($data);
                break;
            case 'find_condition_setuse'://删除全部条件单
                $r = $this->find_condition_setuse($data);
                break;
            case 'alone_condition_setuse'://删除全部条件单
                $r = $this->alone_condition_setuse($data);
                break;
            case 'user_close_order'://查找用户最早的一个可平仓订单
                $r = $this->user_close_order($data);
                break;
            case 'lock_position'://快速锁仓
                $r = $this->lock_position($data);
                break;
            default:
                $r = msg_handle('没有传递接口名称', 0);
                break;
        }
        return json($r);
    }

    /**
     * 取消全部委托
     * @param $data
     * @return array
     */
    public function all_entrust($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $r = $this->EntrustCancel->index($r['data']['id']);
        }
        return $r;
    }

    /**
     * 单独取消委托
     * @param $data
     * @return array
     */
    public function cancel_order($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $r = $this->EntrustCancel->cancel_order($r['data']['id'], $data);
        }
        return $r;
    }

    public function trade($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $r = $this->Trade->index($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     * 取消自选
     * @param $data
     * @return array
     */
    public function cancel_concern($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $r = $this->Selection->cancel_concern($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     * 批量取消
     * @param $data
     * @return array
     */
    public function batch_cancel($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $r = $this->Selection->batch_cancel_concern($r['data']['id'], $data);
        }
        return $r;
    }

    /*
     *查询合约价格
     * @param $data
     * @return array
     */
    public function stock_price($data)
    {
        $r = $this->EntrustCancel->stock_price($data["code"], $data["price"]);
        return $r;
    }

    /**
     * 加入自选
     * @param $data
     * @return array
     */
    public function join_optional($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $r = $this->Selection->join_optional($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     * 我的自选
     * @param $data
     * @return array|\think\response\Json
     */
    public function concern($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $r = $this->Selection->index($r['data']['id']);
        }
        return $r;
    }

    /**
     * 查询自选
     * @param $data
     * @return array
     */
    public function query_concern($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $r = $this->Selection->query_concern($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     * 交易所信息
     * @return array
     */
    public function bourse()
    {
        return $this->Quotation->bourse();
    }

    /**
     * 添加自选品种信息
     * @param $data
     * @return array
     */
    public function bourse_variety($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $r = $this->Quotation->bourse_variety($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     * 查询自选品种信息
     * @param $data
     * @return array
     */
    public function futures_contract($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $r = $this->Quotation->futures_contract($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     * 合约类型
     * @return array
     */
    public function futures_type()
    {
        return $this->Quotation->futures_type();
    }

    /**
     * 查询合约
     * @param $data
     * @return array
     */
    public function query_contract($data)
    {
        return $this->Quotation->query_contract($data);
    }

    public function variety($data)
    {
        return $this->Quotation->variety($data);
    }

    public function contract($data)
    {
        return $this->Quotation->contract($data);
    }

    /**
     * 行情信息
     * @return array
     */
    public function quotation()
    {
        return $this->Quotation->index();
    }

    public function depot_info($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $r = $this->DepotInfo->index($r['data']['id']);
        }
        return $r;
    }

    /**
     * 成交信息
     * @param $data
     * @return array
     */
    public function deal_info($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $r = $this->DealInfo->index($r['data']['id']);
        }
        return $r;
    }

    /**
     * 委托信息
     * @param $data
     * @return array
     */
    public function entrust_info($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $r = $this->EntrustInfo->index($r['data']['id']);
        }
        return $r;
    }

    public function cancel_index($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $r = $this->EntrustInfo->index($r['data']['id']);
        }
        return $r;
    }

    /**
     * 账户信息
     * @param $data
     * @return array
     */
    public function account($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $r = $this->Account->index($r['data']['id']);
        }
        return $r;
    }

    /**
     * 汇率信息
     * @return mixed
     */
    public function exchange_rate()
    {
        return $this->Home->exchange_rate();
    }

    public function about()
    {
        return $this->Home->about();
    }

    /**
     * 查询协议
     * @param $data
     * @return mixed
     */
    public function protocol($data)
    {
        return $this->Home->protocol($data);
    }

    public function company()
    {
        return $this->Home->company();
    }

    /**
     * 登录页面行情信息
     * @return array
     */
    public function index_info()
    {
        return $this->Home->index();
    }

    /**
     * 登录信息
     * @param $data array
     * @return array
     */
    private function login($data)
    {
        return $this->Login->index($data);
    }

    /**
     * 修改安全密码APP
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit_pass($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Login = new Login();
            $r = $this->Login->edit_pass($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     * 验证 token
     * @param $data
     * @return array
     */
    private function verify_token($data)
    {
        if (empty($data['token'])) {
            $r = msg_handle('登录超时，请重新登录', -1);
        } else {
            // $list = Db::name('user')->where(array('id' => 1, 'status' => 1))->find();
            $list = Db::name('user')->where(array('token' => $data['token'], 'status' => 1))->find();
            if (!$list) {
                $r = msg_handle('异常登录，请重新登录', -1);
            } else {
                $r = msg_handle('', 1, $list);
            }
        }
        return $r;
    }

    /**
     *  查找用户配置
     * @param $data
     * @return array
     */
    public function find_user_config($data)
    {
        $this->Account = new Account();
        $r = $this->Account->find_user_config($data);
        return $r;
    }

    /**
     *  查找用户止盈止损配置
     * @param $data
     * @return array
     */
    public function find_check_config($data)
    {
        $this->Account = new Account();
        $r = $this->Account->find_check_config($data);
        return $r;
    }

    /**
     *  修改用户配置
     * @param $data
     * @return array
     */
    public function update_user_config($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Account = new Account();
            $r = $this->Account->update_user_config($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     *  修改用户止盈止损配置
     * @param $data
     * @return array
     */
    public function update_check_config($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Account = new Account();
            $r = $this->Account->update_check_config($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     *  查找历史订单
     * @param $data
     * @return array
     */
    public function find_history_order($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Home = new Home();
            $r = $this->Home->find_history_order($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     *  查找历史订单
     * @param $data
     * @return array
     */
    public function set_check_full($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Account = new Account();
            $r = $this->Account->set_check_full($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     *  查找用户可设置止盈止损的持仓订单
     * @param $data
     * @return array
     */
    public function find_user_depot($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Account = new Account();
            $r = $this->Account->find_user_depot($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     *  查找用户可撤销订单
     * @param $data
     * @return array
     */
    public function find_repeal_order($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Account = new Account();
            $r = $this->Account->find_repeal_order($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     *  计划任务  处理止盈止损订单
     * @param $data
     * @return array
     */
    public function dispose_profit_loss($data)
    {
        $this->EntrustCancel = new EntrustCancel();
        $r = $this->EntrustCancel->dispose_profit_loss($data);
        return $r;
    }

    /**
     *  条件单设置
     * @param $data
     * @return array
     */
    public function set_entrust_condition($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Account = new Account();
            $r = $this->Account->set_entrust_condition($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     * 查找条件单
     * @param $data
     * @return array
     */
    public function find_entrust_condition($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Account = new Account();
            $r = $this->Account->find_entrust_condition($r['data']['id']);
        }
        return $r;
    }

    /**
     * 删除条件单
     * @param $data
     * @return array
     */
    public function del_entrust_condition($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Account = new Account();
            $r = $this->Account->del_entrust_condition($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     * 删除全部条件单
     * @param $data
     * @return array
     */
    public function del_entrust_condition_all($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Account = new Account();
            $r = $this->Account->del_entrust_condition_all($r['data']['id']);
        }
        return $r;
    }

    /**
     * 查找符合要求的条件单(计划任务)
     * @param $data
     * @return array
     */
    public function find_condition_setuse($data)
    {
        $this->EntrustCancel = new EntrustCancel();
        $r = $this->EntrustCancel->find_condition_setuse($data);
        return $r;
    }

    /**
     * 处理条件单
     * @param $data
     * @return array
     */
    public function alone_condition_setuse($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->EntrustCancel = new EntrustCancel();
            $r = $this->EntrustCancel->alone_condition_setuse($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     * 查找用户最早的一个可平仓订单
     * @param $data
     * @return array
     */
    public function user_close_order($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->EntrustCancel = new EntrustCancel();
            $r = $this->EntrustCancel->user_close_order($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     * 用户快捷锁仓
     * @param $data
     * @return array
     */
    public function lock_position($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Trade = new Trade();
            $r = $this->Trade->lock_position($r['data']['id'], $data);
        }
        return $r;
    }
}
