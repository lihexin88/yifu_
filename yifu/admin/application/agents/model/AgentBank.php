<?php

namespace app\agents\model;

//代理银行卡
class AgentBank extends \think\Model {

    public function add($admin) {
        return $this->save($admin);
    }

    public function getById($where) {
        return $this->where($where)->find();
    }

}
