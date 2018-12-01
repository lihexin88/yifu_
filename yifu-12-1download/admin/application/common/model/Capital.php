<?php

namespace app\common\model; 
use think\Model;

class Capital extends Model {

	protected $table = 'sn_capital';

    public function query_log($map){
    	return $this->where($map)->find();
    }
}
