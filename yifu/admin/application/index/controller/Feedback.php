<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/17
 * Time: 14:49
 */

namespace app\index\controller;

use app\common\model\Feedback as FeedbackModel;

class Feedback extends Common
{
    public function index()
    {
        echo "feedback";
    }
    public function admin()
    {
    	$arr = array();
        $page_size = 10;
        $get_all_feedback = new FeedbackModel();

        $get_all_feedback = $get_all_feedback->paginate($page_size);
        $page = $get_all_feedback->render();
        $this->assign('page',$page);
        $this->assign('feedback_log',$get_all_feedback);
        return $this->fetch();
    }
}