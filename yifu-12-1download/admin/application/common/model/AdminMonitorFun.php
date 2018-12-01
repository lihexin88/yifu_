<?php

namespace app\common\model;

use think\Model;

class AdminMonitorFun extends Model
{
    /**
     * @var string 角色管理模型
     */
    protected $table = 'sn_admin_monitor_function';

    public function query_log($map, $page, $size)
    {
        // 这个表示拿到的数据库里面的内容
        $arr = $this->where($map)->order('id','asc')->page($page, $size)->select()->toArray();
        foreach ($arr as $key => &$value) {
            $value['time_text'] = detail_time($value['time']);
            if($value['pid']==0){
                $value['cate_name']['name']='顶级分类';
            }else{
                $value['cate_name']=$this->where('id',$value['pid'])->find();
            }
        }
        $data['data'] = $arr;
        $data['total'] = $this->where($map)->count();
        $data['num'] = page_num($data['total'], $size);
        return $data;
    }
}