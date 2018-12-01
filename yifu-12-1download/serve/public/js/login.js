function logout(){
	layer.msg("正在退出。。。")
	$.ajax({
		type:"post",
		url:"/index.php/login/logout",
		success:function(result){
			if(result['code'] == 1){
				layer.msg("已退出",{time:2000});
				window.location.reload();
			}
		},
		error:function(result){
			alert("退出失败！请稍后重试！");
		},
		async:true
	});
}
