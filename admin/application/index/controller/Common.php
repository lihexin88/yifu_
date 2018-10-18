<?php

namespace app\index\controller;
use app\common\model\User;
use think\Controller;
use think\Request;
use app\common\model\AdminOption;

class Common extends Controller
{
    protected $User;
    protected $num;
    protected $show;
    protected $Agent;
    public function __construct(Request $request = null)
    {
        header("Content-type:text/html;charset=utf-8");
        parent::__construct($request);
        $this->User = new User();
        $this->num = 15;
        $this->show = 5;
        $this->validateSession();
        $table = 'am-table am-table-bordered  am-table-compact am-table-hover am-text-nowrap am-table-centered';
        $this->assign('table', $table);
        $this->assign('url', $request->baseUrl());
        //侧导航跟随
        $a=Request::instance()->param('a');
        $b=Request::instance()->param('b');
        $this->assign('a', $a);
        $this->assign('b', $b);
        $left=session('left');
        $user=session('admin');
        /*if(empty($left)){
            $map['roid']=$user['ro_id'];
            $left=$this->Relation->query_all($map);
            session('left',$left);
        }*/
        $this->assign('left', $left);
    }
    /**
     *
     */
    private function validateSession()
    {
        $admin_id = session('admin_id');
        if (empty($admin_id)) {
            $this->redirect('Index/index');
        }
    }
    public function query_id()
    {
        return session('admin_id');
    }
    /**
     * 空数组下的 HTML 处理
     * @param $num
     * @return string
     */

    public function null_html($num)
    {
        return '<td colspan=' . $num . '>暂时没有数据</td>';
    }
    /**
     * 查询时间处理
     * @param $map array 其他查询条件
     * @param $start  string 开始时间
     * @param $end string 结束时间
     * @return mixed
     */
    protected function query_time($map, $start, $end)
    {
        if ($start || $end) {
            $map['time'] = dateQuery(strtotime($start), strtotime($end));
        }
        return $map;
    }

    /**
     * 查询时间处理
     * @param $map array 其他查询条件
     * @param $start  string 开始时间
     * @param $end string 结束时间
     * @return mixed
     */
    protected function query_addtime($map, $start, $end)
    {
        if ($start || $end) {
            $map['add_time'] = dateQuery(strtotime($start), strtotime($end));
        }
        return $map;
    }

    /**
     * 查询信息处理
     * @param $map array 其他查询条件
     * @param $info string 用户信息
     * @return mixed
     */
    protected function query_info($map, $info)
    {
        if (isset($info) && trim($info)) {
            $array['name|real_name|phone'] = trim($info);
            $uid = $this->User->where($array)->value('id');
            if ($uid) {
                $map['uid'] = $uid;
            } else {
                $map['uid'] = 0;
            }
        }
        return $map;
    }

    public function number_two($num)
    {
        return sprintf("%.2f", substr(sprintf("%.3f", $num), 0, -2));
    }

    protected function arr_info($data)
    {
        $arr['grade'] = isset($data['grade']) ? $data['grade'] : '';
        $arr['agent'] = isset($data['agent']) ? $data['agent'] : '';
        $arr['order'] = isset($data['order']) ? $data['order'] : '';
        $arr['status'] = isset($data['status']) ? $data['status'] : '';
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $arr['start_query'] = isset($data['start_query']) ? $data['start_query'] : '';
        $arr['end_query'] = isset($data['end_query']) ? $data['end_query'] : '';
        return $arr;
    }
    //管理员操作记录
    public function admin_log($data){
        $this->Admin= New AdminOption();
        $data=$this->Admin->insert($data);
        return $data;
    }
}