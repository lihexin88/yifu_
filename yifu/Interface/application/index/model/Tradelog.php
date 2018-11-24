<?php

namespace app\index\model;

use think\Model;

class Tradelog extends Model
{
    protected $table = 'sn_trade_log';

    public function add_account($id, $number)
    {
        return $this->where(array('uid' => $id))->find();
    }
    //616d1394ee2715910cea24365a80e99b
//Ur?j!AY+"y3DvCKa%6s4WXVBTe10adc3949ba59abbe56e057f20f883e

}
