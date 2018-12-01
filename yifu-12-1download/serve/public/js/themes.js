/**
* 移动显示隐藏
*/
$(function(){
	$('.shift').hover(function(){
		$(this).find('.shift-box').show();
	},function(){
		$(this).find('.shift-box').hide();
	});
	$('.tab-items a').click(function(){
		var $tabBox = $(this).parent('.tab-items ').siblings('.tab-box'),
			$items = $('.tab-items a'),
			index = $items.index($(this));
		$items.removeClass('active')
		$(this).addClass('active');
		$tabBox.addClass('hide');
		$tabBox.eq(index).removeClass('hide');
	});

	/**
	* 移动显示隐藏
	*/
	ky.utils.styleHandle.oddEven();
	/**
	* 导航控制
	*/
	var oTimer = "";
	$('.nav-ground-item').mouseenter(function(){
		if(oTimer){
			clearTimeout(oTimer);
			oTimer="";
		}
		$(this).find('.service-tip1').show();
	}).mouseleave(function(event){
		 oTimer = setTimeout(function(){
			$('.nav-ground-item').find('.service-tip1').hide();
			oTimer = "";
		},500);
	});
})

/**
* 验证,失去焦点和得到焦点样式控制
*/
$('.form-group  input[type=text],.form-group  input[type=password]').focus(function(){
	if($(this).parent('div').hasClass('text-style1')){
		$(this).parents('.text-style1').addClass('focus');
	}else{
		$(this).addClass('focus');
	};
}).blur(function(){
	if($(this).parent('div').hasClass('text-style1')){
		$(this).parents('.text-style1').removeClass('focus');
	}else{
		$(this).removeClass('focus');
	};
});
/**
*倒计时
*/
/**
*倒计时
*/
var handle = '';
function doClick(obj,time,style){
	obj.unbind("click").addClass(style);
	var text  = parseInt(time) < 10 ? '0' + time : time;
	obj.text(text+'秒后重发');
	handle = setTimeout( function(){
		if(time === 1){
			obj.removeClass(style).text('获取验证码');
			clearTimeout(handle);
			if(obj.attr('id') == 'testGetCode1'){
				obj.click(countDown1);
			}else{
				obj.click(countDown);
			};
		}else{
			--time;
			doClick(obj,time,style);
		};
	},1000);
};
function stopDownClick(obj,style,eventName){
	clearTimeout(handle);
	obj.unbind("click").removeClass(style).text('获取验证码');
	if(obj.attr('id') == 'testGetCode1'){
		obj.click(countDown1);
	}else{
		obj.click(countDown);
	};
};
/**
*最近7天,30,90
*/
function change_Date(days) {
	// 参数表示在当前日期下要增加的天数
	var now = new Date();
	// + 1 代表日期加，- 1代表日期减
	now.setDate((now.getDate() + 1) - 1 * days);
	var year = now.getFullYear();
	var month = now.getMonth() + 1;
	var day = now.getDate();
	if (month < 10) {
		month = '0' + month;
	}
	if (day < 10) {
		day = '0' + day;
	}
	return year + '-' + month + '-' + day;
};
/**
* odometer 插件处理差值
*/
function totalInvest(obj,time){
	var len = obj.attr('value').split('.')[0].length,
		str = '90000000000000000000000000000',
		str1 = str.substring(0,len - 2),
		time = time?time:0;
		console.log( obj.attr('value').split('.')[0]);
	setTimeout(function(){
		obj.text(str1);
	},time);
	setTimeout(function(){
		obj.html(obj.attr('value'));
	},10+time);
};
window.ky = window.ky || {version:"beta 0.1"};
ky.utils = ky.utils || {};
ky.ui = ky.ui || {};
(function(){
	function styleHandle(){};
	/**
	*点击隐藏
	*/
	styleHandle.rate = function(obj){
		obj.css('display') == 'none' ? obj.show() : obj.hide(); 
	};
	/**
	*点击选中样式控制
	*/
	styleHandle.style = function(obj,allDom){
		allDom.removeClass('active');
		obj.addClass('active');
	};
	/**
	*控制Tab的内容DIV 显示
	*/
	styleHandle.tab = function(obj,allDom,box){
		var that = this,
			index = allDom.index(obj);
		this.style(obj,allDom);
		box.hide();
		box.eq(index).show();
	};
	styleHandle.oddEven = function(){
		$('.table-striped tbody tr:even').addClass('odd');
		$('.table-striped tbody tr:odd').addClass('even');
	};
	
	ky.utils.styleHandle = styleHandle;
})();
(function(){
	function _String(){};
	/**
	* 将数据已.分开
	*/
	_String.splits = function(string){
		
		if(!string) return[0,'00'];
		if(string.indexOf('.') < 0) return [string,'00'];
		return string.split('.');
	};
	/**
	* 去掉小数点
	*/
	_String.ceils = function(string){
	
		return Math.ceil(string);
	};
	/**
	 * 格式化金额
	 * @param string, separator
	 * @return string
	 */
	_String.formateMoney = function(string, separator) {
		if(!separator) separator = ',';
		typeof string === 'number' && (string += '');
        return string.replace(/\b\d+\b/, function(str){
            var len = str.length, miu = Math.floor((len%3 === 0 ? (len - 1) : len)/3);
            if (len < 4) {return str};
            str = str.split('');
            for(var i=1,j=0; i<= miu; i++,j++){
                str.splice(len-i*3-j, 0, separator);
                len++
            }
            return str.join('');
        })
	};
	ky.utils.string = _String;
})();
/**
* 列表加载效果
*/
$.fn.systole = function(opt){
	var that = this;
		that.opt = {
			items: that.find('.list-single'),
			minHeight: 0 
		};
	 that.opt = $.extend(that.opt,opt);
	 return this.each(function(){
		 this.init = function(){
			if(!that.opt.items.length) return;
			var parentH,eleTop = [];
			parentH = that.height();
			that.css({'height':that.opt.minHeight,'overflow':'hidden'});
			setTimeout(function(){
				that.opt.items.each(function(idx){
					eleTop.push($(this).position().top);
					$(this).css({"margin-top":-eleTop[idx]}).children().hide();
				}).animate({"margin-top":0}, 800).children().fadeIn();
				that.animate({"height":parentH - 25}, 1300);
				that.opt.items.css({"-webkit-animation-duration":"2s","-webkit-animation-delay":"0","-webkit-animation-timing-function":"ease","-webkit-animation-fill-mode":"both"});
			}, 300);
		 };
		 this.init();
	 });
};
/**
 * 返回顶部
 */
(function(){
 function goTop(option){ 
 	var m = this;
	 m.option = {
		 time: 500,
		 targetEl: '',
		 style: 'backtop1',
		 scrollTop: 100
	 };
	 $.extend(m.option,option);
 };
 goTop.prototype = {
	 init: function(){
		var m = this, s = m.option;
		s.windowEl = $(window);
		s.targetEl.addClass(s.style);
		m.regEvent();
	 },
	regEvent:function(){
		var m = this, s = m.option;
		s.windowEl.scroll(function(){
			if(s.windowEl.scrollTop() > s.scrollTop){
				s.targetEl.removeClass(s.style);
			}else{
				s.targetEl.addClass(s.style);
			}
		});
		s.targetEl.click(function(){
			$('html,body').animate({
				scrollTop: 0
			},s.time )
		});
	}
 };
 ky.ui.goTop =  goTop;
 
})();
/**
* 连盈计划的投资效果  自动累加效果
*/
(function(){
	function autoAdd(option){
		var m = this;
		m.option = {
			targetEl: '',
			time: 10,
			oldNum: 401591000,
			newNum: 401586000,
			differ: 1e3,
			timeId: ''
		};
		$.extend(m.option,option);
	};
	autoAdd.prototype = {
		init: function(){
			var m = this, s = m.option;
			s.oldNum = s.targetEl.text().split(',').join('');
			s.newNum = s.oldNum - s.differ;
			m.start();
		},
		start: function(){
			var m = this, s = m.option;
			s.newNum > s.oldNum ? clearTimeout(s.timeId): m.add();
		},
		add: function(){
			var m = this, s = m.option;
			s.targetEl.text(ky.utils.string.formateMoney(s.newNum));
			s.timeId = setTimeout(function(){
				s.newNum += 80;
				s.time += 1;
				m.start();
			},s.time)
		}
	};
	ky.ui.autoAdd =  autoAdd;
})(); 
/**
* 文字滚动效果
*/
(function(){
function rollList(option){
		var m = this;
		m.option = {
			targetEl: '',//动画元素
			time: 500,//marginTop变化时间
			exercise: 2e3,//动画滚动时间
			rollH: -20//滚动的高度
		};
		$.extend(m.option,option);
	};
	rollList.prototype = {
		init: function(){
			var m = this, s = m.option,type;
			if(s.targetEl.find('li') < 2) return;
			type = setInterval(function(){
				m.animateFun();
			},s.exercise);
			s.targetEl.hover(function(){
				clearInterval(type);
			},function(){
				type = setInterval(function(){
					m.animateFun();
				},s.exercise);
			});
		},
		animateFun: function(){
			var m = this, s = m.option;
			s.targetEl.animate({marginTop: s.rollH + 'px'},s.time,function(){
            	s.targetEl.css('marginTop',"0px").find('li:lt(2)').remove().appendTo(s.targetEl);
			});
		}
	};
	ky.ui.rollList =  rollList;
})();
/**
* 图片墙幻灯片处理
*/
$.fn.listSlide = function(opt){
	var that = this,
		$that = $(this);
		that.opt = {
			$items: $that.find('.picture-list-box a'),//图片列表中的元素
			$Allitems: $that.find('.picture-list-box a')
		}
		$.extend(that.opt,opt);
	return this.each(function(){
		that.init = function(){
			$that.append(that.html);
			that.$blueimp = $that.find('.blueimp-gallery');
			that.$next = that.$blueimp.find('.next');
			that.$prev = that.$blueimp.find('.prev');
			that.opt.length = that.opt.$items.length;
			
			that.opt.$items.bind('click',function(){
				that.target = $(this);
				that.maxImg();
			});
			
			that.$next.bind('click',function(){
				that.next();
			});
			that.$prev.bind('click',function(){
				that.prev();
			});
			that.$blueimp.bind('click',function(event){
				that.target = $(this);
				that.closeFun(event);
			})
		};
		that.maxImg = function(){
			var index = that.opt.$Allitems.index(that.target);
			var src = that.srcData(that.target);
			that.$blueimp.removeClass('hide').find('img').attr('src',src);
			that.current = index;//当前点击第几张图片
		};
		that.srcData = function($obj){
			var srcArr  = $obj.find('img').attr('src').split('.'),
				src = srcArr[0]+'max.'+srcArr[1];
			return src;
		};
		that.next = function(){
			that.current = (that.current + 1) >= (that.opt.length - 1) ? 0 : (that.current + 1);
			var src = that.srcData(that.opt.$items.eq(that.current));
			that.$blueimp.find('img').attr('src',src);
		};
		that.prev = function(){
			that.current = (that.current - 1) < 0 ? (that.opt.length - 1)  : (that.current - 1);
			var src = that.srcData(that.opt.$items.eq(that.current));
			that.$blueimp.find('img').attr('src',src);
		};
		that.closeFun = function(event){
			if(!that.$next.is(event.target) && !that.target.find('img').is(event.target) && !that.$prev.is(event.target)){
				that.target.addClass('hide');
			};
		};
		that.html = function(){
			if(that.opt.$items.length > 1){
				return '<div class="blueimp-gallery hide" id="blueimp-gallery"><img src="" /><a class="prev" href="javascript:;">‹</a><a class="next" href="javascript:;">›</a><a class="close" href="javascript:;">×</a></div>';
			}else{
				return '<div class="blueimp-gallery hide" id="blueimp-gallery"><img src="" /><a class="close" href="javascript:;">×</a></div>';
			}
		}
		that.init();
	});		
};

// 预约标倒计时
function cycleBorrow() {
	function borrowFn(time){
		var sec = parseInt($(this).find(".b_sec").html());
		var min = parseInt($(this).find(".b_min").html());
		var hour = parseInt($(this).find(".b_hour").html());
		sec --;
		if (sec < 0) {
			sec = 59;
			min--;
		}
		if (min < 0 ) {
			min = 60;
			hour--;
		};
		if(hour<0){//清除定时器
			clearInterval(time);
			return;
		};
		$(this).find(".b_hour").html(hour);
		$(this).find(".b_min").html(min);
		$(this).find(".b_sec").html(sec);
	};
	$('.time2,.btn33').each(function(){
		var that = $(this);
		var time = setInterval(changeFn, 1000);
		function changeFn(){
			borrowFn.call(that,time);
		};
	});
};
if ($(".b_sec").html() != "") {
    cycleBorrow();
}