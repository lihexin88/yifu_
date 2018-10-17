<?php

namespace app\agents\model;

class AgentWithdraw extends \think\Model {
    public function add($admin) {
        return $this->save($admin);
    }

}
