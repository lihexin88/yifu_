<?php

namespace app\index\controller;

use app\index\model\UserAccount;
use think\Controller;
use think\Request;

class Auto extends Controller
{
    private $UserAccount;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->UserAccount = new UserAccount();
    }

    public function index()
    {

    }
}