<?php

namespace app\agents\model;
use think\Model;

class AgentAccount extends \think\Model {
    protected $table = 'sn_agent_account';
    
    public function updateAccount($id, $admin) {
        return $this->where('uid', $id)->update($admin);
    }

    public function getLi($where) {
        return $this->alias('a')
                        ->join('sn_agent g', 'a.uid=g.id')
                        ->where($where)
                        ->field('g.username,g.userphone,a.profit_loss,a.bond,a.rec_total,a.wit_total,a.total_amount,a.fee_total,a.trade_number')
                        ->find();
    }

}
