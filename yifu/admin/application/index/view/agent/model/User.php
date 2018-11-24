<?php

namespace app\agent\model;

use think\Db;

//客户管理
class User extends \think\Model {

    protected $table = 'sn_user';

    public function getList($id) {
        return $this->where($id)->find();
    }

    public function getUser($id) {
        return Db::table('sn_user')->where($id)->select();
    }

    public function count($id) {
        return $this->where($id)->select();
    }

    public function getLi($where) {
        return Db::table('sn_user')->alias('u')
                        ->join('sn_user_account a', 'a.uid=u.id')
                        ->where($where)
                        ->field('a.bond,u.id,u.real_name,u.phone,a.account,a.profit_loss,a.rec_total,a.wit_total')
                        ->select();
    }

}
