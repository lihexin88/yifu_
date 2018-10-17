/**前端通用处理 **/
var spark = function(){
	var basePath  = '';
	return {
    	init:function(context){
    		basePath = context;
    		$.ajaxSetup({
                beforeSend:function(xhr){
                    spark.blockUI();
                },
                complete:function(xhr,ts){
                    spark.unblockUI();
                }
            });
    	},
		/*
		type：success成功，warning警告，error错误
		message：提示内容
		spark.alert(message,type);
		*/
		alert:function(message,type) {
			if(type==""){
				swal({
					title:message,
					confirmButtonText:"确定" 
				});	
			}else{
				swal({
					title:message,
					type: type,
					confirmButtonText:"确定" 
				 
				});	
			} 
		},
		/**
		type：success成功，warning警告，error错误
		message：提示内容
		fun:回调函数
		*/
		confirm:function(title,message,fun) {
				swal({
						title: message,
						text:message,
						showCancelButton: true,
						confirmButtonText:"确定",
						cancelButtonText: "取消",
						closeOnConfirm: false ,
						showLoaderOnConfirm: true,
						html: true
					}, 
					function(){
						fun(); 
					});
		},
    	blockUI:function(options) {
		    options = $.extend(true, {}, options);
		    var html = '';
		    if (options.message) {
		        html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' +basePath+ 'theme/default/asset/handler/img/loading-spinner-grey.gif" align=""><span>&nbsp;&nbsp;' + (options.message ? options.message : '正在加载...') + '</span></div>';
		    } else if (options.textOnly) {
		        html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><span>&nbsp;&nbsp;' + (options.message ? options.message : '正在加载...') + '</span></div>';
		    } else {
		        html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + basePath + 'theme/default/asset/handler/img/loading-spinner-grey.gif" align=""></div>';
		    }

		    if (options.target) { // element blocking
		        var el = $(options.target);
		        if (el.height() <= ($(window).height())) {
		            options.cenrerY = true;
		        }
		        el.block({
		            message: html,
		            baseZ: options.zIndex ? options.zIndex : 1000,
		            centerY: options.cenrerY !== undefined ? options.cenrerY : false,
		            css: {
		                top: '10%',
		                border: '0',
		                padding: '0',
		                backgroundColor: 'none'
		            },
		            overlayCSS: {
		                backgroundColor: options.overlayColor ? options.overlayColor : '#555',
		                opacity: options.boxed ? 0.05 : 0.1,
		                cursor: 'wait'
		            }
		        });
		    } else { // page blocking
		        $.blockUI({
		            message: html,
		            baseZ: options.zIndex ? options.zIndex : 1000,
		            css: {
		                border: '0',
		                padding: '0',
		                backgroundColor: 'none'
		            },
		            overlayCSS: {
		                backgroundColor: options.overlayColor ? options.overlayColor : '#555',
		                opacity: options.boxed ? 0.05 : 0.1,
		                cursor: 'wait'
		            }
		        });
		    }
		},
		unblockUI:function(target) {
		    if (target) {
		        $(target).unblock({
		            onUnblock: function() {
		                $(target).css('position', '');
		                $(target).css('zoom', '');
		            }
		        });
		    } else {
		        $.unblockUI();
		    }
		},
		validation:function(el) {
            var form = $(el);
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);
            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success.hide();
                    error.show();
                    spark.scrollTo(error, -200);
                },

                highlight: function (element) { // hightlight error inputs
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
					$(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label.closest('.form-group').removeClass('has-error'); // set success class to the control group
                },
                submitHandler: function (form) {
                    success.show();
                    error.hide();
                }
            });
		},
		validationWithIcon:function(el) {
        	// for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form = $(el);
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success.hide();
                    error.show();
                    spark.scrollTo(error, -200);
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var icon = $(element).parent('.input-icon').children('i');
                    icon.removeClass('fa-check').addClass("fa-warning");  
                    icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
                },

                highlight: function (element) { // hightlight error inputs
                    $(element).closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    
                },

                success: function (label, element) {
                    var icon = $(element).parent('.input-icon').children('i');
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                    icon.removeClass("fa-warning").addClass("fa-check");
                },

                submitHandler: function (form) {
                    success.show();
                    error.hide();
                    form[0].submit(); // submit the form
                }
            });
		}
    }
}();