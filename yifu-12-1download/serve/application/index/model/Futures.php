<?php

namespace app\index\model;

use think\Model;

class Futures extends Model
{
    protected $table = 'sn_futures';

    /**
     * @param $bourse
     * @return array
     */
    public function query_log($bourse)
    {
        $list = $this->where(array('status' => 1, 'bourse' => $bourse))->select();
        $data = array();
        foreach ($list as $key => $value) {
            $data[$key]['id'] = $value['id'];
            $data[$key]['code'] = $value['code'];
            $data[$key]['name'] = $value['name'];
            $data[$key]['short'] = $value['short'];
            $data[$key]['bourse'] = $value['bourse'];
            $data[$key]['bourse_name'] = $value['bourse_name'];
            $data[$key]['bourse_code'] = $value['bourse_code'];
            $data[$key]['industry'] = $value['industry'];
        }
        return $data;
    }
}
