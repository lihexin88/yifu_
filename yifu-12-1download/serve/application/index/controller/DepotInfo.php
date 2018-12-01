<?php

namespace app\index\controller;

use app\index\model\Depot;
use think\Controller;
use think\Request;

class DepotInfo extends Controller
{
    private $Depot;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Depot = new Depot();
    }

    public function index($id = 1)
    {
        $list = $this->Depot->query_log(array('uid' => $id));
        return msg_handle('', 1, $list);
    }
}