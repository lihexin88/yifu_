<?php

namespace app\agent\model;

class AgentWithdraw extends \think\Model {
    public function add($admin) {
        return $this->save($admin);
    }

}
