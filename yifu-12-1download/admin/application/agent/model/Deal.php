<?php

namespace app\agent\model;
use think\Model;
use think\Db;

class Deal extends model
{

    protected $table = 'sn_deal';

    public function user()
    {
        return $this->belongsTo('User', 'uid', 'id');
    }

    public function query_log($map, $page, $size)
    {
        $list = $this->where($map)->relation(array('user'))->order('time desc')->page($page, $size)->select()->toArray();
        foreach ($list as $key => &$value) {
            $value['time'] = detail_time($value['time']);
            switch ($value['status']) {
                case '0':
                    $value['status'] = "未交易";
                    break;
                case '1':
                    $value['status'] = "取消";
                    break;
                case '1':
                    $value['status'] = "完成";
                    break;
            }

            switch ($value['type']) {
                case '0':
                    $value['type'] = "限价";
                    break;
                case '1':
                    $value['type'] = "最新价";
                    break;
                case '2':
                    $value['type'] = "对手价";
                    break;
                case '3':
                    $value['type'] = "挂单价";
                    break;
                case '4':
                    $value['type'] = "快速价";
                    break;
            }
            switch ($value['mold']) {
                case '0':
                    $value['mold'] = "开仓";
                    break;
                case '1':
                    $value['mold'] = "平仓";
                    break;
            }
            switch ($value['direction']) {
                case '0':
                    $value['direction'] = "买";
                    break;
                case '1':
                    $value['direction'] = "卖";
                    break;
            }
            switch ($value['pattern']) {
                case '0':
                    $value['pattern'] = "投机";
                    break;
                case '1':
                    $value['pattern'] = "套利";
                    break;
                case '2':
                    $value['pattern'] = "套保";
                    break;
            }
        }
        $list['data'] = $list;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
}
