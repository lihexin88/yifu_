<?php

namespace app\common\model;

use think\Model;

class Play extends Model
{
    protected $table = 'sn_pc_play';

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
        $arr = $this->where($map)->order('sort asc')->page($page, $size)->select();
        foreach ($arr as $key => &$value) {
            $arr[$key]['time_text'] = detail_time($value['time']);
            switch ($value['type']) {
                case 1:
                    $value['type'] = '首页轮播';
                    break;
                case 2:
                    $value['type'] = '合作伙伴';
                    break;
               
            }
            switch ($value['status']) {
                case 1:
                    $value['status'] = '正常';
                    break;
                case 2:
                    $value['status'] = '禁用';
                    break;
               
            }
        }
        $list['data'] = $arr;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

    public function modify_log($data)
    {
        $map['id'] = $data['id'];
        $map['pic'] = $data['pic'];
        $map['url'] = $data['url'];
        $map['status'] = $data['status'];
        $map['time'] = time();
        $map['sort'] = $data['sort'];
        if ($data['id']) {
            return $this->update($map);
        } else {
            return $this->insert($map);

        }
    }
}
