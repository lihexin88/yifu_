<?php

namespace app\index\controller;

use app\index\model\User;
use app\index\model\Agree;
use think\Controller;
use think\Request;

class Home extends Controller
{
    private $User;
    private $Agree;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->User = new User();
        $this->Agree = new Agree();
    }

    /**
     * 各种协议
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function agree($data)
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
}