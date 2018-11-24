<?php

namespace app\index\controller;

use app\index\model\User;
use think\Controller;
use think\Request;

class Index extends Controller
{

    private $token;
    private $User;
    private $Login;
    private $Home;
    private $Orders;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->token = -1;
    }


    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $nozzle = input('post.nozzle');
        switch ($nozzle) {
            case 'quotation_login'://行情登录
                $data = input('post.'); //phone password
                $r = $this->quotation_login($data);
                break;
            case 'login'://交易登录
                $data = input('post.'); //phone password
                $r = $this->login($data);
                break;
            case 'logout'://退出登录
                $r = $this->logout();
                break;
            case 'agree'://各种协议
                $data = input('post.'); //phone code reid ;
                $r = $this->agree($data);
                break;
            case 'ordinary_order'://普通下单
                $data = input('post.');
                $r = $this->ordinary_order($data);
                break;
            case 'register'://222
                $data = input('post.'); //phone code reid ;
                $r = $this->register($data);
                break;

            case 'find_position'://查找用户持仓
                $data = input('post.'); //phone code reid ;
                $r = $this->find_position($data);
                break;
            case 'position_list'://用户持仓列表(下列表)
                $data = input('post.'); //phone code reid ;
                $r = $this->position_list($data);
                break;

            case 'find_entrust'://查找用户委托
                $data = input('post.'); //phone code reid ;
                $r = $this->find_entrust($data);
                break;

            case 'find_trade_log'://查找用户成交记录
                $data = input('post.'); //phone code reid ;
                $r = $this->find_trade_log($data);
                break;
            case 'user_buy'://有户购买
                $data = input('post.'); //phone code reid ;
                $r = $this->user_buy($data);
                break;
            case 'user_close'://有户购买
                $data = input('post.'); //phone code reid ;
                $r = $this->user_close($data);
                break;
            case 'revocation_order'://撤单操作
                $data = input('post.'); //phone code reid ;
                $r = $this->revocation_order($data);
                break;
            case 'revocation_order_all'://撤单操作
                $data = input('post.'); //phone code reid ;
                $r = $this->revocation_order_all($data);
                break;
            case 'find_price'://计算价格
                $data = input('post.'); //phone code reid ;
                $r = $this->find_price($data);
                break;
            case 'dispose_entrust'://委托处理
                $data = input('post.'); //phone code reid ;
                $r = $this->dispose_entrust($data);
                break;
            case 'set_profit'://止盈止损
                $data = input('post.'); //phone code reid ;
                $r = $this->set_profit($data);
                break;
            case 'profit_state'://止盈止损处理
                $data = input('post.'); //phone code reid ;
                $r = $this->profit_state($data);
                break;

            default:
                $r = msg_handle('没有传递接口名称', 0);
                break;
        }
        return json($r);
    }

    /**
     * 行情登录
     * @param $data
     * @return mixed
     */
    private function quotation_login($data)
    {
        $this->Login = new Login();
        $r = $this->Login->quotation_login($data);
        return $r;
    }

    /**
     * 交易登录
     * @param $data
     * @return array
     */
    private function login($data)
    {
        $this->Login = new Login();
        $r = $this->Login->login($data);
        return $r;
    }

    /**
     * 退出登录
     * @return array
     */
    private function logout()
    {
        $this->Login = new Login();
        return $this->Login->logout();
    }

    /**
     * 各种协议
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function agree($data)
    {
        $this->Home = new Home();
        return $this->Home->agree($data);
    }

    /**
     * 普通下单
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function ordinary_order($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Orders = new Orders();
            $r = $this->Orders->create_order($r['data']['id'], $data, 1);
        }
        return $r;
    }

    private function login1($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Member = new Member();
            $r = $this->Member->recom_code($r['data']['id']);
        }
        return $r;
    }

    /**
     * 验证 token
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function verify_token($data)
    {
        $this->User = new User();
        if (empty($data['token'])) {
            $r = msg_handle('登录超时，请重新登录', -1);
        } else {
            $user = $this->User->verify_token($data['token']);
            if (!$user) {
                $r = msg_handle('异常登录1，请重新登录', -1);
            } else {
                $r = msg_handle('数据正规', 1, $user);
            }
        }
        return $r;
    }


    /**
     *  查找用户持仓
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function find_position($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Orders = new Orders();
            $r = $this->Orders->find_position($r['data']['id']);
        }
        return $r;
    }

    /**
     * 用户持仓下列表
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function position_list($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Orders = new Orders();
            $r = $this->Orders->position_list($r['data']['id']);
        }
        return $r;
    }

    /**
     *  查找用户委托
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function find_entrust($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Orders = new Orders();
            $r = $this->Orders->find_entrust($r['data']['id']);
        }
        return $r;
    }
    /**
     *  查找用户成交记录
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function find_trade_log($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Orders = new Orders();
            $r = $this->Orders->find_trade_log($r['data']['id']);
        }
        return $r;
    }

    /**
     *  用户购买
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function user_buy($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Orders = new Orders();
            $r = $this->Orders->user_buy($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     *  用户平仓
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function user_close($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Orders = new Orders();
            $r = $this->Orders->user_close($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     *  撤单操作
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function revocation_order($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Orders = new Orders();
            $r = $this->Orders->revocation_order($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     *  全部撤单
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function revocation_order_all($data)
    {
        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Orders = new Orders();
            $r = $this->Orders->revocation_order_all($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     *  计算价格
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function find_price($data)
    {

        $this->Orders = new Orders();
        $r = $this->Orders->find_price($data);

        return $r;
    }

    /**
     *  处理委托
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function dispose_entrust($data)
    {

        $this->Orders = new Orders();
        $r = $this->Orders->dispose_entrust($data);

        return $r;
    }

    /**
     *  止盈止损
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function set_profit($data)
    {

        $r = $this->verify_token($data);
        if ($r['code'] == 1) {
            $this->Orders = new Orders();
            $r = $this->Orders->set_profit($r['data']['id'], $data);
        }
        return $r;
    }

    /**
     *  止盈止损处理
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function profit_state($data)
    {


        $this->Orders = new Orders();
        $r = $this->Orders->profit_state($data);

        return $r;
    }

}
