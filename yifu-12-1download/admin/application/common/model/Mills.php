<?php

namespace app\common\model;

use think\Model;

class Mills extends Model
{
    protected $table = 'ccs_mills';

    /**
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
        $arr = $this->where($map)->order('id asc')->page($page, $size)->select()->toArray();
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

    public function modify_log($data)
    {
        $map['id'] = $data['id'];
        $map['pic'] = $data['pic'];
        $map['name'] = $data['name'];
        $map['price'] = $data['price'];
        $map['times'] = $data['times'];
        $map['timess'] = $data['timess'];
        $map['yield'] = $data['yield'];
        $map['count'] = $data['count'];
        if ($data['id']) {
            return $this->update($map);
        } else {
            return $this->insert($map);
        }
    }
}
