<?php

namespace app\agent\model;

use think\Db;

class CapitalFlow extends \think\Model {

    public function getList($where) {
        return Db::table('sn_capital_flow')->alias('r')
                        ->join('sn_user u', 'r.uid=u.id')
                        ->where($where)
                        ->field('u.real_name,r.number,r.balance,r.type,r.time')
                        ->order('r.time desc')
                        ->select();
    }

    public function getLi() {
        return Db::table('sn_capital_flow')->order('time desc')->select();
    }

}
