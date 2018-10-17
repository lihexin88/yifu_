// 实例化layui
layui.use(['layer', 'form'], function(){
    var layer = layui.layer,form = layui.form;
});
//切换选项卡
$('.navTab button').hover(function () {
    $(this).toggleClass('pitchTab');
});
$('#question').click(function () {
    window.location.href = 'question.html';
});
$('#feedback').click(function () {
    window.location.href = 'feedback.html';
});
//重置
$('#resetBtn').click(function () {
    $('#questionType').val('');
    $('#content').val('');
    $('#yourNum').val('');
});
//提交问题
$('#submitBtn').click(function () {
    var question = $('#questionType').val();
    var content = $('#content').val();
    var yourNum = $('#yourNum').val();
    var questionData = {"post_question_type":"input_question","question_type":question,"content":content,"yourNum":yourNum};
    if(content === '') {
        layer.msg('请填写问题描述!');
    }else {
        if((yourNum === '')||(yourNum.length!=11)) {
            layer.msg('请填写正确的手机号!');
        }else {
			$.ajax({
			type:"post",
			data:questionData,
			url:"post_question",
			success:function(result){
				if(result['code'] == 1){					
					alert("问题已经提交，我们会尽快与您联系");
		          	window.location.href = 'feedback.html';
				}
			},
			error:function(result){
//				console.log(result);
			},
			async:true
		});
        }
    }
});
//查看
function ondetail(question_id) {
	if(!question_id){
		alert(参数不正确);
		return false;
	}
	var questionData = {"post_question_type":"get_one_question","question_id":question_id};
	$.ajax({
		type:"post",
		url:"post_question",
		data:questionData,
		success:function(result){
			if(result['code'] !=1 ){
				alert(result['msg']);
				return false;
			}
			//问题编号
			$("#question_display_id").html(result['data']['question_id']);
			//问题类别
			if(result['data']['question_type'] == 1){				
				$("#question_display_type").html("交易");
			}else if(result['data']['question_type'] == 2){
				$("#question_display_type").html("出入金");
			}else{
				$("#question_display_type").html("其他");
			}
			//提交时间
			$("#question_display_create_time").html(result['data']['question_create_time']);
			//处理状态
			if(result['data']['question_headle'] == 1){				
				$("#question_display_headle").html("已处理");
			}else{
				$("#question_display_headle").html("未处理");
			}
			//问题描述
			$("#question_display_describe").html(result['data']['question_describe']);
			//处理意见
			$("#question_display_headle_suggestion").html(result['data']['question_headle_suggestion']);
			$('#myModal').modal('show');
		},
		error:function(){
			alert("获取失败，请稍后重试");
			return false;
		},
		async:true
	});
}