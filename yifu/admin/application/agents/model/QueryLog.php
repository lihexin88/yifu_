<?php

namespace app\agents\model;

class QueryLog extends \think\Model {

    public function getList($where) {
        return $this->alias('q')
                        ->join('sn_user u', 'q.uid=u.id')
                        ->where($where)
                        ->field('q.status,q.id,u.level,q.time,u.real_name,u.phone,q.name,q.capital,q.bond,q.ratio,q.price,q.expiry_time,q.cycle,q.short')
                        ->select();
    }

}
