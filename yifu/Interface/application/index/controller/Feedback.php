<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/12
 * Time: 9:01
 */

namespace app\index\controller;


use think\Controller;
use app\index\model\Feedback as FeedbackModel;
use think\Db;

class Feedback extends IndexController
{
    public function index($data = null)
    {
        return $this->fetch("question");
    }

    /** 访问模板
     * @return mixed
     */
    public function question()
    {
        return $this->fetch();
    }
    /**
     * 提交反馈
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function feedback($user_id = "")
    {
//        判断用户Id是否存在，根据用户Id进行筛选
        $where = null;
        if(empty($user_id)){
            $where['uid'] = null;
        }
        $all_feedback_log = new FeedbackModel;
        if(empty($all_feedback_log['id'])){
            $r = msg_handle("最近没有反馈记录",0);
            $this->assign("noRecord",$r);
        }

        //每页显示10条数据
        $pagesize = 10;
        $order_by_time['time'] = "desc";
        $feedback = $all_feedback_log->where($where)->order($order_by_time)->paginate($pagesize);
        $page = $feedback->render();
        $this->assign("feedback_log",$feedback);
        $this->assign("page",$page);
        $htmls = $this->fetch();
        return $htmls;
    }

    /**
     * 获得前台ajax传递过来的数据
     * @return \think\response\Json
     */
    public function post_question($feedback_user_id = "")
    {
       $data = $_POST;
        if(!empty($feedback_user_id)){
            $data['user_id'] = $feedback_user_id;
        }else{
            $data['user_id'] = null;
        }
        if(empty($data)){
           $r = msg_handle("失败",-1,"未接收到数据");
        }
        if($data['post_question_type'] == "input_question"){
            $r = $this->input_question($data);
        }else if($data['post_question_type'] == "get_one_question"){
            $r = $this->get_one_question($data);
        }
       return json($r);
    }
    /**
     * @param $data
     * @return array
     * 插入反馈数据
     */
    private function input_question($data)
    {
        $question = new FeedbackModel();
        $question->uid = $data['user_id'];
        $question->type = $data['question_type'];
        $question->describe = $data['content'];
        $question->time = time();
        $question->phone = $data['yourNum'];
        if($question->save()){
           $r = msg_handle('成功',1);
        }else{
            $r = msg_handle('失败',-1);
        }
        return $r;
    }
    /**
     * 展示一条反馈数据
     * @param $data
     * @return array
     * @throws \think\exception\DbException
     */
    private function get_one_question($data)
    {
        $get_one_question = FeedbackModel::get($data['question_id']);
        if($get_one_question){
            $get_one_question->question_create_time = date('Y-m-d H:i:s',$get_one_question->question_create_time);
            $r = msg_handle("成功",1,$get_one_question);
        }else{
            $r = msg_handle("数据库中没有该数据",-1);
        }
        return $r;
    }
}