<?php

namespace app\common\model;

use think\Db;

class AgentFee extends \think\Model {

    protected $table = 'sn_agent_fee';

    /** 佣金明细
     * @param $map
     * @param $page
     * @param $size
     * @return mixed
     */
    public function total($map, $page, $size) {
        $arr = $this->where($map)->order('id asc')->page($page, $size)->select()->toArray();
        foreach ($arr as $key => $value) {
            if ($value['agent'] == 0) {
                $a = $this->alias('f')->where($map)->join('sn_capital g', 'g.id=f.capital')->join('sn_agent_user a', 'a.id=f.staff')
                                ->field('f.id,a.num_name as number,f.type,a.cont_name as real_name,a.cont_phone as phone,g.code,g.name,g.buy_price,g.num,g.time,f.number as money,f.surplus,f.remark')->order('f.id desc')->page($page, $size)->select()->toArray();
            } else {
                $b = $this->alias('f')->where($map)->join('sn_capital g', 'g.id=f.capital')->join('sn_agent a', 'a.id=f.agent')
                                ->field('f.id,a.number,f.type,a.real_name,a.phone,g.code,g.name,g.buy_price,g.num,g.time,f.number as money,f.surplus,f.remark')->order('f.id desc')->page($page, $size)->select()->toArray();
            }
        }
        if (empty($a) && empty($b)) {
            $p = '';
        } else if (empty($b)) {
            $p = $a;
        } else if (empty($a)) {
            $p = $b;
        } else {
            $p = array_merge($a, $b);
        }
        $list['data'] = $p;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }
        /** 佣金统计
     * @param $map
     * @param $page
     * @param $size
     * @return mixed
     */
        public function query_log($map, $page, $size) {
        $agent = Db::table('sn_agent')->alias('a')->join('sn_agent_account g', 'a.id=g.uid')->field('a.id,a.number,a.real_name,a.phone,a.grade,a.agent,g.wit_total,g.account,g.total,a.bond')->where($map)->order('id asc')->page($page, $size)->select();
        $staff = Db::table('sn_agent_user')->alias('a')->join('sn_staff_account g', 'a.id=g.uid')->field('a.id,a.num_name as number,a.cont_name as real_name,a.cont_phone as phone,a.grade,a.aid as agent,g.wit_total,g.account,g.total,a.bond')->where($map)->order('id asc')->page($page, $size)->select();
         if (empty($agent) && empty($staff)) {
            $p = '';
        } else if (empty($agent)) {
            $p = $staff;
        } else if (empty($staff)) {
            $p = $agent;
        } else {
            $p = array_merge($agent, $staff);
        }
        $list['data'] = $p;
        $list['total'] = $this->where($map)->count();
        $list['num'] = page_num($list['total'], $size);
        return $list;
    }

}
