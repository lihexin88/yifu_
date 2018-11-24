<?php

namespace app\common\model;

use think\Model;
use think\Db;

class Level extends Model
{
    protected $table = 'sn_user_level';

    /**查询等级信息
     * @param $map
     * @param $page
     * @param $size 
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function query_log($map, $page, $size)
    {
        $arr = $this->where($map)->order('id asc')->page($page, $size)->select();
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
    //添加/修改
    public function edit_add($data){
    	$map['name'] = $data['name'];
    	$map['fencheng'] = $data['fencheng'];
    	$map['ticheng'] = $data['ticheng'];
    	$map['buytj'] = $data['buytj'];
    	$map['suanli'] = $data['suanli'];
    	if(empty($data['id'])){
            return $this->insert($map);
        }else{
            return $this->where('id='.$data['id'])->update($map);
            
        }
    }
}
