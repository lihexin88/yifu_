<?php

namespace app\common\model;

use think\Db;

class City extends \think\Model {

    public function getListById($parent_id) {
        return $this->where(['parent_id' => $parent_id])->select();
    }

    public function getOne($parent_id) {
        return $this->where(['id' => $parent_id])->find();
    }

    public function getLi($name) {
        return $this->where($name)->find();
    }

    public function getCity($id) {
        return $this->where(['parent_id' => $id])->select();
    }

    public function getCountry($id) {
        return $this->whereIn('parent_id',$id)->select();
    }

}
