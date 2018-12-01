<?php

namespace app\index\controller;


use app\index\model\Contract;
use app\index\model\UserAccount;
use think\Request;

class Selection extends Common
{
    private $UserAccount;
    private $Contract;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->UserAccount = new UserAccount();
        $this->Contract = new Contract();
    }

    /**
     * 自选合约
     * @param $id int 自选合约
     * @return \think\response\Json
     */
    public function index($id)
    {
        return msg_handle('', 1, $this->optional_handle($id));
    }

    /**
     * 自选数据处理
     * @param $id
     * @return mixed
     */
    private function optional_handle($id)
    {
        $value = $this->UserAccount->where(array('uid' => $id))->value('concern');
        $list = array();
        if ($value) {
            $map['code|short'] = array('IN', jd($value));
            $data = $this->Contract->where($map)->select();
            foreach ($data as $kye => $value) {
                $arr['bourse_name'] = $value['bourse_name'];
                $arr['name'] = $value['name'];
                $arr['code'] = $value['code'];
                $arr['short'] = $value['short'];
                array_push($list, $arr);
            }
        }
        return $list;
    }

    /**
     * 查询自选
     * @param int $id
     * @param array $data
     * @return array
     */
    public function query_concern($id = 1, $data = array())
    {
        $code = empty($data['code']) ? '' : $data['code'];
        $value = $this->UserAccount->where(array('uid' => $id))->value('concern');
        if ($value) {
            return msg_handle('', 1, in_array($code, jd($value)));
        } else {
            return msg_handle('', 1, in_array($code, array()));
        }
    }

    /**
     * 加入自选股
     * @param $id  int 用户id
     * @param $data array 添加信息
     * @return array
     */
    public function join_optional($id, $data)
    {
        $code = empty($data['code']) ? '' : $data['code'];
        if (empty($code)) {
            $r = msg_handle('合约代码不能为空', 0);
        } elseif ($this->Contract->validate_stock(array('code|short' => $code)) == 0) {
            $r = msg_handle('加入自选股失败', 0);
        } elseif ($this->UserAccount->join_optional($id, $code)) {
            $r = msg_handle('加入自选股成功', 1);
        } else {
            $r = msg_handle('暂时无法加入自选股', 0);
        }
        return $r;
    }

    /**
     * 删除自选合约
     * @param $id  int 用户id
     * @param $data array 添加信息
     * @return array
     */
    public function cancel_concern($id, $data)
    {
        $code = empty($data['code']) ? '' : $data['code'];
        if (empty($code)) {
            $r = msg_handle('合约代码不能为空', 0);
        } elseif ($this->Contract->validate_stock(array('code|short' => $code)) == 0) {
            $r = msg_handle('合约代码无法加入自选', 0);
        } elseif ($this->UserAccount->delete_optional($id, $code)) {
            $r = msg_handle('取消自选股成功', 1, $this->optional_handle($id));
        } else {
            $r = msg_handle('暂时取消自选股', 0);
        }
        return $r;
    }

    public function batch_cancel_concern($id = 1, $data = array())
    {
        $code = empty($data['code']) ? '' : $data['code'];
        if (empty($codes)) {
            $r = msg_handle('合约代码不能为空', 0);
        } else {
            if ($this->UserAccount->delete_optional($id, $code)) {
                $r = msg_handle('取消自选股成功', 1, $this->optional_handle($id));
            } else {
                $r = msg_handle('暂时取消自选股', 0);
            }
        }
        return $r;
    }
}