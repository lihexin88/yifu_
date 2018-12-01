<?php

namespace app\common\model;

use think\Model;

class Info extends Model
{
    protected $table = 'sn_notice';

    /**
     * 查询记录
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function query_log()
    {
        $list = $this->select();
        foreach($list as $k=>&$v){
        	if($v['time']){
        		$v['time']=date('Y-m-d H:i:s',$v['time']);
        	}
        }
        
        return $list;
    }

    public function modify_log($data)
    {
        $map['id'] = $data['id'];
        $map['name'] = $data['name'];
        $map['content'] = $data['content'];
        $map['time'] = time();
        return $this->update($map);
    }
}