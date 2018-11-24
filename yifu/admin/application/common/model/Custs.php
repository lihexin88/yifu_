<?php

namespace app\common\model;

use think\Model;

class Custs extends Model
{
    protected $table = 'sn_pc_cust';

    
   public function query_log($map, $page, $size)
    {
        // 这个表示拿到的数据库里面的内容
        $arr = $this->where($map)->order('id','asc')->page($page, $size)->select();
        foreach ($arr as $key => $value) {
            $arr[$key]['time'] = detail_time($value['time']);
        }
        $data['data'] = $arr;
        $data['total'] = $this->where($map)->count();
        $data['num'] = page_num($data['total'], $size);
        return $data;
    }
   
    //添加/修改方法
    public function add_save($data){

        $map['name'] = $data['name'];
        $map['qq'] = $data['qq'];
        $map['time'] = time();
        if(empty($data['id'])){
            return $this->insert($map);
        }else{
            return $this->where('id='.$data['id'])->update($map);
            
        }
            
    }
     //添加/修改方法
    public function doadd($data){
        $map['name'] = $data['name'];
        $map['qq'] = $data['qq'];
        $map['time'] = time();
        return $this->save($map);        
    }
   
   
    
}
