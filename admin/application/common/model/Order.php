<?php

namespace app\common\model;

use think\Model;

class Order extends Model
{
    protected $table = 'sn_order';

   
   
    public function query_log($map, $page, $size)
    {
        // 这个表示拿到的数据库里面的内容
        $arr = $this->where($map)->order('id','asc')->page($page, $size)->select();
        foreach ($arr as $key => &$value) {
            if (empty($value['time'])) {
                $value['time']  = '/';
            }else{
                $value['time'] = detail_time($value['time']);
            }

            if (empty($value['sell_time'])) {
                $value['sell_time']  = '/';
            }else{
                $value['sell_time'] = detail_time($value['sell_time']);
            }

            if (empty($value['buy_time'])) {
                $value['buy_time']  = '/';
            }else{
                $value['buy_time'] = detail_time($value['buy_time']);
            }
            //$value['pic'] =config('HOSTADMIN').$value['pic'] ;
            switch ($value['status']) {
                case '0':
                    $value['status'] = '未成交';
                    break;
                case '1':
                    $value['status'] = '已撤单';
                    break;
                case '2':
                    $value['status'] = '已完成';
                    break;
            }
        }
        $data['data'] = $arr;
        $data['total'] = $this->where($map)->count();
        $data['num'] = page_num($data['total'], $size);
        return $data;
    }
    public function getList(){
        $map['status']=1;
        $arr = $this->where($map)->order('id','asc')->select();
        return $arr;
    }
   
}