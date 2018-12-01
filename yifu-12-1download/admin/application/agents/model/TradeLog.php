<?php

namespace app\agents\model;

class TradeLog extends \think\Model {

    public function getList($where) {
        return $this->alias('t')
                        ->join('sn_user u', 't.uid=u.id')
                        ->where($where)
                        ->field('t.profit_loss,t.fund_time,t.close_time,t.status,t.id,u.level,t.time,u.real_name,u.phone,t.name,t.capital,t.bond,t.ratio,t.price,t.expiry_time,t.cycle,t.short')
                         ->order('t.expiry_time','desc')   
                ->select();
    }

}
