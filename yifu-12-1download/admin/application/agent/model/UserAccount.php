<?php

namespace app\agent\model;
use think\Db;
class UserAccount extends \think\Model {
	protected $table = 'sn_user_account';
    public function getLi($where){
        return Db::table('sn_user_account')->alias('a')
                ->join('sn_user u','a.uid=u.id')
                ->where($where)
                ->field('u.time,u.id,u.real_name,u.phone,a.account,a.profit_loss,a.rec_total,a.wit_total')
                ->order('id desc')
                ->select();
    }
}
