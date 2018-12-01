<?php



namespace app\index\controller;



use app\index\model\ExchangeRate;

use app\index\model\Protocol;

use app\index\model\User;

use app\index\model\Config;

use app\index\model\UserAccount;

use app\index\model\Agree;

use app\index\model\Entrust;

use think\Controller;

use think\Request;



class Home extends Controller

{

    private $Protocol;

    private $ExchangeRate;



    public function __construct(Request $request = null)

    {

        parent::__construct($request);

        $this->Protocol = new Protocol();

        $this->User = new User();

        $this->Agree = new Agree();

        $this->Entrust = new Entrust();

        $this->ExchangeRate = new ExchangeRate();

    }

    public function index_index(Request $request = null)
    {
        parent::__construct($request);
        //验证是否登录
        if(User::is_user_login()){
        }else{
            $this->redirect("/index.php/login/login_index");
        }
//声明基本参数
        $this->User = new User();
        $this->Agree = new Agree();
        $this->user_id = $_SESSION['think']['id'];

//查询系统配置信息
        //充值手续费率
        $this->recharge_ratio = Config::get(1)['recharge_ratio'];
        //提现手续费率
        $this->withdraw_ratio = Config::get(1)['withdraw_ratio'];
        //人民币美元汇率
        $this->usdt_rmb = Config::get(1)['usdt_rmb'];

//查询用户信息
        $this->uid['uid'] = $this->user_id;
        $this->user_info['id'] =$this->user_id;
        $this->get_user_info = $this->User->query_info($this->user_info);
        $this->username =$this->get_user_info['name'];
        $this->assign("user_info",$this->get_user_info);

//查询用户账户信息
        $get_user_account_where['uid'] = $this->user_id;
        $get_user_account_info = UserAccount::get($get_user_account_where);
        $this->assign("user_account_info",$get_user_account_info);
        return $this->fetch();
    }
    /**
     * 各种协议
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function agree_index($data)
    {
        if (empty($data['type'])) {
            $r = msg_handle('请选择类型', 0);
        } else {
            $type = $data['type'];
            switch ($type) {
                case 1:
                    $map['type'] = 1;
                    break;
                case 2:
                    $map['type'] = 2;
                    break;
                default:
                    $map['type'] = 1;
                    break;
            }
            $data = $this->Agree->query_info($map);
            $r = msg_handle('', 1, $data);
        }
        return $r;
    }


    /**

     * 服务器信息

     * @return array

     */

    public function index()

    {

        $list['quote'] = array(

            array('id' => 1, 'name' => '电信1'),

            array('id' => 2, 'name' => '电信2'),

            array('id' => 3, 'name' => '电信3'),

            array('id' => 4, 'name' => '电信4'),

            array('id' => 5, 'name' => '电信5'),

        );

        $list['trade'] = array(array('id' => 1, 'name' => '电信'));

        return msg_handle('', 1, $list);

    }

    /**

     * 查找历史订单

     * @return array

     */

    public function find_history_order($id,$data)

    {

        $old_day=strtotime($data["year"]."-".$data["month"]."-".$data["day"]);

        $new_day=$data["day"]+1;

        $new_day=strtotime($data["year"]."-".$data["month"]."-".$new_day);

        $list=$this->Entrust->where(array("uid"=>$id,"time"=>[">=",$old_day],"time"=>["<=",$new_day]))->select();

        if(count($list) >= 1){
            foreach($list as $k=>$v){
            $list[$k]["status"]=$this->Entrust->status_name($v["status"]);
            $list[$k]["mold"]=$this->Entrust->mold_name($v["mold"]);
            $list[$k]["type"]=$this->Entrust->type_name($v["type"]);
            $list[$k]["time"]=$v["time"]>0?date("Y-m-d H:i:s",$v["time"]):"无";
            $list[$k]["finish_time"]=$v["finish_time"]>0?date("Y-m-d H:i:s",$v["finish_time"]):"无";
            }
            return msg_handle('', 1, $list);
        }else{
            return msg_handle('暂无数据', 0);
        }

        

        

    }



    /**

     * 查询协议

     * @param $data

     * @return mixed

     */

    public function protocol($data)

    {

        $type = empty($data['type']) ? 0 : $data['type'];//0 行情

        return msg_handle('', 1, $this->Protocol->query_log($type));

    }



    /**

     * 查询汇率

     * @return mixed

     */

    public function exchange_rate()

    {

        return msg_handle('', 1, $this->ExchangeRate->query_log());

    }



    public function about()

    {

        $list['version'] = '1.0.0.0';

        $list['cn_name'] = '香港逸富有限公司';

        $list['en_name'] = '香港逸富有限公司';

        return msg_handle('', 1, $list);

    }





    public function company()

    {

        $array = array(

            array(

                'id' => 1,

                'name' => '国际',

                'data' => array(

                    array('id' => 1, 'name' => '云中心01'),

                    array('id' => 2, 'name' => 'LXY'),

                    array('id' => 3, 'name' => 'HFT'),

                    array('id' => 4, 'name' => 'GKTX'),

                    array('id' => 5, 'name' => 'GMQH(HK)'),

                    array('id' => 6, 'name' => 'QQQT'),

                    array('id' => 7, 'name' => 'EFGS'),

                    array('id' => 8, 'name' => 'TFB_S'),

                    array('id' => 9, 'name' => 'HOME'),

                    array('id' => 10, 'name' => 'GZLY'),

                    array('id' => 11, 'name' => 'TFB_ZH'),

                    array('id' => 12, 'name' => 'GJQH'),

                    array('id' => 13, 'name' => 'ZZGJ'),

                    array('id' => 14, 'name' => 'ZGLP'),

                    array('id' => 15, 'name' => 'RHZ'),

                    array('id' => 16, 'name' => 'HZTZ'),

                    array('id' => 17, 'name' => 'MJRDQH'),

                    array('id' => 18, 'name' => 'JWJR'),

                    array('id' => 19, 'name' => 'MZQHQC'),

                    array('id' => 20, 'name' => 'ZDZD'),

                    array('id' => 15, 'name' => 'QYSJ'),

                    array('id' => 16, 'name' => 'PBHK'),

                    array('id' => 17, 'name' => 'JHQH'),

                    array('id' => 18, 'name' => 'CCNEW'),

                    array('id' => 19, 'name' => 'HTJR'),

                    array('id' => 20, 'name' => 'ASLH'),

                    array('id' => 21, 'name' => 'DDQH'),

                    array('id' => 22, 'name' => 'TTGJ'),

                    array('id' => 23, 'name' => 'IFCL'),

                    array('id' => 24, 'name' => 'LY'),

                    array('id' => 25, 'name' => 'ALPHA'),

                    array('id' => 26, 'name' => 'HMFZ'),

                    array('id' => 27, 'name' => 'ZSRR'),

                    array('id' => 28, 'name' => 'ATTB'),

                    array('id' => 29, 'name' => 'SSHZY'),

                    array('id' => 30, 'name' => 'YUANTA'),

                    array('id' => 31, 'name' => 'AYDF'),

                    array('id' => 32, 'name' => 'JSTF1'),

                    array('id' => 33, 'name' => 'NCPJDZ'),

                    array('id' => 33, 'name' => 'STAM_1'),

                    array('id' => 34, 'name' => 'STAM_2'),

                    array('id' => 35, 'name' => 'RQ'),

                    array('id' => 36, 'name' => 'MAIKE'),

                    array('id' => 37, 'name' => 'ZHJY'),

                    array('id' => 38, 'name' => 'ECMLTD'),

                    array('id' => 39, 'name' => 'ZYZY'),

                    array('id' => 40, 'name' => 'HKYF1'),

                    array('id' => 41, 'name' => 'YFQH'),

                ),

            ),

            array(

                'id' => 2,

                'name' => '国内',

                'data' => array(

                    array('id' => 1, 'name' => 'Cloud'),

                    array('id' => 2, 'name' => 'JYJR'),

                    array('id' => 3, 'name' => 'MKRDQH'),

                    array('id' => 4, 'name' => 'YYT'),

                    array('id' => 5, 'name' => 'UCL'),

                    array('id' => 6, 'name' => 'SCQH'),

                    array('id' => 7, 'name' => 'GJB'),

                    array('id' => 8, 'name' => 'PBCN'),

                    array('id' => 9, 'name' => 'GZF'),

                    array('id' => 10, 'name' => 'YZT'),

                    array('id' => 11, 'name' => 'JTCF'),

                    array('id' => 12, 'name' => 'XS'),

                    array('id' => 13, 'name' => 'QXTX'),

                    array('id' => 14, 'name' => 'QHJGJ'),

                    array('id' => 15, 'name' => 'CFXS'),

                    array('id' => 16, 'name' => 'ASKJ'),

                    array('id' => 17, 'name' => 'QXT'),

                    array('id' => 18, 'name' => 'XRQH'),

                )

            ),

            array(

                'id' => 3, 'name' => '国际试用',

                'data' => array(

                    array('id' => 1, 'name' => '逸富试用'),

                    array('id' => 2, 'name' => '逸富VIP'),

                    array('id' => 3, 'name' => 'DC'),

                    array('id' => 4, 'name' => 'SP'),

                    array('id' => 5, 'name' => 'QQ'),

                    array('id' => 6, 'name' => 'MLJR'),

                ),

            ),

            array(

                'id' => 4, 'name' => '国内试用',

                'data' => array(

                    array('id' => 1, 'name' => '逸富试用'),

                    array('id' => 2, 'name' => '逸富内侧'),

                    array('id' => 3, 'name' => '逸富MLJR'),

                    array('id' => 4, 'name' => '逸富UCL'),

                ),

            ),

        );

        return msg_handle('', 1, $array);

    }

}

























