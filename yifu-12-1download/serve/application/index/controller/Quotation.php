<?php



namespace app\index\controller;



use app\index\model\Contract;

use app\index\model\Exchange;

use app\index\model\Futures;

use think\Request;



class Quotation extends Common

{

    private $Exchange;

    private $Futures;

    private $Contract;



    public function __construct(Request $request = null)

    {

        parent::__construct($request);

        $this->Exchange = new Exchange();

        $this->Futures = new Futures();

        $this->Contract = new Contract();

    }









    /**

     * 交易所

     * @return array

     */

    public function bourse()

    {

        $list['data'] = $this->Exchange->query_log();

        $list['variety'] = $this->query_log(4);

        $map = array('status' => 1, 'bourse' => 4);

        $list['contract'] = $this->Contract->query_log($map);

        return msg_handle('', 1, $list);

    }



    /**

     * 交易品种

     * @param array $data

     * @return array

     */

    public function variety($data = array())

    {

        $id = empty($data['id']) ? '4' : $data['id'];

        $map = array('status' => 1, 'bourse' => $id);

        $list['variety'] = $this->query_log($id);

        $list['contract'] = $this->Contract->query_log($map);

        return msg_handle('', 1, $list);

    }



    /**

     * 交易合约

     * @param array $data

     * @return array

     */

    public function contract($data = array())

    {

        $id = empty($data['id']) ? '' : $data['id'];

        $name = empty($data['name']) ? '' : $data['name'];

        $array = $this->Futures->where(array('status' => 1, 'name|short' => $name))->find();

        if (empty($array)) {

            $map = array('status' => 1, 'industry' => $name, 'bourse' => $id);

        } else {

            $map = array('status' => 1, 'futures' => $array['id']);

        }

        return msg_handle('', 1, $this->Contract->query_log($map));

    }



    /**

     * @param $bourse

     * @return array

     */

    private function query_log($bourse)

    {

        $list = $this->Futures->where(array('status' => 1, 'bourse' => $bourse))->select();

        $data = array();

        foreach ($list as $key => $value) {

            if (!in_array($value['short'], $data)) {

                array_push($data, trim($value['short']));

            }

        }

        foreach ($list as $key => $value) {

            if (!in_array($value['industry'], $data)) {

                array_push($data, trim($value['industry']));

            }

        }

        array_push($data, '主力连续');

        array_push($data, '主力合约');

        return $data;

    }



    public function index()

    {

        $list = $this->Exchange->query_log();

        foreach ($list as $key => $value) {

            $list[$key]['variety'] = $this->Futures->query_log($value['id']);

            $list[$key]['contract'] = $this->Contract->query_log($value['id']);

        }

        return msg_handle('', 1, $list);

    }



    /**
     * 添加自选品种列表
     * @param $id  int 用户id
     * @param $data array 添加信息
     * @return array
     */

    public function bourse_variety($id,$data)

    {

        $contract = $this->Futures->where(array("code"=>$data["code"]))->find();
        if($contract){
            $string_arr = explode(",", $contract["self_id"]);
            if(!in_array($id,$string_arr)){
                $date=array();
                $date["self_id"]=$contract["self_id"].",".$id;
                $list=$this->Futures->where(array("id"=>$contract["id"]))->update($date);
                if($list){
                    $r=msg_handle('添加成功', 1);  
                }else{
                    $r=msg_handle('添加失败', 0);
                }
            }else{
                $r=msg_handle('品种已存在', 0);
            }
            
        }else{
            $r=msg_handle('品种不存在', 0);
        }

        return $r;

    }



    /**

     * 查询自选品种信息

     * @param $id int 品种id
     
     * @param $data array 添加信息

     * @return array

     */

    public function futures_contract($id,$data)

    {

        $list=$this->Futures->where('FIND_IN_SET(:id,self_id)',['id' => $id])->select();
        foreach($list as $k=>$v){
            if($v["type"] == 1){
                $list[$k]["type"]="国外";
            }else{
                $list[$k]["type"]="国内";
            }
        }
        $r=msg_handle('', 1, $list);
        return $r;

    }



    /**

     * 合约分类

     * @return array

     */

    public function futures_type()

    {

        $list = $this->Futures->where(array('status' => 1))->select();

        $data1 = array();

        $data2 = array();

        array_push($data1, '主力合约');

        array_push($data2, '主力合约');

        foreach ($list as $key => $value) {

            if (!in_array($value['industry'], $data1) && $value['type'] == 0) {

                array_push($data1, trim($value['industry']));

            }

            if (!in_array($value['industry'], $data2) && $value['type'] == 1) {

                array_push($data2, trim($value['industry']));

            }

        }



        return msg_handle('', 1, array($data1, $data2));

    }



    /**

     * 查询合约

     * @param $data

     * @return array

     */

    public function query_contract($data)

    {

        $name = empty($data['name']) ? '' : $data['name'];

        $type = empty($data['type']) ? '' : $data['type'];

        if ($name == '主力合约') {

            $map = array('status' => 1, 'mold' => 1, 'type' => $type);

        } else {

            $map = array('status' => 1, 'industry' => $name, 'type' => $type);

        }

        return msg_handle('', 1, $this->Contract->query_log($map));

    }

}















