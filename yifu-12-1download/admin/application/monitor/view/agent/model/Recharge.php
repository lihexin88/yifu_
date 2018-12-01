<?php

namespace app\agent\model;
use think\Db;
class Recharge extends \think\Model {

    public function getList($where) {
        return Db::table('sn_recharge')->alias('r')
                        ->join('sn_user u', 'r.uid=u.id')
                        ->where($where)
                        ->field('u.real_name,r.number,r.pay_type,r.time')
                        ->order('r.time desc')
                        ->select();
    }

}
