<?php

namespace app\index\controller;

use app\index\model\Contract;
use app\index\model\Exchange;
use app\index\model\Futures;
use think\Request;

class ContractPrice extends Common
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


    public function index()
    {
        $list = $this->Exchange->query_log();
        foreach ($list as $key => $value) {
            $this->contract($value['id']);
        }
    }

    public function contract($id)
    {
        $map = array('status' => 1, 'bourse' => $id);
        $list = $this->Contract->query_log($map);
        print_r($list);
    }

    public function cff_ex1()
    {
        $list = do_get('https://hq.sinajs.cn/?_=0.44639441210177244&list=nf_IC1903');
        $list = str_replace(array("var hq_str_", "\""), "", $list);
        $list = str_replace(array("="), ",", $list);
        $list = explode(',', trim($list));
        $list['num'] = $list[4] - $list[15];
        $list['ratio'] = round(($list['num'] / $list[15]) * 100, 2);
        $list['high'] = $list[15] * (1.1);
        $list['low'] = $list[15] * (0.9);
    }


}