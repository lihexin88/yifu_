<?php

namespace app\index\controller;

use app\index\model\Deal;
use think\Controller;
use think\Request;

class DealInfo extends Controller
{
    private $Deal;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Deal = new Deal();
    }

    public function index($id = 1)
    {
        $list = $this->Deal->query_log(array('uid' => $id));
        return msg_handle('', 1, $list);
    }
}
































