<?php

namespace app\agent\model;

use think\Db;

class Withdraw extends \think\Model {
	protected $table = 'sn_withdraw';

    public function getList($where) {
        return Db::table('sn_withdraw')->alias('w')
                        ->join('sn_user u', 'w.uid=u.id')
                        ->where($where)
                        ->field('u.real_name,w.order,w.number,w.fee,w.examine_time')
                        ->order('w.time desc')
                        ->select();
    }

}
