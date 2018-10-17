<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"C:\wamp64\www\yifu\Interface\public/../application/index\view\feedback\feedback.html";i:1539391746;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8">
    <title>逸富-银期转账</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/modules.css">
    <link rel="stylesheet" href="../css/question.css">
</head>
<body>

<main class="main">
    <nav class="navTab">
        <button type="button" id="question">提交问题</button>
        <button type="button" class="pitchTab" id="feedback">反馈记录</button>
    </nav>
    <div class="feedback_box">
        <div class="pay_head">反馈记录</div>
        <div class="feedback">
            <form>
                <table class="table table-bordered tablerecord table-hover">
                    <thead>
                    <tr>
                        <th>问题编号</th>
                        <th>问题类别</th>
                        <th>问题描述</th>
                        <th>提交时间</th>
                        <th>处理状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>160</td>
                        <td>交易</td>
                        <td>222</td>
                        <td>2018-09-22 12:05</td>
                        <td>待处理</td>
                        <td onclick="ondetail(1)">查看</td>
                    </tr>
                    <?php if(is_array($feedback_log) || $feedback_log instanceof \think\Collection || $feedback_log instanceof \think\Paginator): $key = 0; $__LIST__ = $feedback_log;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$feedback_log): $mod = ($key % 2 );++$key;if($key%2 == '0'): ?>
						<tr style="color: gray;">
					<?php else: ?>
						<tr>
					<?php endif; ?>
							<td><?php echo $feedback_log['question_id']; ?></td>
							<td><?php if(($feedback_log['question_type']) == 1): ?>交易<?php elseif(($feedback_log['question_type']) == 2): ?>出入金<?php else: ?>其他<?php endif; ?></td>
							<td title="<?php echo $feedback_log['question_describe']; ?>"><?php if((strlen($feedback_log['question_describe']) < 24 )): ?> <?php echo $feedback_log['question_describe']; else: ?> <?php echo substr($feedback_log['question_describe'],0,24); ?>... <?php endif; ?></td>
							<td><?php echo date('Y-m-d H:i:s',$feedback_log['question_create_time']); ?></td>
							<td><?php if(($feedback_log['question_headle']) == 1): ?>已处理<?php else: ?>待处理<?php endif; ?></td>
							<td onclick="ondetail(this.id)" id = "<?php echo $feedback_log['question_id']; ?>">查看</td>
						</tr>
					<?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                    
                </table>
                <?php echo $page; ?>
            </form>
            <!--没有记录时显示-->
            <div class="noRecord"><?php if((empty($noRecord))): ?>最近没有反馈记录<?php endif; ?></div>
            <!--没有记录时显示 end-->
        </div>
    </div>
</main>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    反馈记录详情
                </h4>
            </div>
            <div class="modal-body">
                <ul>
                    <li>
                        <span>问题编号：</span>
                        <span id = "question_display_id">160</span>
                    </li>
                    <li>
                        <span>问题类别：</span>
                        <span id="question_display_type">交易</span>
                    </li>
                    <li>
                        <span>提交时间：</span>
                        <span id="question_display_create_time">2018-09-22 12:05</span>
                    </li>
                    <li>
                        <span>解决时间：</span>
                        <span id="question_display_done_time">-</span>
                    </li>
                    <li>
                        <span>处理状态：</span>
                        <span id="question_display_headle">待处理</span>
                    </li>
                </ul>
                <div class="detailBox">
                    <p>问题描述：</p>
                    <textarea id="question_display_describe" disabled="disabled"></textarea>
                </div>
                <div class="detailBox">
                    <p>处理意见：</p>
                    <textarea id="question_display_headle_suggestion" disabled="disabled"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btnqure" data-dismiss="modal">
                    取消
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../layui-v2.3.0/layui/layui.js"></script>
<script src="../js/question.js"></script>

</body>
</html>