<?php

namespace app\common\model;

use think\Model;

class Relations extends Model
{
    public function name()
    {
        return $this->belongsTo('AdminMonitorFun', 'fuid', 'id');
    }

    /**
     * @var string 角色管理模型
     */
    protected $table = 'sn_admin_relation';
    public function query_log($map, $page, $size)
    {
        // 这个表示拿到的数据库里面的内容
        $arr = $this->where($map)->order('id','asc')->page($page, $size)->select();
        foreach ($arr as $key => $value) {
            $value['time'] = detail_time($value['time']);
        }
        $data['data'] = $arr;
        $data['total'] = $this->where($map)->count();
        $data['num'] = page_num($data['total'], $size);
        return $data;
    }
    // 添加
    public function add($map){
        $data['fuid'] = $map['fuid'];
        $data['roid'] = $map['roid'];
        $data['time'] = time();
        return $this->insert($data);
    }
    public function query_all($map)
    {
        $this->AdminFunction=new AdminMonitorFun();
        $arr = $this->where($map)->relation(array('name'))->order('id asc')->select()->toArray();
        foreach ($arr as $key =>&$value) {
            $value['rela'] = $this->AdminFunction->where('pid',$value['fuid'])->select()->toArray();
        }
        return $arr;
    }
    public function query($map) {
        $this->AdminFunction = new AdminMonitorFun();
        $arr = $this->where($map)->relation(array('name'))->order('id asc')->select()->toArray();
        foreach ($arr as $key => &$value) {
            $value['rela'] = $this->AdminFunction->where('pid', $value['fuid'])->select()->toArray();
        }
        return $arr;
    }
}