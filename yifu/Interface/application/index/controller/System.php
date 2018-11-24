<?php

namespace app\index\controller;

use app\index\model\User;
use app\index\model\UserConfig;
use think\Controller;
use think\Request;

class System extends Controller
{
    private $User;
    private $UserConfig;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->User = new User();
        $this->UserConfig = new UserConfig();
    }

    /**
     * 用户设置
     * @param $data uid open_num 开仓默认手数 decla_num 报单最大手数 open_ber 开仓默认数量
     *    contract 选择合约焦点位置 place_order 下单后焦点位置 clear 下单后清空 click 单击行情买卖方向
     * switch 合约切换开平方向 type 合约切换下单价格类型
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function user_config()
    {
        // $data = $_POST;//打开即能用
        $data['uid'] = '1';//测试
        $data['open_num'] = '1';//测试
        $data['decla_num'] = '1';//测试
        $data['open_ber'] = '1';//测试
        $data['contract'] = '1';//测试
        $data['place_order'] = '1';//测试
        $data['clear'] = '1';//测试
        $data['click'] = '1';//测试
        $data['switch'] = '1';//测试
        $data['type'] = '1';//测试
        $data['status'] = '1';//测试
        if (empty($data['open_num'])) {
            $r = msg_handle('开仓默认手数不能为空', 0);
        } elseif (empty($data['decla_num'])) {
            $r = msg_handle('报单最大手数不能为空', 0);
        } else {
            $lis = $this->UserConfig->where('uid=' . $data['uid'])->update($data);
            if ($lis) {
                $r = msg_handle('修改成功', 1);
            } else {
                $r = msg_handle('修改失败', 0);
            }
        }
        return json($r);
    }
}