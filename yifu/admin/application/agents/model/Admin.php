<?php

//管理员管理

namespace app\agents\model;

class Admin extends \think\Model {

    //登录查询
    public function getIn($name) {
        return $this->where('name', $name)->find();
    }

    //后台管理员添加
    public function add($admin) {
        return $this->save($admin);
    }

//修改
    public function updateAdmin($id, $admin) {
        return $this->where('name', $id)->update($admin);
    }
}
