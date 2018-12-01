<?php



namespace app\index\controller;



use app\index\model\Entrust;

use think\Controller;

use think\Request;



class EntrustInfo extends Controller

{

    private $Entrust;



    public function __construct(Request $request = null)

    {

        parent::__construct($request);

        $this->Entrust = new Entrust();

    }



    public function index($id)

    {

        $list = $this->Entrust->query_log(array('uid' => $id));

        return msg_handle('', 1, $list);

    }



    public function cancel_index($id)

    {

        $list = $this->Entrust->query_log(array('uid' => $id, 'status' => array('in', array(0, 1))));

        return msg_handle('', 1, $list);

    }



}























































































