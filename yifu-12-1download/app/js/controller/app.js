var EventUtil = {
    addHandler: function (element, type, handler) {
        if (element.addEventListener)
            element.addEventListener(type, handler, false);
        else if (element.attachEvent)
            element.attachEvent("on" + type, handler);
        else
            element["on" + type] = handler;
    },
    removeHandler: function (element, type, handler) {
        if(element.removeEventListener)
            element.removeEventListener(type, handler, false);
        else if(element.detachEvent)
            element.detachEvent("on" + type, handler);
        else
            element["on" + type] = handler;
    },
    /**
     * 监听触摸的方向
     * @param target            要绑定监听的目标元素
     * @param isPreventDefault  是否屏蔽掉触摸滑动的默认行为（例如页面的上下滚动，缩放等）
     * @param upCallback        向上滑动的监听回调（若不关心，可以不传，或传false）
     * @param rightCallback     向右滑动的监听回调（若不关心，可以不传，或传false）
     * @param downCallback      向下滑动的监听回调（若不关心，可以不传，或传false）
     * @param leftCallback      向左滑动的监听回调（若不关心，可以不传，或传false）
     */
    listenTouchDirection: function (target, isPreventDefault, upCallback, rightCallback, downCallback, leftCallback) {
        this.addHandler(target, "touchstart", handleTouchEvent);
        this.addHandler(target, "touchend", handleTouchEvent);
        this.addHandler(target, "touchmove", handleTouchEvent);
        var startX;
        var startY;
        function handleTouchEvent(event) {
            switch (event.type){
                case "touchstart":
                    startX = event.touches[0].pageX;
                    startY = event.touches[0].pageY;
                    break;
                case "touchend":
                    var spanX = event.changedTouches[0].pageX - startX;
                    var spanY = event.changedTouches[0].pageY - startY;

                    if(Math.abs(spanX) > Math.abs(spanY)){      //认定为水平方向滑动
                        if(spanX > 30){         //向右
                            if(rightCallback)
                                rightCallback();
                        } else if(spanX < -30){ //向左
                            if(leftCallback)
                                leftCallback();
                        }
                    } else {                                    //认定为垂直方向滑动
                        if(spanY > 30){         //向下
                            if(downCallback)
                                downCallback();
                        } else if (spanY < -30) {//向上
                            if(upCallback)
                                upCallback();
                        }
                    }

                    break;
                case "touchmove":
                    //阻止默认行为
                    var spanX = event.changedTouches[0].pageX - startX;
                    var spanY = event.changedTouches[0].pageY - startY;

                    if(Math.abs(spanX) > Math.abs(spanY)){      //认定为水平方向滑动
                        if(spanX > 30){         //向右
                            if(rightCallback)
                                rightCallback();
                        } else if(spanX < -30){ //向左
                            if(leftCallback)
                                leftCallback();
                        }
                    } else {                                    //认定为垂直方向滑动
                        if(spanY > 30){         //向下
                            if(downCallback)
                                downCallback();
                        } else if (spanY < -30) {//向上
                            if(upCallback)
                                upCallback();
                        }
                    }
                    break;
            }
        }
    }
};

(function() {
	var html = document.documentElement;
	var width = html.getBoundingClientRect().width;
	html.style.fontSize = width / 18.75 + 'px';
})();
if(1 != 1) {
	var globalUrl = 'http://inter.yifu.com/';
} else {
	var globalUrl = 'http://rry.jinjifuweng.com/';
}
var app = angular.module('myApp.controller', ['ionic']);

app.run(function($ionicPlatform) {
	$ionicPlatform.ready(function() {
		if(window.cordova && window.cordova.plugins.Keyboard) {
			cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
			cordova.plugins.Keyboard.close();
		};
	});
});


//自定义服务(时间插件)
app.service('dateSelect', function() {
	this.init = function(obj) {
		var calendar1 = new lCalendar();
		calendar1.init({
			'trigger': obj.d1,
			'type': 'date'
		});
		var calendar2 = new lCalendar();
		calendar2.init({
			'trigger': obj.d2,
			'type': 'date'
		});
	}
});
//自定义警告框提示
function _toast(msg) {
	//	let str=`<div class="toast_mask"></div>
	//			<div class="toast_con">
	//				<div>
	//					<div>
	//						<span class="iconfont icon-jinggao1"></span>
	//					</div>
	//					<div>${msg}</div>
	//				</div>
	//			</div>`
	$(".toast_msg").html(msg);
	$(".d_toast").show();
	$(".toast_mask").on("click", function() {
		$(".d_toast").hide();
	})
}
/* 封装 http 请求*/
app.factory('$httpService', function($http, $q, $ionicLoading, $state) {
	var factory = {};
	factory.httpFun = function(jsonData) {
		var defer = $q.defer();
		$http({
			method: 'POST',
			url: globalUrl,
			data: jsonData,
		}).then(function successCallback(res) {
			$ionicLoading.hide();
			if(res.data.code == 1) {
				var data = res.data.data;
				defer.resolve(data);
//				if(res.data.msg != "") {
//					layer.msg(res.data.msg, {
//						icon: 1,
//						time: 3000
//					});
//				}
			} else if(res.data.code == 0) {
				if(res.data.msg != "") {
//					layer.msg(res.data.msg, {
//						icon: 15,
//						time: 1500
//					});
					_toast(res.data.msg);
				}
			} else if(res.data.code == -1) {
				if(res.data.msg != "") {
//					layer.msg(res.data.msg, {
//						icon: 2,
//						time: 1500
//					});
					_toast(res.data.msg);
					$state.go('login');
				}
			}
		}, function errorCallback(res) {
//			layer.msg("请求失败", {
//				icon: 2,
//				time: 1500
//			});
			_toast("请求失败");
		});
		return defer.promise;
	};
	return factory;
});
/* 调用方法*/
/*
 *  首先控制器中引入$httpService
	app.controller('serviceCtrl', function($scope, $httpService, $http, $state) {
		var jsonData = {
			nozzle: 'customer_service',
			token: localStorage.getItem('token')
		};
		$httpService.httpFun(jsonData).then(function(data) {
			$scope.data = data;
		});
	});
*/
angular.module('myApp', ['ionic', 'myApp.controller', 'ionic-datepicker', 'ionic-citypicker', 'ngSanitize'])
	.config(['$ionicConfigProvider', function($ionicConfigProvider) {
		$ionicConfigProvider.tabs.position('bottom');
	}]).config(function($httpProvider) { //解决post请求的方法
		$httpProvider.defaults.transformRequest = function(obj) {
			var str = [];
			for(var p in obj) {
				str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			}
			return str.join("&");
		};
		$httpProvider.defaults.headers.post = {
			'Content-Type': 'application/x-www-form-urlencoded' //设置请求头 可以接收除json外的数据
		};
		$httpProvider.defaults.withCredentials = true; //保留cookie信息
	}).config(function($stateProvider, $urlRouterProvider) {
		$stateProvider.state('index', { //登陆方式
				url: '/index',
				cache: false,
				templateUrl: "index.html",
				controller: 'indexCtr'
			}).state('login', { //登陆方式
				url: '/login',
				cache: false,
				templateUrl: "views/index/login.html",
				controller: 'loginCtr'
			}).state('risk_book', { //风险提示书
				url: '/risk_book',
				cache: false,
				templateUrl: "views/index/risk_book.html",
				controller: 'risk_bookCtr'
			}).state('condition_risk_tip', { //风险提示书
				url: '/condition_risk_tip',
				cache: false,
				templateUrl: "views/condition_risk_tip.html",
				controller: 'condition_risk_tipCtr'
			}).state('confirm_balance', { //确认结算单
				url: '/confirm_balance',
				cache: false,
				templateUrl: "views/index/confirm_balance.html",
				controller: 'confirm_balanceCtr'
			}).state('about', { //关于
				url: '/about',
				cache: false,
				templateUrl: "views/about.html",
				controller: 'aboutCtr'
			}).state('forget_pass', { //忘记密码
				url: '/forget_pass',
				cache: false,
				templateUrl: "views/index/forget_pass.html",
				controller: 'forget_passCtr'
			}).state('register', { //游客登陆
				url: '/register',
				cache: false,
				templateUrl: "views/index/register.html",
				controller: 'registerCtr'
			}).state('tab', {
				url: "/tab",
				cache: false,
				abstract: true,
				templateUrl: "views/tabs.html",
				controller: 'tabsCtr'
			}).state('tab.tab1', {
				url: '/tab1',
				cache: false,
				views: {
					'tab-tab1': {
						templateUrl: "views/tab-tab1.html",
						controller: 'tab1Ctr'
					}
				}
			}).state('tab.tab2', {
				url: '/tab2',
				cache: false,
				params: {
					id: ''
				},
				views: {
					'tab-tab2': {
						templateUrl: "views/tab-tab2.html",
						controller: 'tab2Ctr'
					}
				}
			}).state('tab.tab3', {
				url: '/tab3', //新闻
				cache: false,
				views: {
					'tab-tab3': {
						templateUrl: "views/tab-tab3.html",
						controller: 'tab3Ctr'
					}
				}
			}).state('tab.tab4', {
				url: '/tab4', //交易登录
				cache: false,
				views: {
					'tab-tab4': {
						templateUrl: "views/tab-tab4.html",
						controller: 'tab4Ctr'
					}
				}
			}).state('add_contract', { //添加自选合约
				url: '/add_contract',
				cache: false,
				templateUrl: "views/add_contract.html",
				controller: 'add_contractCtr'
			}).state('edit_contract', { //编辑自选合约
				url: '/edit_contract',
				cache: false,
				templateUrl: "views/edit_contract.html",
				controller: 'edit_contractCtr'
			}).state('official_notice', { //官方公告
				url: '/official_notice',
				cache: false,
				templateUrl: "views/home/official_notice.html",
				controller: 'official_noticeCtr'
			}).state('statement', { //声明
				url: '/statement',
				cache: false,
				params: {
					item: ''
				},
				templateUrl: "views/home/statement.html",
				controller: 'statementCtr'
			}).state('self_list', { //自选
				url: '/self_list',
				cache: false,
				templateUrl: "views/home/self_list.html",
				controller: 'self_listCtr'
			}).state('search', { //搜索
				url: '/search',
				cache: false,
				templateUrl: "views/search.html",
				controller: 'searchCtr'
			}).state('set_up', { //指标参数设置列表
				url: '/set_up',
				cache: false,
				templateUrl: "views/home/set_up.html",
				controller: 'set_upCtr'
			}).state('macd', { //技术指标
				url: '/macd',
				cache: false,
				params: {
					item: ''
				},
				templateUrl: "views/target/macd.html",
				controller: 'macdCtr'
			})
			.state('boll', { //技术指标
				url: '/boll',
				cache: false,
				params: {
					item: ''
				},
				templateUrl: "views/target/boll.html",
				controller: 'bollCtr'
			})
			.state('kdj', { //技术指标
				url: '/kdj',
				cache: false,
				params: {
					item: ''
				},
				templateUrl: "views/target/kdj.html",
				controller: 'kdjCtr'
			}).state('cci', { //技术指标
				url: '/cci',
				cache: false,
				params: {
					item: ''
				},
				templateUrl: "views/target/cci.html",
				controller: 'cciCtr'
			}).state('vol', { //技术指标
				url: '/vol',
				cache: false,
				params: {
					item: ''
				},
				templateUrl: "views/target/vol.html",
				controller: 'volCtr'
			}).state('rsi', { //技术指标
				url: '/rsi',
				cache: false,
				params: {
					item: ''
				},
				templateUrl: "views/target/rsi.html",
				controller: 'rsiCtr'
			}).state('market', { //交易 - 分时 - K线
				url: '/market',
				cache: false,
				params: {
					cut: '',
				},
				templateUrl: "views/deal/market.html",
				controller: 'marketCtr'
			}).state('deal', { //交易 - 分时 - K线
				url: '/deal',
				cache: false,
				params: {
					way: ''
				},
				templateUrl: "views/deal/deal.html",
				controller: 'dealCtr'
			}).state('capital', { //资金明细
				url: '/capital',
				cache: false,
				templateUrl: "views/deal/capital.html",
				controller: 'capitalCtr'
			}).state('gold', { //出金入金
				url: '/gold',
				cache: false,
				templateUrl: "views/deal/gold.html",
				controller: 'goldCtr'
			}).state('deal_set', { //交易设置
				url: '/deal_set',
				cache: false,
				templateUrl: "views/deal/deal_set.html",
				controller: 'deal_setCtr'
			}).state('bind_card', { //设定签约银行
				url: '/bind_card',
				cache: false,
				templateUrl: "views/deal/bind_card.html",
				controller: 'bind_cardCtr'
			}).state('tabs2', {
				url: "/tabs2",
				cache: false,
				abstract: true,
				templateUrl: "views/tabs2.html",
//				controller: 'tabs2Ctr'
			}).state('tabs2.taba', {
				url: '/taba',
				cache: false,
				views: {
					'tabs2-taba': {
						templateUrl: "views/tabs2-taba.html",
						controller: 'tabaCtr'
					}
				}
			}).state('tabs2.tabb', {
				url: '/tabb',
				cache: false,
				params: {
					id: ''
				},
				views: {
					'tabs2-tabb': {
						templateUrl: "views/tabs2-tabb.html",
						controller: 'tabbCtr'
					}
				}
			}).state('tabs2.tabc', {
				url: '/tabc', //
				cache: false,
				views: {
					'tabs2-tabc': {
						templateUrl: "views/tabs2-tabc.html",
						controller: 'tabcCtr'
					}
				}
			}).state('tabs2.tabd', {
				url: '/tabd', //
				cache: false,
				views: {
					'tabs2-tabd': {
						templateUrl: "views/tabs2-tabd.html",
						controller: 'tabdCtr'
					}
				}
			}).state('tabs2.tabe', {
				url: '/tabe', //
				cache: false,
				views: {
					'tabs2-tabe': {
						templateUrl: "views/tabs2-tabe.html",
						controller: 'tabeCtr'
					}
				}
			}).state('setting', {
				url: '/setting',
				cache: false,
				templateUrl: "views/setting.html",
				controller: 'settingCtr'
			}).state('variety_set', {
				url: '/variety_set',
				cache: false,
				templateUrl: "views/variety_set.html",
				controller: 'variety_setCtr'
			}).state('cloud_condition', {
				url: '/cloud_condition',
				cache: false,
				templateUrl: "views/cloud_condition.html",
				controller: 'cloud_conditionCtr'
			}).state('add_condition_order', {
				url: '/add_condition_order',
				cache: false,
				templateUrl: "views/add_condition_order.html",
				controller: 'add_condition_orderCtr'
			}).state('cloud_stop_loss', {
				url: '/cloud_stop_loss',
				cache: false,
				templateUrl: "views/cloud_stop_loss.html",
				controller: 'cloud_stop_lossCtr'
			}).state('order_history', {
				url: '/order_history',
				cache: false,
				templateUrl: "views/order_history.html",
				controller: 'order_historyCtr'
			});
		$urlRouterProvider.otherwise('login');
	});
	
//自定义服务(时间插件)
app.service('dateSelect', function() {
	this.init = function(obj) {
		var calendar1 = new lCalendar();
		calendar1.init({
			'trigger': obj.d1,
			'type': 'date'
		});
//		var calendar2 = new lCalendar();
//		calendar2.init({
//			'trigger': obj.d2,
//			'type': 'date'
//		});
	}
});