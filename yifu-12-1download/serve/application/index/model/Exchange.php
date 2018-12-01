<?php



namespace app\index\model;



use think\Model;



class Exchange extends Model

{

    protected $table = 'sn_exchange';



    public function query_log()

    {

        $list = $this->where(array('status' => 1))->order('sort desc')->select();

        $data = array();

        foreach ($list as $key => $value) {

            $data[$key]['id'] = $value['id'];

            $data[$key]['name'] = $value['short'];

            $data[$key]['code'] = $value['code'];

        }

        return $data;

    }

}

