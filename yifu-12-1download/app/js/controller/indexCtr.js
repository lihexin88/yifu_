//自定义警告框提示
function _toast(msg) {
	$(".toast_msg").html(msg);
	$(".d_toast").show();
	$(".toast_mask").on("click", function() {
		$(".d_toast").hide();
	})
}
//自定义底部提示
function _tip(msg) {
	$(".d_tip").html(msg).fadeIn();
	setTimeout(function() {
		$(".d_tip").fadeOut();
	}, 1000)
}

app.controller('indexCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {

}]);

app.controller('tabsCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	//查询有没有登录交易账号
	$scope.go_deal = function() {
		var jsonData = {
			nozzle: 'pay_login_sure',
			token: localStorage.getItem("token")
		};
		$httpService.httpFun(jsonData).then(function(data) {
			$scope.deal_login_statues = data.statues;
			if($scope.deal_login_statues == true) {
				$state.go("deal");
			} else {
				$state.go("tabs2.tabe");
			}
		});
	};
	//交易
	$scope.go_tab = function(i) {
		if(sessionStorage.getItem("token")) {
			if(i == 2) {
				$state.go("tab.tab2");
			} else {
				$state.go("tabs2.tabe");
			}
		} else {
			$state.go("login");
		}
	}
}]);
//登录
app.controller('loginCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.back = function() {
		javascript: history.back(-1);
	};
	$scope.inputData = {
		com: localStorage.getItem("com"),
		acc: localStorage.getItem("acc"),
		pwd: ''
	};
	$scope.d_id = 0;
	//汇率
	//	$httpService.httpFun({
	//		nozzle: "exchange_rate",
	//	}).then(function(data) {
	//		sessionStorage.setItem("ratio", data)
	//	});
	//开户公司
	if(!localStorage.getItem("com")) {
		$httpService.httpFun({
			nozzle: "company"
		}).then(function(data) {
			$scope.inputData.com = data[0].data[0].name;
		});
	}
	$scope.focus = function(i) {
		$scope.d_id = i;
		if(i == 1) {
			$state.go("search");
		}
	};
	$scope.clear = function(i) {
		if(i) {
			$scope.inputData.pwd = "";
		} else {
			$scope.inputData.acc = "";
		}
	}
	$scope.sub = function() {
		if($scope.inputData.acc && $scope.inputData.pwd) {
			$ionicLoading.show({
				template: '<div class="lds-ring"><div></div><div></div><div></div><div></div></div><div class="load_msg">连接交易服务器...</div>'
			});
			var jsonData = {
				nozzle: 'login',
				phone: $scope.inputData.acc,
				password: $scope.inputData.pwd,
				type: 0
			};
			$httpService.httpFun(jsonData).then(function(data) {
				sessionStorage.setItem('token', data.token);
				localStorage.setItem("com", $scope.inputData.com);
				localStorage.setItem("acc", $scope.inputData.acc);
				$state.go('risk_book');
				//用户信息
				$httpService.httpFun({
					nozzle: "account",
					token: data.token
				}).then(function(data) {
					sessionStorage.setItem('accinfo', JSON.stringify(data));
				});
			});
		} else {
			_toast("请输入完整信息");
		}
	}

}]);
//风险提示书
app.controller('risk_bookCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.con = "";
	$scope.back = function() {
		javascript: history.back(-1);
	};
	//获取风险提示书内容
	$httpService.httpFun({
		nozzle: "protocol",
		type: 2
	}).then(function(res) {
		$scope.con = res;
	});
	$scope.cancel = function() {
		sessionStorage.removeItem("token");
		$scope.back();
	}
	$scope.confirm = function() {
		$state.go('tab.tab1');
	}
}]);
//条件单风险提示
app.controller('condition_risk_tipCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.con = "";
	$scope.back = function() {
		javascript: history.back(-1);
	};
}]);
//确认结算单
app.controller('confirm_balanceCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.con = "";
	$scope.back = function() {
		javascript: history.back(-1);
	};
}]);
//关于
app.controller('aboutCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.info;
	$scope.back = function() {
		javascript: history.back(-1);
	};
	$httpService.httpFun({
		nozzle: "about",
	}).then(function(data) {
		$scope.info = data;
	});
}]);
//忘记密码
app.controller('forget_passCtr', ['$interval', '$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($interval, $ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.inputData = {
		old_pwd: "",
		new_pwd: "",
		new_pwd1: ""
	};
	$scope.back = function() {
		javascript: history.back(-1);
	};
	$scope.sub = function() {
		if($scope.inputData.old_pwd && $scope.inputData.new_pwd && $scope.inputData.new_pwd1) {
			if($scope.inputData.new_pwd == $scope.inputData.new_pwd1) {
				$httpService.httpFun({
					nozzle: "edit_pass",
					token: sessionStorage.getItem("token"),
					old_pass: $scope.inputData.old_pwd,
					new_pass: $scope.inputData.new_pwd,
					con_pass: $scope.inputData.new_pwd1
				}).then(function(data) {
					_tip("修改成功");
					$scope.inputData.old_pwd = "";
					$scope.inputData.new_pwd = "";
					$scope.inputData.new_pwd1 = "";
				});
			} else {
				_toast("两次新密码输入不一致");
			}
		} else {
			_toast("请输入完整信息");
		}
	}
}]);

//首页  tab1
app.controller('tab1Ctr', ['$interval', '$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($interval, $ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	//切换页面清除计时器:
	$scope.$on('$destroy', function() {
		$scope.interval_cancel();
	});
	$scope.title = "-"
	$scope.bool = false;
	$scope.editIf = false;

	$scope.id = "";
	$scope.id1 = "";
	$scope.t_id = "";
	//合約列表
	$scope.contracts = [];
	//合约类型
	$scope.contracts_type = [];
	//我的合约列表
	$scope.my_contracts = [];

	//主菜单
	$scope.showMenu = function() {
		$(".right_modal_mask").fadeIn();
		$(".right_modal").show();
		$(".right_modal").animate({
			right: 0
		}, 300)
	}
	//我的自选
	$httpService.httpFun({
		nozzle: "concern",
		token: sessionStorage.getItem("token")
	}).then(function(data) {
		console.log("我的自选：", data);
		$scope.my_contracts = data;
		//合约类型
		$httpService.httpFun({
			nozzle: "futures_type",
		}).then(function(data) {
			$scope.contracts_type = data;
			if($scope.my_contracts) {
				$scope.myContract(0);
			} else {
				$scope.title = "国际•" + data[0][0];
				$scope.queryContract(0, data[0][0]);
				$scope.id = data[0][0];
			}
		});

	});
	$scope.interval_cancel = function() {
		if($scope.timer) {
			$interval.cancel($scope.timer);
		}
	};
	//查询合约
	$scope.queryContract = function(i, n) {
		$scope.interval_cancel();
		//合约列表
		$httpService.httpFun({
			nozzle: "query_contract",
			type: i,
			name: n
		}).then(function(arr) {
			$scope.arr = arr;
			sessionStorage.setItem("activearr", JSON.stringify($scope.arr));
			sessionStorage.setItem("activename", arr[0].name);
			sessionStorage.setItem("activecode", arr[0].code);
			sessionStorage.setItem("activeshort", arr[0].short);
			sessionStorage.setItem("activebourse", arr[0].bourse_name);
			sessionStorage.setItem("activeindex",0);
			var params = [],
				l = arr.length;
			for(var v of arr) {
				params.push(v.code)
			}
			$scope.params = params;
			console.log(arr)
			$scope.timer = $interval(function() {
				sendAjax();
			}, 3000);

			function sendAjax() {
				var back_arr = [];
				$.ajax({
					cache: true,
					url: `https://hq.sinajs.cn/?list=${$scope.params}`,
					type: 'GET',
					dataType: 'script',
					async: false,
					success: function() {
						for(var v of arr) {
							var x = "hq_str_" + v.code;
							var arrX = eval(x).split(",");
							var up_val, up_down;
							if(arrX.length != 1) {
								if(v.bourse_name == "中金所") {
									if(Number(arrX[13]) != 0) {
										up_val = ((Number(arrX[3]) - Number(arrX[13])) / Number(arrX[13]) * 100).toFixed(2);
									} else {
										up_val = "0.00";
									}
									up_down = (Number(arrX[3]) - Number(arrX[13])).toFixed(2);
									if(up_down > 0) {
										arrX.push([v.name, arrX[3], up_down, up_val, "--", "--", "--", "--", arrX[6], arrX[4], "f_c_red"]);
									} else if(up_down < 0) {
										arrX.push([v.name, arrX[3], up_down, up_val, "--", "--", "--", "--", arrX[6], arrX[4], "f_c_green"]);
									} else {
										arrX.push([v.name, arrX[3], up_down, up_val, "--", "--", "--", "--", arrX[6], arrX[4], "f_c_white"]);
									}
								} else {
									if(Number(arrX[9]) != 0) {
										up_val = ((Number(arrX[8]) - Number(arrX[9])) / Number(arrX[9]) * 100).toFixed(2);
									} else {
										up_val = "0.00"
									}
									up_down = (Number(arrX[8]) - Number(arrX[9])).toFixed(2);
									if(up_down > 0) {
										arrX.push([v.name, arrX[8], up_down, up_val, arrX[6], arrX[11], arrX[7], arrX[12], arrX[13], arrX[14], "f_c_red"]);
									} else if(up_down < 0) {
										arrX.push([v.name, arrX[8], up_down, up_val, arrX[6], arrX[11], arrX[7], arrX[12], arrX[13], arrX[14], "f_c_green"]);
									} else {
										arrX.push([v.name, arrX[8], up_down, up_val, arrX[6], arrX[11], arrX[7], arrX[12], arrX[13], arrX[14], "f_c_white"]);
									}
								}
							} else {
								arrX.push([v.name, "--", "--", "--", "--", "--", "--", "--", "--", "--", "f_c_white"]);
							}
							back_arr.push(arrX);
						}
						$scope.contracts = back_arr;
						console.log("合约列表：", $scope.contracts);
						sessionStorage.setItem("activecontract", JSON.stringify($scope.contracts[0]));
						$scope.$apply();
					}
				})
			}
			sendAjax();
		});

	}

	//合约选择
	$scope.showChoice = function() {
		//$(".choice_modal").slideToggle("fast");
		$(".choice_modal").stop(false, true);
		if(!$scope.bool) {
			$(".choice_modal").show();
			$(".choice_modal").animate({
				marginTop: 0,
				opacity: 1,
			}, 300)
		} else {
			$(".choice_modal").animate({
				marginTop: '-50%',
				opacity: 0,
			}, 300, function() {
				$(".choice_modal").hide();
			})
		}
		$scope.bool = !$scope.bool;
	};
	//选择合约
	$scope.select = function(n, i) {
		$scope.my = 0;
		$(".choice_modal").animate({
			marginTop: '-50%',
			opacity: 0,
		}, 300, function() {
			$(".choice_modal").hide();
		})
		if(i) {
			$scope.id1 = n;
			$scope.id = "";
			$scope.title = "国际•" + n;
		} else {
			$scope.id = n;
			$scope.id1 = "";
			$scope.title = "国内•" + n;
		}
		$scope.my = 0;
		$scope.bool = !$scope.bool;
		$ionicLoading.show({
			template: '<div class="lds-ring"><div></div><div></div><div></div><div></div></div><div class="load_msg">连接交易服务器...</div>'
		});
		$scope.queryContract(i, n);
	};
	//选择自选合约
	$scope.myContract = function(x) {
		if($scope.my_contracts.length) {

			$scope.arr = $scope.my_contracts;
			sessionStorage.setItem("activearr", JSON.stringify($scope.arr));
			sessionStorage.setItem("activename", $scope.arr[0].name);
			sessionStorage.setItem("activecode", $scope.arr[0].code);
			sessionStorage.setItem("activeshort", $scope.arr[0].short);
			sessionStorage.setItem("activebourse", $scope.arr[0].bourse_name);
			sessionStorage.setItem("activeindex",0);
			if(x) {
				$(".choice_modal").animate({
					marginTop: '-50%',
					opacity: 0,
				}, 300, function() {
					$(".choice_modal").hide();
				})
				$scope.bool = !$scope.bool;
			}
			$scope.title = "自选合约"
			$scope.id = "";
			$scope.id1 = "";
			$scope.my = 1;
			$scope.interval_cancel();
			var params = [],
				l = $scope.my_contracts.length;
			for(var v of $scope.my_contracts) {
				params.push(v.code)
			}
			$scope.params = params;

			//			$scope.timer = $interval(function() {
			//				sendAjax();
			//			}, 3000);

			function sendAjax() {
				var back_arr = [];
				$.ajax({
					cache: true,
					url: `https://hq.sinajs.cn/?list=${$scope.params}`,
					type: 'GET',
					dataType: 'script',
					async: false,
					success: function() {
						for(var v of $scope.my_contracts) {
							var x = "hq_str_" + v.code;
							var arrX = eval(x).split(",");
							var up_val, up_down;
							if(arrX.length != 1) {
								if(v.bourse_name == "中金所") {
									if(Number(arrX[13]) != 0) {
										up_val = ((Number(arrX[3]) - Number(arrX[13])) / Number(arrX[13]) * 100).toFixed(2);
									} else {
										up_val = "0.00"
									}
									up_down = (Number(arrX[3]) - Number(arrX[13])).toFixed(2);
									if(up_down > 0) {
										arrX.push([v.name, arrX[3], up_down, up_val, "--", "--", "--", "--", arrX[6], arrX[4], "f_c_red"]);
									} else if(up_down < 0) {
										arrX.push([v.name, arrX[3], up_down, up_val, "--", "--", "--", "--", arrX[6], arrX[4], "f_c_green"]);
									} else {
										arrX.push([v.name, arrX[3], up_down, up_val, "--", "--", "--", "--", arrX[6], arrX[4], "f_c_white"]);
									}

								} else {
									if(Number(arrX[13]) != 0) {
										up_val = ((Number(arrX[8]) - Number(arrX[9])) / Number(arrX[13]) * 100).toFixed(2);
									} else {
										up_val = "0.00"
									}
									up_down = (Number(arrX[8]) - Number(arrX[9])).toFixed(2);
									if(up_down > 0) {
										arrX.push([v.name, arrX[8], up_down, up_val, arrX[6], arrX[11], arrX[7], arrX[12], arrX[13], arrX[14], "f_c_red"]);
									} else if(up_down < 0) {
										arrX.push([v.name, arrX[8], up_down, up_val, arrX[6], arrX[11], arrX[7], arrX[12], arrX[13], arrX[14], "f_c_green"]);
									} else {
										arrX.push([v.name, arrX[8], up_down, up_val, arrX[6], arrX[11], arrX[7], arrX[12], arrX[13], arrX[14], "f_c_white"]);
									}
								}
							} else {
								arrX.push([v.name, "--", "--", "--", "--", "--", "--", "--", "--", "--", "f_c_white"]);
							}
							back_arr.push(arrX);
						}
						$scope.contracts = back_arr;
						console.log("自选合约：", $scope.contracts);
						sessionStorage.setItem("activecontract", JSON.stringify($scope.contracts[0]));
						$scope.$apply();
					}
				})
			}
			sendAjax();
		} else {
			_tip("请先选择合约加入自选");
		}

	}

	$scope.hideModal = function() {
		if($scope.bool) {
			$(".choice_modal").animate({
				marginTop: '-50%',
				opacity: 0,
			}, 300, function() {
				$(".choice_modal").hide();
			})
			$scope.bool = !$scope.bool;
		}
	};
	$scope.preventDefault = function(e) {
		e.stopPropagation();
	};
	//list点击
	$scope.operate = function(e, i) {
		if($scope.t_id != "") {
			$scope.t_id = "";
		} else {
			sessionStorage.setItem("activecode", $scope.arr[i].code);
			sessionStorage.setItem("activename", $scope.arr[i].name);
			sessionStorage.setItem("activeshort", $scope.arr[i].short);
			sessionStorage.setItem("activebourse", $scope.arr[i].bourse_name);
			sessionStorage.setItem("activeindex",i)
			//跳转
			$state.go("tabs2.tabc");
		}
	};
	//长按加入自选
	$scope.addSelf = function(e, n, i) {
		if(!$scope.my) {
			$scope.active_index = i;
			$scope.t_id = n;
		}
	};
	//按钮加入自选
	$scope.addSub = function(e) {
		e.stopPropagation();
		$httpService.httpFun({
			nozzle: "join_optional",
			token: sessionStorage.getItem("token"),
			code: $scope.params[$scope.active_index]
		}).then(function(data) {
			$scope.t_id = "";
			_tip("已加入自选");
		});
	};
	//进入添加自选
	$scope.toAddContract = function() {
		if(sessionStorage.getItem("token")) {
			$state.go("add_contract");
		} else {
			$state.go("login");
		}
	};
	//编辑自选按钮
	$scope.edit = function() {
		if($scope.my) {
			$state.go("edit_contract");
		}
	}
}]);
//添加自选合约
app.controller('add_contractCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.c_id = "";
	$scope.l_id = "";
	$scope.list = [];
	$scope.s_list = [];
	$scope.ss_list = [];
	$scope.code_arr = [];

	$scope.back = function() {
		javascript: history.back(-1);
	};
	$httpService.httpFun({
		nozzle: "bourse_variety",
	}).then(function(data) {
		console.log(data);
		$scope.list = data;
		$scope.l_id = data[0].id
		$scope.s_list = data[0].variety;
	});
	$scope.navTap = function(id, i) {
		$scope.l_id = id;
		$scope.s_list = $scope.list[i].variety;
	};
	$scope.select = function(id, i) {
		$scope.c_id = id;
		$scope.ss_list = $scope.s_list[i].contract;
		$(".ac_modal").slideDown();
	}
	$scope.select1 = function(e, code) {
		if($(e.target).hasClass("s_select")) {
			$(e.target).removeClass("s_select");
			$scope.code_arr.splice($scope.code_arr.indexOf(code), 1);
		} else {
			$(e.target).addClass("s_select");
			$scope.code_arr.push(code);
		}
	}
	$scope.hide = function() {
		$(".ac_modal").slideUp();
		$(".add_contract_con .d_con span").removeClass("s_select");
		$scope.c_id = "";
	};
	$scope.sub = function() {
		console.log($scope.code_arr);
		$httpService.httpFun({
			nozzle: "join_optional",
			token: sessionStorage.getItem("token"),
			code: $scope.code_arr.join(",")
		}).then(function(data) {
			console.log(data)
			$(".ac_modal").slideUp();
			$(".add_contract_con .d_con span").removeClass("s_select");
			$scope.c_id = "";
			_msg("加入成功")
		});
	}
}]);
//编辑自选合约
app.controller('edit_contractCtr', ['$interval', '$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($interval, $ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	//切换页面清除计时器:
	$scope.$on('$destroy', function() {
		$scope.interval_cancel();
	});
	$scope.interval_cancel = function() {
		if($scope.timer) {
			$interval.cancel($scope.timer);
		}
	};
	$scope.back = function() {
		javascript: history.back(-1);
	};
	$scope.bool = false;

	$scope.id = 1;
	$scope.t_id = "";
	$scope.spe_id = 2;
	$scope.click_id = "";
	$scope.code_arr = [];
	//数据
	//	$scope.my_con=[];
	$scope.my_contracts = [];

	//我的自选
	function getInfo() {
		$httpService.httpFun({
			nozzle: "concern",
			token: sessionStorage.getItem("token")
		}).then(function(data) {
			console.log("我的自选：", data);
			$scope.my_con = data;
			var params = [];
			for(var v of $scope.my_con) {
				params.push(v.code)
			}
			$scope.params = params;
			sendAjax();
		});
	}
	getInfo();
	$scope.interval_cancel();

	//	$scope.timer = $interval(function() {
	//		sendAjax();
	//	}, 3000);

	function sendAjax() {
		var back_arr = [];
		$.ajax({
			cache: true,
			url: `https://hq.sinajs.cn/?list=${$scope.params}`,
			type: 'GET',
			dataType: 'script',
			async: false,
			success: function() {
				for(var v of $scope.my_con) {
					var x = "hq_str_" + v.code;
					var arrX = eval(x).split(",");
					var up_val, up_down;
					if(arrX.length != 1) {
						if(v.bourse_name == "中金所") {
							if(Number(arrX[13]) != 0) {
								up_val = ((Number(arrX[3]) - Number(arrX[13])) / Number(arrX[13]) * 100).toFixed(2);
							} else {
								up_val = "0.00"
							}
							up_down = (Number(arrX[3]) - Number(arrX[13])).toFixed(2);
							if(up_down > 0) {
								arrX.push([v.name, arrX[3], up_down, up_val, "--", "--", "--", "--", arrX[6], arrX[4], "f_c_red", "false"]);
							} else if(up_down < 0) {
								arrX.push([v.name, arrX[3], up_down, up_val, "--", "--", "--", "--", arrX[6], arrX[4], "f_c_green", "false"]);
							} else {
								arrX.push([v.name, arrX[3], up_down, up_val, "--", "--", "--", "--", arrX[6], arrX[4], "f_c_white", "false"]);
							}
						} else {
							if(Number(arrX[9]) != 0) {
								up_val = ((Number(arrX[8]) - Number(arrX[9])) / Number(arrX[9]) * 100).toFixed(2);
							} else {
								up_val = "0.00"
							}
							up_down = (Number(arrX[8]) - Number(arrX[9])).toFixed(2);
							if(up_down > 0) {
								arrX.push([v.name, arrX[8], up_down, up_val, arrX[6], arrX[11], arrX[7], arrX[12], arrX[13], arrX[14], "f_c_red", "false"]);
							} else if(up_down < 0) {
								arrX.push([v.name, arrX[8], up_down, up_val, arrX[6], arrX[11], arrX[7], arrX[12], arrX[13], arrX[14], "f_c_green", "false"]);
							} else {
								arrX.push([v.name, arrX[8], up_down, up_val, arrX[6], arrX[11], arrX[7], arrX[12], arrX[13], arrX[14], "f_c_white", "false"]);
							}
						}
					} else {
						arrX.push([v.name, "--", "--", "--", "--", "--", "--", "--", "--", "--", "f_c_white", "false"]);
					}
					back_arr.push(arrX);
				}
				$scope.my_contracts = back_arr;
				console.log($scope.my_contracts);
				//				$scope.apply();
			}
		})
	}

	//list点击
	$scope.operate = function(e, i) {
		if($scope.t_id != "") {
			$scope.t_id = "";
		} else {
			//选中
			if($scope.my_contracts[i][$scope.my_contracts[i].length - 1][11] == 'true') {
				$scope.my_contracts[i][$scope.my_contracts[i].length - 1][11] = 'false';
			} else {
				$scope.my_contracts[i][$scope.my_contracts[i].length - 1][11] = 'true';
				$scope.code_arr.push($scope.params[i]);
				$scope.spe_id = 1;
			}
		}
	};
	//长按取消
	$scope.addSelf = function(e, n, i) {
		$scope.t_id = n;
		$scope.active_index = i;
	};
	//按钮取消收藏
	$scope.cancel = function(e) {
		e.stopPropagation();
		$httpService.httpFun({
			nozzle: "cancel_concern",
			token: sessionStorage.getItem("token"),
			code: $scope.my_con[$scope.active_index].code
		}).then(function(data) {
			$scope.t_id = "";
			_tip("取消收藏成功");
			getInfo();
		});
	};
	//删除全部
	$scope.deleteAll = function() {
		if($scope.code_arr.length) {
			layer.confirm('确定要删除所选合约？', {
				title: "",
				closeBtn: 0,
				btn: ['确定', '取消'],
			}, function(index) {
				//按钮【按钮一】的回调
				$httpService.httpFun({
					nozzle: "batch_cancel",
					token: sessionStorage.getItem("token"),
					code: $scope.code_arr.join(",")
				}).then(function(data) {
					$scope.t_id = "";
					_tip("取消收藏成功");
					getInfo();
				});
				layer.close(index);
			}, function(index) {
				//按钮【按钮二】的回调
				layer.close(index);
			});
		} else {
			_tip("请选择合约");
		}
	}
	//全选
	$scope.doAll = function() {
		$scope.spe_id = 1;
		$scope.code_arr = $scope.params;
		for(let v of $scope.my_contracts) {
			v[v.length - 1][11] = "true";
		}
	}
	//取消
	$scope.doCancel = function() {
		$scope.spe_id = 2;
		for(let v of $scope.my_contracts) {
			v[v.length - 1][11] = "false";
		}
		$scope.code_arr = [];
	}
}]);

//搜索
app.controller('searchCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.back = function() {
		javascript: history.back(-1);
	};
	$scope.company_list = [];
	$scope.list_ = [];
	$scope.list = [];
	$scope.c_id = 1;
	$scope.bool = true;
	$scope.inputData = {
		con: "",
	};
	//开户公司
	$httpService.httpFun({
		nozzle: "company"
	}).then(function(data) {
		console.log(data);
		$scope.company_list = data;
		$scope.list = data[0].data;
		$scope.list_ = data[0].data;
	});
	$scope.navTap = function(i) {
		$scope.c_id = i;
		$scope.list = $scope.company_list[i - 1].data;
		$scope.list_ = $scope.company_list[i - 1].data;
	}
	$scope.inputSearch = function() {
		if($scope.inputData.con) {
			var arr = [];
			for(var v of $scope.list) {
				if((v.name.indexOf($scope.inputData.con)) != -1) {
					arr.push(v);
				}
			}
			$scope.list = arr;
		} else {
			$scope.list = $scope.list_;
		}
	}
	$scope.blur = function() {
		if($scope.inputData.con) {
			$scope.bool = false;
		} else {
			$scope.bool = true;
		}
	}
	$scope.inputClear = function() {
		$scope.inputData.con = "";
		$scope.list = $scope.list_;
	}
	$scope.select = function(name) {
		localStorage.setItem("com", name);
		$state.go("login");
	}
}]);

//tab2 资金
app.controller('tab2Ctr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.back = function() {
		javascript: history.back(-1);
	};
	$scope.refreshIf = 0;
	//主菜单
	$scope.showMenu = function() {
		$(".right_modal_mask").fadeIn();
		$(".right_modal").show();
		$(".right_modal").animate({
			right: 0
		}, 300)
	}
	//刷新
	$scope.refresh = function() {
		$scope.refreshIf = 1;
		$(".loader").show();
	};
}]);

//交易 - 分时 - K线    
app.controller('tabdCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.top_name = sessionStorage.getItem("activename");
	$scope.back = function() {
		$scope.reset();
		$state.go("tab.tab1");
	}
	//重置
	$scope.reset = function(e) {
		$scope.active_id = 0;
		$(".user_keyboard").slideUp();
		$(".pk_modal_top").slideUp();
		$(".k_bot_items").slideUp();
		$(".pankou_modal").hide();
		$(".d_border").removeClass("d_border1");
	}

	$scope.active_deal = false;
	$scope.active_change = false;

	//交易
	$scope.doDeal = function(e) {
		//e.stopPropagation();
		$scope.active_deal = !$scope.active_deal;
		$scope.active_change = false;
		var str = `<div class="pankou_modal"><div>
						<div class="pk_modal_top">
							<div class="do_hand">
								<span>手数</span>
								<span>1</span>
							</div>
							<div class="doc_price">
								<span>对手价</span>
							</div>
							<div class="d_tb">
								<span>3935</span>
								<span>买</span>
							</div>
							<div class="d_tb">
								<span>3934</span>
								<span>卖</span>
							</div>
							<div class="d_tb">
								<span>无仓位</span>
								<span>平</span>
							</div>
						</div>
						<!--手数-->
						<div class="user_keyboard user_keyboard2">
							<div>
								<div class="top_head2">
									<div class="ov_a">
										<span class="f_l">手数输入</span>
										<img class="f_r" src="img/jianpan.png" />
									</div>
									<div>最大开仓手数：买0，卖0</div>
								</div>
								<div class="kb_con2">
									<table class="layui-table">
										<tbody>
											<tr>
												<td class="kb_num">1</td>
												<td class="kb_num">2</td>
												<td class="kb_num">3</td>
												<td class="td kb_add" rowspan="2">+</td>
											</tr>
											<tr>
												<td class="kb_num">4</td>
												<td class="kb_num">5</td>
												<td class="kb_num">6</td>
											</tr>
											<tr>
												<td class="kb_num">7</td>
												<td class="kb_num">8</td>
												<td class="kb_num">9</td>
												<td class="td kb_decrease" rowspan="2">-</td>
											</tr>
											<tr>
												<td class="kb_num" colspan="2">0</td>
												<td class="td kb_devare">
													<span class="iconfont icon-jianpanshanchu"></span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<!--对手价-->
						<div class="user_keyboard user_keyboard3">
							<div>
								<div class="top_head3">
									<div class="ov_a">
										<span class="f_l">价格输入</span>
										<img class="f_r" src="img/jianpan.png" />
									</div>
									<div class="ov_a">
										<span class="f_l">涨停：4090&nbsp;&nbsp;&nbsp;跌停：4090</span>
										<span class="f_r">最小变动：1</span>
									</div>
								</div>
								<div class="kb_con3 ov_a">
									<div class="kb_left f_l">
										<span>对手</span>
										<span>买一</span>
										<span>卖一</span>
										<span>超价</span>
										<span>市价</span>
									</div>
									<div class="kb_mid f_l">
										<div>
											<span>1</span>
											<span>2</span>
											<span>3</span>
										</div>
										<div>
											<span>4</span>
											<span>5</span>
											<span>6</span>
										</div>
										<div>
											<span>7</span>
											<span>8</span>
											<span>9</span>
										</div>
										<div>
											<span>.</span>
											<span>0</span>
											<span class="d_icon">
									<span class="iconfont icon-jianpanshanchu"></span>
											</span>
										</div>
									</div>
									<div class="kb_right f_l">
										<span>最新</span>
										<span class="calc">+</span>
										<span class="calc">-</span>
									</div>
								</div>
							</div>
						</div>
					</div></div>`
		$(".bot_modal").html(str);
		if($scope.active_deal) {
			$(".pk_modal_top").slideDown();
		} else {
			$(".pk_modal_top").slideUp();
			$(".user_keyboard").slideUp();
		}
		//手数
		$(".do_hand").on("click", function() {
			$(".user_keyboard3").hide();
			$(".user_keyboard2").slideDown();
		})
		//对手价
		$(".doc_price").on("click", function() {
			$(".user_keyboard2").hide();
			$(".user_keyboard3").slideDown();
		})
		//按钮
		$(".pk_modal_top .d_tb").on("click", function() {
			var i = $(this).index();
			if(i == 2) {
				//买
				layer.confirm('是否进行下单？', {
					title: "",
					closeBtn: 0,
					btn: ['确定', '取消'],
				}, function(index) {
					//按钮【按钮一】的回调
					layer.close(index);
				}, function(index) {
					//按钮【按钮二】的回调
					layer.close(index);
				});
			} else if(i == 3) {
				//卖
				layer.confirm('是否进行下单？', {
					title: "",
					closeBtn: 0,
					btn: ['确定', '取消'],
				}, function(index) {
					//按钮【按钮一】的回调
					layer.close(index);
				}, function(index) {
					//按钮【按钮二】的回调
					layer.close(index);
				});
			} else {
				//平
				layer.confirm('是否进行下单？', {
					title: "",
					closeBtn: 0,
					btn: ['确定', '取消'],
				}, function(index) {
					//按钮【按钮一】的回调
					layer.close(index);
				}, function(index) {
					//按钮【按钮二】的回调
					layer.close(index);
				});
			}
		})
	}
	//改变k线时间段
	$scope.doChange = function() {
		$scope.active_change = !$scope.active_change;
		$scope.active_deal = false;

		var str = `<div class="k_bot_items">
					<div>
						<span><span>1分钟</span></span>
						<span><span>3分钟</span></span>
						<span><span>5分钟</span></span>
						<span><span>10分钟</span></span>
						<span><span>15分钟</span></span>
						<span><span>30分钟</span></span>
						<span><span>1小时</span></span>
						<span><span>2小时</span></span>
						<span><span>3小时</span></span>
						<span><span>4小时</span></span>
						<span><span>1天</span></span>
						<span><span>1周</span></span>
						<span><span>1月</span></span>
						<span><span>1季</span></span>
						<span><span>1年</span></span>		
					</div>
				</div>`;
		$(".bot_modal").html(str);
		if($scope.active_change) {
			$(".k_bot_items").slideDown();
		} else {
			$(".k_bot_items").slideUp();
		}
		$(".k_bot_items>div>span").eq(0).find("span").addClass("s_select");
		$(".k_bot_items>div>span>span").on("click", function() {
			$(".k_bot_items>div>span>span").removeClass("s_select");
			$(this).addClass("s_select");
		})
	}
	//主菜单
	$scope.showMenu = function() {
		$(".right_modal_mask").fadeIn();
		$(".right_modal").show();
		$(".right_modal").animate({
			right: 0
		}, 300)
	}

	/*::::::::::KKKKKKKKKKKKKKKKK:::::::::::*/
	//数据模型 time0 open1 close2 min3 max4 vol5 tag6 macd7 dif8 dea9
	//['2015-10-19',18.56,18.25,18.19,18.56,55.00,0,-1,0.0['0.00,0.08,0.09] 

	var code = sessionStorage.getItem("activeshort");

	function getKline(code, type) {
		$.ajax({
			cache: true,
			url: `https://stock2.finance.sina.com.cn/futures/api/jsonp.php/var _${code}_=/InnerFuturesNewService.getFewMinLine?symbol=${code}&type=${type}`,
			type: 'GET',
			dataType: 'script',
			success: function() {
				var res = eval("_" + code + "_");
				var all_data = build_all_data(res);
				var data = splitData(all_data);
				build_kline(data);
			}
		});
	}
	getKline(code, "5");

	//时间戳转换成日期
	function timestampToTime(timestamp) {
		var date = new Date(timestamp * 1000); //时间戳为10位需*1000，时间戳为13位的话不需乘1000
		var Y = date.getFullYear() + '/';
		var M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '/';
		var D = date.getDate() + '/';
		var h = date.getHours() + ':';
		var m = date.getMinutes() + ':';
		var s = date.getSeconds();
		if(s == 0) {
			s = "00";
		}
		return Y + M + D + h + m + s;
	};
	/*****************计算macd**************macd*/
	//指标参数m_short,m_long  data数据
	function build_diff(m_short, m_long, data) {
		var result = [];      
		var pre_emashort = 0;      
		var pre_emalong = 0;      
		for(var i = 0; i < data.length; i++) {        
			var ema_short = data[i][2];   //收盘价    
			var ema_long = data[i][2];    //收盘价
			if(i != 0) {          
				ema_short = (1.0 / m_short) * data[i][2] + (1 - 1.0 / m_short) * pre_emashort;          
				ema_long = (1.0 / m_long) * data[i][2] + (1 - 1.0 / m_long) * pre_emalong;        
			}        
			pre_emashort = ema_short;        
			pre_emalong = ema_long;        
			var diff = parseInt((ema_short - ema_long) * 100) / 100;        
			result.push(diff);      
		}      
		return result;
	}
	//console.log(build_diff(12, 26, rowData));
	//指标3 长度
	function build_dea(m, diff) {
		var result = [];      
		var pre_ema_diff = 0;      
		for(var i = 0; i < diff.length; i++) {        
			var ema_diff = diff[i];        
			if(i != 0) {          
				ema_diff = (1.0 / m) * diff[i] + (1 - 1.0 / m) * pre_ema_diff;        
			}
			pre_ema_diff = ema_diff;     
			ema_diff = parseInt(ema_diff * 100) / 100; 
			result.push(ema_diff);      
		}      
		return result;
	};
	//计算 MACD
	function build_macd(data, diff, dea) {
		var result = [];      
		for(var i = 0; i < data.length; i++) {        
			var macd = 2 * (diff[i] - dea[i]);      
			macd = parseInt(macd * 100) / 100;   
			result.push(macd);      
		}      
		return result;
	};
	/*****************计算macd***************/
	function build_all_data(res) {
		var line_data = [];
		for(var i = 0; i < res.length; i++) {
			//数据模型 time0 open1 close2 min3 max4 vol5
			//var time = timestampToTime(res[i].Date);
			var time = res[i].d;
			var line_arr = [time, res[i].o, res[i].c, res[i].l, res[i].h, res[i].v];
			line_data.push(line_arr);
		}
		var diff = build_diff(12, 26, line_data);
		var dea = build_dea(9, diff);
		var macd = build_macd(line_data, diff, dea);
		var all_data = [];
		for(var i = 0; i < line_data.length; i++) {
			//数据模型 time0 open1 close2 min3 max4 vol5 tag6 macd7 dif8 dea9
			var arr = [line_data[i][0], line_data[i][1], line_data[i][2], line_data[i][3], line_data[i][4], line_data[i][5], macd[i], diff[i], dea[i]];
			all_data.push(arr);
		}
		return all_data;
	};
	//数组处理
	function splitData(rawData) {
		var datas = [];
		var times = [];
		var vols = [];
		var macds = [];
		var difs = [];
		var deas = [];
		for(var i = 0; i < rawData.length; i++) {
			datas.push(rawData[i]);
			vols.push(parseInt(rawData[i][5]));
			macds.push(rawData[i][6]);
			difs.push(rawData[i][7]);
			deas.push(rawData[i][8]);
			times.push(rawData[i].splice(0, 1)[0]);
		}
		return {
			datas: datas,
			times: times,
			vols: vols,
			macds: macds,
			difs: difs,
			deas: deas
		};
	};

	//MA计算公式
	function calculateMA(dayCount, data) {
		var result = [];

		for(var i = 0, len = data.times.length; i < len; i++) {
			if(i < dayCount) {
				result.push('-');
				continue;
			}
			var sum = 0;
			for(var j = 0; j < dayCount; j++) {
				sum += parseFloat(data.datas[i - j][1]);
			}

			result.push((sum / dayCount).toFixed(2));
		}
		return result;
	};

	function build_kline(data) {

		var option = {
			backgroundColor: '#1F2227',
			tooltip: {
				show: true,
				trigger: 'axis',
				axisPointer: {
					type: 'cross',
					lineStyle: {
						type: 'dashed',
						color: '#9394a3'
					},
					crossStyle: {
						type: 'dashed',
						color: '#9394a3'
					},
					label: {
						show: true,
						precision: 2,
						backgroundColor: '#585858'
					}
				},
				textStyle: {
					color: "#C2C2C2",
					fontSize: "12"
				},
				backgroundColor: 'transparent',
				showContent: true,
				position: [0, 0],
				formatter: function(params) {
					if(params[0].seriesIndex == 0) {
						return '时间：' + params[0].name + '&nbsp;&nbsp;&nbsp;量：' + params[0].data[5] + '<br/>' + ' 开：' + params[0].data[4] + ' &nbsp;高：' + params[0].data[3] + ' &nbsp;低：' + params[0].data[2] + ' &nbsp;收：' + params[0].data[1] + '<br/>' +
							params[1].seriesName + '：' + params[1].value + '&nbsp;&nbsp;' + params[3].seriesName + '：' + params[3].value; // + ' ' + params[2].seriesName + '：' + params[2].value;
					} else if(params[0].seriesIndex == 4) {
						return '时间：' + ' 量：' + params[1].data[5] + '<br/>' + params[1].name + ' 开：' + params[1].data[4] + ' &nbsp;高：' + params[1].data[3] + ' &nbsp;低：' + params[1].data[2] + ' &nbsp;收：' + params[1].data[1] +
							'<br/>' + params[2].seriesName + '：' + params[2].value + '&nbsp;&nbsp;' + params[4].seriesName + '：' + params[4].value; // + ' ' + params[3].seriesName + '：' + params[3].value;
					}
				},
				crossStyle: {
					opacity: 1
				}
			},
			axisPointer: {
				link: {
					xAxisIndex: 'all'
				}
			},
			grid: [{
				left: '0',
				right: '0',
				height: '50%',
				top: "0"
			}, {
				left: '0',
				right: '0',
				top: '53%',
				height: '20%'
			}, {
				left: '0',
				right: '0',
				top: '75%',
				height: '20%'
			}],
			xAxis: [{
				type: 'category',
				data: data.times,
				scale: true,
				boundaryGap: true,
				axisLine: {
					show: true,
					lineStyle: {
						color: 'rgba(255,0,0,.7)'
					}
				},
				splitLine: {
					show: true,
					lineStyle: {
						color: "rgba(255,0,0,.3)",
						type: "dashed"
					}
				},
				axisLabel: {
					show: false
				},
				axisTick: {
					show: false
				},
				splitNumber: 20,
				min: 'dataMin',
				max: 'dataMax',
				axisPointer: {
					label: {
						show: false
					}
				}
			}, {
				type: 'category',
				gridIndex: 1,
				boundaryGap: true,
				data: data.times,
				axisLabel: {
					show: false
				},
				splitLine: {
					show: true,
					lineStyle: {
						color: "rgba(255,0,0,.3)",
						type: "dashed"
					}
				},
				axisLine: {
					show: true,
					lineStyle: {
						color: '#61688A'
					}
				},
				axisPointer: {
					label: {
						show: false
					}
				}
			}, {
				type: 'category',
				boundaryGap: true,
				gridIndex: 2,
				data: data.times,
				axisLabel: {
					show: false
				}

			}],
			yAxis: [{
				scale: true,
				position: 'right',
				boundaryGap: true,
				splitArea: {
					show: false
				},
				splitLine: {
					show: true,
					lineStyle: {
						color: "rgba(255,0,0,.3)",
						type: "dashed"
					}
				},
			}, {
				gridIndex: 1,
				splitNumber: 3,
				position: 'right',
				boundaryGap: true,
				axisLine: {
					onZero: false
				},
				axisTick: {
					show: false
				},
				splitLine: {
					show: true,
					lineStyle: {
						color: "rgba(255,0,0,.3)",
						type: "dashed"
					}
				},
				axisLabel: {
					show: true
				}
			}, {
				gridIndex: 2,
				splitNumber: 4,
				position: 'right',
				axisLine: {
					onZero: false
				},
				axisTick: {
					show: false
				},
				splitLine: {
					show: true,
					lineStyle: {
						color: "rgba(255,0,0,.3)",
						type: "dashed"
					}
				},
				axisLabel: {
					show: true
				}
			}],
			dataZoom: [{
				type: 'inside',
				xAxisIndex: [0, 1, 2],
				start: 85,
				end: 100
			}],
			series: [{
				name: 'K线周期图表(matols.com)',
				type: 'candlestick',
				data: data.datas,
				splitLine: {
					show: true,
					lineStyle: {
						color: "rgba(255,0,0,.3)",
						type: "dashed"
					}
				},
				itemStyle: {
					normal: {
						color: 'transparent',
						color0: '#2cfcf9',
						borderColor: '#ef232a',
						borderColor0: '#2cfcf9',
						opacity: 1
					}
				}
			}, {
				name: 'MA5',
				type: 'line',
				data: calculateMA(5, data),
				smooth: true,
				symbol: 'none',
				hoverAnimation: false,
				showSymbol: false,
				showAllSymbol: false,
				lineStyle: {
					type: "solid"
				},
				splitLine: {
					show: true,
					lineStyle: {
						color: "rgba(255,0,0,.3)",
						type: "dashed"
					}
				},
				itemStyle: {
					type: "solid"
				}
			}, {
				name: 'Volumn',
				type: 'bar',
				xAxisIndex: 1,
				yAxisIndex: 1,
				data: data.vols,
				splitLine: {
					show: true,
					lineStyle: {
						color: "rgba(255,0,0,.3)",
						type: "dashed"
					},
				},
				itemStyle: {
					normal: {
						color: function(params) {
							var colorList;
							if(data.datas[params.dataIndex][1] > data.datas[params.dataIndex][0]) {
								colorList = '#ef232a';
							} else {
								colorList = '#2cfcf9';
							}
							return colorList;
						},
					},
				}
			}, {
				name: 'MACD',
				type: 'bar',
				xAxisIndex: 2,
				yAxisIndex: 2,
				data: data.macds,
				splitLine: {
					show: true,
					lineStyle: {
						color: "rgba(255,0,0,.3)",
						type: "dashed"
					}
				},
				itemStyle: {
					normal: {
						color: function(params) {
							var colorList;
							if(params.data >= 0) {
								colorList = '#ef232a';
							} else {
								colorList = '#2cfcf9';
							}
							return colorList;
						},
					}
				}
			}, {
				name: 'DIF',
				type: 'line',
				symbol: 'none',
				hoverAnimation: false,
				showSymbol: false,
				showAllSymbol: false,
				xAxisIndex: 2,
				yAxisIndex: 2,
				data: data.difs,
				splitLine: {
					show: true,
					lineStyle: {
						color: "rgba(255,0,0,.3)",
						type: "dashed"
					}
				},
			}, {
				name: 'DEA',
				type: 'line',
				symbol: 'none',

				hoverAnimation: false,
				showSymbol: false,
				showAllSymbol: false,
				xAxisIndex: 2,
				yAxisIndex: 2,
				data: data.deas,
				splitLine: {
					show: true,
					lineStyle: {
						color: "rgba(255,0,0,.3)",
						type: "dashed"
					}
				},
			}]
		};
		var myChart = echarts.init(document.getElementById('main'));
		myChart.clear(option);
		myChart.setOption(option, true);
	};
	/*::::::::::KKKKKKKKKKKKKKKKK:::::::::::*/
}]);
//交易 - 分时 - K线  deal
app.controller('dealCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.back = function() {
		if($stateParams.way == "deal_login") {
			$state.go("tab.tab1");
		} else {
			javascript: history.back(-1);
		}
	};
	$scope.inputData = {
		price: "",
		number: ""
	};
	//获取每个交易所的合约列表
	$scope.get_list = function(id) {
		var jsonData = {
			nozzle: 'select_variety',
			token: localStorage.getItem("token"),
			exchange_id: id
		};
		$httpService.httpFun(jsonData).then(function(data) {
			$scope.contract = data;
			$scope.contract_id = $scope.contract[0].id;
		});
	};
	if(localStorage.getItem("exchange_id") == null) {
		//获取所有交易所信息 
		var jsonData = {
			nozzle: 'select_exchange'
		};
		$httpService.httpFun(jsonData).then(function(data) {
			$scope.exchange_list = data.record;
			$scope.exchang_id = $scope.exchange_list[0].id;
			$scope.get_list(exchang_id);
		});
	} else {
		$scope.exchang_id = localStorage.getItem("exchange_id");
		$scope.get_list($scope.exchang_id);
	};
	//侧边栏
	$scope.slider = function() {
		$('.deal_slider').slideToggle(200);
	};
	//cut  持仓  可撤  委托  成交
	$scope.cut = 0;
	$scope.setCut = function(params) {
		if($scope.cut != params) {
			$scope.cut = params;
			if($scope.cut == 0) {
				//持仓
				$scope.get_hold();
			} else if($scope.cut == 1) {
				//可撤
				$scope.get_data(1);
			} else if($scope.cut == 2) {
				//委托
				$scope.get_data(2);
			} else if($scope.cut == 3) {
				//成交
				$scope.get_data(3);
			}
		}
	};
	//获取数据  inquire: 选择类型1为可撤 2为委托 3为成交
	$scope.get_data = function(type) {
		var jsonData = {
			nozzle: 'order_accomplish',
			token: localStorage.getItem("token"),
			inquire: type
		};
		$httpService.httpFun(jsonData).then(function(data) {
			if(type == 1) {
				//可撤
				$scope.cancel_list = data.record;
			} else if(type == 2) {
				//委托
				$scope.entrust_list = data.record;
			} else if(type == 3) {
				//成交
				$scope.deal_list = data.record;
				for(var i = 0; i < $scope.deal_list.length; i++) {
					$scope.deal_list[i].time = $scope.deal_list[i].time.split(" ");
				}
			}
		});
	};
	//获取持仓数据
	$scope.get_hold = function() {
		var jsonData = {
			nozzle: 'had_shipping',
			token: localStorage.getItem("token")
		};
		$httpService.httpFun(jsonData).then(function(data) {
			$scope.hold_list = data;
		});
	};
	$scope.get_hold();
	//购买
	$scope.buy = function() {
		var str = '<div class="position0 buy_box buy_bigBox">' +
			'<div class="buy_cont">' +
			'<p class="module_title">确认下单</p>' +
			'<p class="module_text">天津松江，25588，买开，1手 </p>' +
			'<div class="module_button">' +
			'<button class="buy_cancel">取消</button>' +
			'<button class="buy_sure">确认</button>' +
			'</div></div></div>';
		$('body').append(str);
		$('.buy_bigBox').fadeIn(200);
		$('.buy_cancel').click(function() {
			fade_out('.buy_bigBox');
		});
		$('.buy_sure').click(function() {
			fade_out('.buy_bigBox');
			var html = '<div class="position0 buy_box buy_confirmBox">' +
				'<div class="buy_cont">' +
				'<p class="module_title">委托</p>' +
				'<p class="module_text">123456 您的可用资金（120.00）不足，此次操作需要360.55 </p>' +
				'<div class="module_button">' +
				'<button class="buy_confirm">确认</button>' +
				'</div></div></div>';
			$('body').append(html);
			$('.buy_confirmBox').fadeIn(200);
			$('.buy_confirm').click(function() {
				$scope.sell_buy(1);
				fade_out('.buy_confirmBox');
			});
		});
	};
	//买入卖出 交易请求
	$scope.sell_buy = function(type) {
		var jsonData = {
			nozzle: 'user_buy',
			token: localStorage.getItem("token"),
			direction: type, //交易方向 1买 2卖
			number: $scope.inputData.number,
			money: 100, //当前价格
			user_money: $scope.inputData.price, //用户输入价格
			contract_id: $scope.contract_id //合约id
		};
		$httpService.httpFun(jsonData).then(function(data) {

		});
	};
	//隐藏 弹窗函数
	function fade_out(str) {
		$(str).fadeOut(200);
		setTimeout(function() {
			$(str).remove();
		}, 200);
	};
	//卖出
	$scope.sell = function() {
		var str = '<div class="position0 buy_box sell_bigBox">' +
			'<div class="buy_cont">' +
			'<p class="module_title">确认下单</p>' +
			'<p class="module_text">天津松江，25588，卖开，1手 </p>' +
			'<div class="module_button">' +
			'<button class="sell_cancel">取消</button>' +
			'<button class="sell_sure">确认</button>' +
			'</div></div></div>';
		$('body').append(str);
		$('.sell_bigBox').fadeIn(200);
		$('.sell_cancel').click(function() {
			fade_out('.sell_bigBox');
		});
		$('.sell_sure').click(function() {
			fade_out('.sell_bigBox');
			var html = '<div class="position0 buy_box sell_confirmBox">' +
				'<div class="buy_cont">' +
				'<p class="module_title">委托</p>' +
				'<p class="module_text">123456 您的可用资金（120.00）不足，此次操作需要360.55</p>' +
				'<div class="module_button">' +
				'<button class="sell_confirm">确认</button>' +
				'</div></div></div>';
			$('body').append(html);
			$('.sell_confirmBox').fadeIn(200);
			$('.sell_confirm').click(function() {
				$scope.sell_buy(2);
				fade_out('.sell_confirmBox');
			});
		});
	};
	//平仓
	$scope.close = function() {
		var str = '<div class="position0 buy_box close_bigBox">' +
			'<div class="buy_cont">' +
			'<p class="module_title">确认下单</p>' +
			'<p class="module_text">天津松江，平仓，1手 </p>' +
			'<div class="module_button">' +
			'<button class="close_cancel">取消</button>' +
			'<button class="close_sure">确认</button>' +
			'</div></div></div>';
		$('body').append(str);
		$('.close_bigBox').fadeIn(200);
		$('.close_cancel').click(function() {
			fade_out('.close_bigBox');
		});
		$('.close_sure').click(function() {
			fade_out('.close_bigBox');
		});
	};
	//点击持仓列表  判断属于哪个交易所  刷新交易所列表 并且给持仓的合约 添加selected
	$scope.hold_click = function() {
		console.log(this.item);
	};
	//撤单
	$scope.cancel = function() {
		var index = this.$index;
		var order_id = this.item.id;
		var str = '<div class="position0 buy_box cancel_bigBox">' +
			'<div class="buy_cont">' +
			'<p class="module_title">撤单</p>' +
			'<p class="module_text">确认撤回 美原油1901 的订单吗?</p>' +
			'<div class="module_button">' +
			'<button class="cancel_cancel">取消</button>' +
			'<button class="cancel_sure">确认</button>' +
			'</div></div></div>';
		$('body').append(str);
		$('.cancel_bigBox').fadeIn(200);
		$('.cancel_cancel').click(function() {
			fade_out('.cancel_bigBox');
		});
		$('.cancel_sure').click(function() {
			var jsonData = {
				nozzle: 'revocation_order',
				token: localStorage.getItem("token"),
				order_id: order_id
			};
			$httpService.httpFun(jsonData).then(function(data) {
				fade_out('.cancel_bigBox');
				$scope.cancel_list.splice(index, 1);
			});
		});
	};

	//设置止损
	$scope.set_profit = function() {
		var index = this.$index;
		var order_id = this.item.id;
		//		$scope.hold_list[index].profit_close = 10;
		var str = '<div class="position0 profit_lose profit_box">' +
			'<div class="profit_lose_cont">' +
			'<p class="profit_lose_title ">止盈设置</p>' +
			'<p class="profit_lose_title1 ma_bom10">合约：美原油1901</p>' +
			'<div class="profit_lose_input ma_bom10">止盈方式：价格</div>' +
			'<div class="profit_lose_input ma_bom10">止盈价格：<input class="profi_close" type="text" value=""/></div>' +
			//			'<div class="profit_lose_input ma_bom10">止盈手数：<input class="profi_number" type="number" value=""/></div>' +
			//			'<div class="profit_lose_input ma_bom15">状态：运行</div>' +
			'<div class="module_button">' +
			'<button class="profit_sure">确认</button>' +
			'<button class="profit_cancel">取消</button>' +
			'</div></div></div>';
		$('body').append(str);
		$('.profit_box').fadeIn(200);
		$('.profi_close').val($scope.hold_list[index].profit_close);
		$('.profit_sure').click(function() {
			var value = $('.profi_close').val();
			var jsonData = {
				nozzle: 'set_close',
				token: localStorage.getItem("token"),
				type: "profit_close",
				number: value,
				order_id: order_id
			};
			$httpService.httpFun(jsonData).then(function(data) {
				fade_out('.profit_box');
				$scope.hold_list[index].profit_close = value;
			});
		});
		$('.profit_cancel').click(function() {
			fade_out('.profit_box');
		});
	};
	//设置止损
	$scope.set_lose = function() {
		var index = this.$index;
		var order_id = this.item.id;
		var str = '<div class="position0 profit_lose lose_box">' +
			'<div class="profit_lose_cont">' +
			'<p class="profit_lose_title ">止损设置</p>' +
			'<p class="profit_lose_title1 ma_bom10">合约：美原油1901</p>' +
			'<div class="profit_lose_input ma_bom10">止损方式：价格</div>' +
			'<div class="profit_lose_input ma_bom10">止损价格：<input class="lose_close" type="text" value=""/></div>' +
			//			'<div class="profit_lose_input ma_bom10">止损手数：<input class="lose_number" type="number" value=""/></div>' +
			//			'<div class="profit_lose_input ma_bom15">状态：运行</div>' +
			'<div class="module_button">' +
			'<button class="lose_sure">确认</button>' +
			'<button class="lose_cancel">取消</button>' +
			'</div></div></div>';
		$('body').append(str);
		$('.lose_box').fadeIn(200);
		$('.lose_close').val($scope.hold_list[index].lose_close);
		$('.lose_sure').click(function() {
			var value = $('.lose_close').val();
			var jsonData = {
				nozzle: 'set_close',
				token: localStorage.getItem("token"),
				type: "lose_close",
				number: value,
				order_id: order_id
			};
			$httpService.httpFun(jsonData).then(function(data) {
				fade_out('.lose_box');
				$scope.hold_list[index].lose_close = value;
			});
			fade_out('.lose_box');
		});
		$('.lose_cancel').click(function() {
			fade_out('.lose_box');
		});
	};
	//K线
	$scope.go_k = function() {
		$state.go('market', {
			cut: 1
		});
	};
	//分时
	$scope.go_time = function() {
		$state.go('market', {
			cut: 0
		});
	};
	//退出交易登录
	$scope.exit_login = function() {
		var str = '<div class="position0 buy_box exit_confirm">' +
			'<div class="buy_cont">' +
			'<p class="module_title">退出登录</p>' +
			'<p class="module_text">退出登录将不能进入交易页面,确认退出吗? </p>' +
			'<div class="module_button">' +
			'<button class="exit_cancel">取消</button>' +
			'<button class="exit_sure">确认</button>' +
			'</div></div></div>';
		$('body').append(str);
		$('.exit_confirm').fadeIn(200);
		$('.exit_cancel').click(function() {
			fade_out('.exit_confirm');
		});
		$('.exit_sure').click(function() {
			var jsonData = {
				nozzle: 'pay_login_lose'
			};
			$httpService.httpFun(jsonData).then(function(data) {
				fade_out('.exit_confirm');
				$state.go("tab.tab4");
			});
		});
	};

}]);
//资金明细
app.controller('capitalCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.back = function() {
		javascript: history.back(-1);
	};
	var jsonData = {
		nozzle: 'capital_detail',
		token: window.localStorage.getItem("token")
	};
	$httpService.httpFun(jsonData).then(function(data) {
		$scope.data = data[1];
		$scope.data.account = parseFloat($scope.data.account);
		$scope.data.frozen = parseFloat($scope.data.frozen);
	});

}]);
//出金入金
app.controller('goldCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.back = function() {
		javascript: history.back(-1);
	};
	$scope.inputData = {
		money: "",
		card: ""
	};
	//查询用户绑定的银行卡
	var jsonData = {
		nozzle: 'find_user_bank',
		token: window.localStorage.getItem("token")
	};
	$httpService.httpFun(jsonData).then(function(data) {
		if(data.length != 0) {
			$scope.inputData.card = data.card;
			$scope.scale = parseFloat(data.parities[3].number);
			//			$scope.usd_money =
		} else {
			layer.msg("请先设定签约银行", {
				icon: 2
			});
		}
	});
	//入金
	$scope.recharge = function() {
		if($scope.inputData.card == "") {
			layer.msg("请先设定签约银行", {
				icon: 2
			});
		} else if($scope.inputData.money <= 0) {
			layer.msg("请输入正确的入金金额", {
				icon: 2
			});
		} else {
			$scope.usd_money = Math.round($scope.inputData.money / $scope.scale * 100) / 100;
			var html = '<div class="position0 buy_box recharge">' +
				'<div class="buy_cont">' +
				'<p class="module_title">确认入金</p>' +
				'<p class="module_text">确认入金人民币' + $scope.inputData.money + '元(约' + $scope.usd_money + '美元)</p>' +
				'<div class="module_button">' +
				'<button class="recharge_cancel">取消</button>' +
				'<button class="recharge_sure">确认</button>' +
				'</div></div></div>';
			$("body").append(html);
			$(".recharge").fadeIn(300);
			$('.recharge_cancel').click(function() {
				$(".recharge").fadeOut(300);
				setTimeout(function() {
					$(".recharge").remove();
				}, 300);
			});
			$('.recharge_sure').click(function() {
				var jsonData = {
					nozzle: 'recharge_apply',
					token: window.localStorage.getItem("token"),
					number: $scope.inputData.money,
					fee: 0,
					money_type: 2
				};
				$httpService.httpFun(jsonData).then(function(data) {
					$(".recharge").fadeOut(300);
					setTimeout(function() {
						$(".recharge").remove();
					}, 300);
				});
			});
		}
	};
	//出金
	$scope.forward = function() {
		if($scope.inputData.card == "") {
			layer.msg("请先设定签约银行", {
				icon: 2
			});
		} else if($scope.inputData.money <= 0) {
			layer.msg("请输入正确的出金金额", {
				icon: 2
			});
		} else {
			$scope.usd_money = Math.round($scope.inputData.money / $scope.scale * 100) / 100;
			var html = '<div class="position0 buy_box forward">' +
				'<div class="buy_cont">' +
				'<p class="module_title">确认出金</p>' +
				'<p class="module_text">确认出金人民币' + $scope.inputData.money + '元(约' + $scope.usd_money + '美元)</p>' +
				'<div class="module_button">' +
				'<button class="recharge_cancel">取消</button>' +
				'<button class="recharge_sure">确认</button>' +
				'</div></div></div>';
			$("body").append(html);
			$(".forward").fadeIn(300);
			$('.recharge_cancel').click(function() {
				$(".forward").fadeOut(300);
				setTimeout(function() {
					$(".forward").remove();
				}, 300);
			});
			$('.recharge_sure').click(function() {
				var jsonData = {
					nozzle: 'withdraw_apply',
					token: window.localStorage.getItem("token"),
					number: $scope.inputData.money,
					fee: 0
				};
				$httpService.httpFun(jsonData).then(function(data) {
					$(".forward").fadeOut(300);
					setTimeout(function() {
						$(".forward").remove();
					}, 300);
				});
			});
		}
	};

}]);
//交易 - 分时 - K线   
app.controller('tabcCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.top_name = sessionStorage.getItem("activename");
	$scope.back = function() {
		$scope.reset();
		$state.go("tab.tab1");
	}
	//重置
	$scope.reset = function(e) {
		$scope.active_id = 0;
		$(".user_keyboard").slideUp();
		$(".pk_modal_top").slideUp();
		$(".pankou_modal").hide();
		$(".d_border").removeClass("d_border1");
	}
	$scope.active_deal = false;
	//交易
	$scope.doDeal = function(e) {
		//e.stopPropagation();
		$scope.active_deal = !$scope.active_deal;
		var str = `<div class="pankou_modal"><div>
						<div class="pk_modal_top">
							<div class="do_hand">
								<span>手数</span>
								<span>1</span>
							</div>
							<div class="doc_price">
								<span>对手价</span>
							</div>
							<div class="d_tb">
								<span>3935</span>
								<span>买</span>
							</div>
							<div class="d_tb">
								<span>3934</span>
								<span>卖</span>
							</div>
							<div class="d_tb">
								<span>无仓位</span>
								<span>平</span>
							</div>
						</div>
						<!--手数-->
						<div class="user_keyboard user_keyboard2">
							<div>
								<div class="top_head2">
									<div class="ov_a">
										<span class="f_l">手数输入</span>
										<img class="f_r" src="img/jianpan.png" />
									</div>
									<div>最大开仓手数：买0，卖0</div>
								</div>
								<div class="kb_con2">
									<table class="layui-table">
										<tbody>
											<tr>
												<td class="kb_num">1</td>
												<td class="kb_num">2</td>
												<td class="kb_num">3</td>
												<td class="td kb_add" rowspan="2">+</td>
											</tr>
											<tr>
												<td class="kb_num">4</td>
												<td class="kb_num">5</td>
												<td class="kb_num">6</td>
											</tr>
											<tr>
												<td class="kb_num">7</td>
												<td class="kb_num">8</td>
												<td class="kb_num">9</td>
												<td class="td kb_decrease" rowspan="2">-</td>
											</tr>
											<tr>
												<td class="kb_num" colspan="2">0</td>
												<td class="td kb_devare">
													<span class="iconfont icon-jianpanshanchu"></span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<!--对手价-->
						<div class="user_keyboard user_keyboard3">
							<div>
								<div class="top_head3">
									<div class="ov_a">
										<span class="f_l">价格输入</span>
										<img class="f_r" src="img/jianpan.png" />
									</div>
									<div class="ov_a">
										<span class="f_l">涨停：4090&nbsp;&nbsp;&nbsp;跌停：4090</span>
										<span class="f_r">最小变动：1</span>
									</div>
								</div>
								<div class="kb_con3 ov_a">
									<div class="kb_left f_l">
										<span>对手</span>
										<span>买一</span>
										<span>卖一</span>
										<span>超价</span>
										<span>市价</span>
									</div>
									<div class="kb_mid f_l">
										<div>
											<span>1</span>
											<span>2</span>
											<span>3</span>
										</div>
										<div>
											<span>4</span>
											<span>5</span>
											<span>6</span>
										</div>
										<div>
											<span>7</span>
											<span>8</span>
											<span>9</span>
										</div>
										<div>
											<span>.</span>
											<span>0</span>
											<span class="d_icon">
									<span class="iconfont icon-jianpanshanchu"></span>
											</span>
										</div>
									</div>
									<div class="kb_right f_l">
										<span>最新</span>
										<span class="calc">+</span>
										<span class="calc">-</span>
									</div>
								</div>
							</div>
						</div>
					</div></div>`
		$(".bot_modal").html(str);
		if($scope.active_deal) {
			$(".pk_modal_top").slideDown();
		} else {
			$(".pk_modal_top").slideUp();
			$(".user_keyboard").slideUp();
		}
		//手数
		$(".do_hand").on("click", function() {
			$(".user_keyboard3").hide();
			$(".user_keyboard2").slideDown();
		})
		//对手价
		$(".doc_price").on("click", function() {
			$(".user_keyboard2").hide();
			$(".user_keyboard3").slideDown();
		})
		//按钮
		$(".pk_modal_top .d_tb").on("click", function() {
			var i = $(this).index();
			if(i == 2) {
				//买
				layer.confirm('是否进行下单？', {
					title: "",
					closeBtn: 0,
					btn: ['确定', '取消'],
				}, function(index) {
					//按钮【按钮一】的回调
					layer.close(index);
				}, function(index) {
					//按钮【按钮二】的回调
					layer.close(index);
				});
			} else if(i == 3) {
				//卖
				layer.confirm('是否进行下单？', {
					title: "",
					closeBtn: 0,
					btn: ['确定', '取消'],
				}, function(index) {
					//按钮【按钮一】的回调
					layer.close(index);
				}, function(index) {
					//按钮【按钮二】的回调
					layer.close(index);
				});
			} else {
				//平
				layer.confirm('是否进行下单？', {
					title: "",
					closeBtn: 0,
					btn: ['确定', '取消'],
				}, function(index) {
					//按钮【按钮一】的回调
					layer.close(index);
				}, function(index) {
					//按钮【按钮二】的回调
					layer.close(index);
				});
			}
		})
	}
	//主菜单
	$scope.showMenu = function() {
		$(".right_modal_mask").fadeIn();
		$(".right_modal").show();
		$(".right_modal").animate({
			right: 0
		}, 300)
	}
	//cut 0:分时  1:K线  2:跳转到交易页面
	if($stateParams.cut == "") {
		$scope.cut = 0;
	} else {
		$scope.cut = $stateParams.cut;
	}
	//	build_timeask("CMSII0", "min,1");

	build_timeask(sessionStorage.getItem("activeshort"));
	//	$scope.setCut = function(params) {
	//		if($scope.cut != params) {
	//			$scope.cut = params;
	//			if($scope.cut == 1) {
	//				$scope.list = 0;
	//				build_ask("CMSII0", "day");
	//			} else if($scope.cut == 0) {
	//				build_timeask("CMSII0", "min,1");
	//			}
	//		}
	//		if($scope.cut == 2) {
	//			if($scope.deal_login_statues == true) {
	//				$state.go("deal");
	//			} else if($scope.deal_login_statues == false) {
	//				$state.go("tab.tab4");
	//			}
	//		};
	//	};
}]);
//交易 - 分时 - K线  deal
app.controller('dealCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.back = function() {
		if($stateParams.way == "deal_login") {
			$state.go("tab.tab1");
		} else {
			javascript: history.back(-1);
		}
	};
	$scope.inputData = {
		price: "",
		number: ""
	};
	//获取每个交易所的合约列表
	$scope.get_list = function(id) {
		var jsonData = {
			nozzle: 'select_variety',
			token: localStorage.getItem("token"),
			exchange_id: id
		};
		$httpService.httpFun(jsonData).then(function(data) {
			$scope.contract = data;
			$scope.contract_id = $scope.contract[0].id;
		});
	};
	if(localStorage.getItem("exchange_id") == null) {
		//获取所有交易所信息 
		var jsonData = {
			nozzle: 'select_exchange'
		};
		$httpService.httpFun(jsonData).then(function(data) {
			$scope.exchange_list = data.record;
			$scope.exchang_id = $scope.exchange_list[0].id;
			$scope.get_list(exchang_id);
		});
	} else {
		$scope.exchang_id = localStorage.getItem("exchange_id");
		$scope.get_list($scope.exchang_id);
	};
	//侧边栏
	$scope.slider = function() {
		$('.deal_slider').slideToggle(200);
	};
	//cut  持仓  可撤  委托  成交
	$scope.cut = 0;
	$scope.setCut = function(params) {
		if($scope.cut != params) {
			$scope.cut = params;
			if($scope.cut == 0) {
				//持仓
				$scope.get_hold();
			} else if($scope.cut == 1) {
				//可撤
				$scope.get_data(1);
			} else if($scope.cut == 2) {
				//委托
				$scope.get_data(2);
			} else if($scope.cut == 3) {
				//成交
				$scope.get_data(3);
			}
		}
	};
	//获取数据  inquire: 选择类型1为可撤 2为委托 3为成交
	$scope.get_data = function(type) {
		var jsonData = {
			nozzle: 'order_accomplish',
			token: localStorage.getItem("token"),
			inquire: type
		};
		$httpService.httpFun(jsonData).then(function(data) {
			if(type == 1) {
				//可撤
				$scope.cancel_list = data.record;
			} else if(type == 2) {
				//委托
				$scope.entrust_list = data.record;
			} else if(type == 3) {
				//成交
				$scope.deal_list = data.record;
				for(var i = 0; i < $scope.deal_list.length; i++) {
					$scope.deal_list[i].time = $scope.deal_list[i].time.split(" ");
				}
			}
		});
	};
	//获取持仓数据
	$scope.get_hold = function() {
		var jsonData = {
			nozzle: 'had_shipping',
			token: localStorage.getItem("token")
		};
		$httpService.httpFun(jsonData).then(function(data) {
			$scope.hold_list = data;
		});
	};
	$scope.get_hold();
	//购买
	$scope.buy = function() {
		var str = '<div class="position0 buy_box buy_bigBox">' +
			'<div class="buy_cont">' +
			'<p class="module_title">确认下单</p>' +
			'<p class="module_text">天津松江，25588，买开，1手 </p>' +
			'<div class="module_button">' +
			'<button class="buy_cancel">取消</button>' +
			'<button class="buy_sure">确认</button>' +
			'</div></div></div>';
		$('body').append(str);
		$('.buy_bigBox').fadeIn(200);
		$('.buy_cancel').click(function() {
			fade_out('.buy_bigBox');
		});
		$('.buy_sure').click(function() {
			fade_out('.buy_bigBox');
			var html = '<div class="position0 buy_box buy_confirmBox">' +
				'<div class="buy_cont">' +
				'<p class="module_title">委托</p>' +
				'<p class="module_text">123456 您的可用资金（120.00）不足，此次操作需要360.55 </p>' +
				'<div class="module_button">' +
				'<button class="buy_confirm">确认</button>' +
				'</div></div></div>';
			$('body').append(html);
			$('.buy_confirmBox').fadeIn(200);
			$('.buy_confirm').click(function() {
				$scope.sell_buy(1);
				fade_out('.buy_confirmBox');
			});
		});
	};
	//买入卖出 交易请求
	$scope.sell_buy = function(type) {
		var jsonData = {
			nozzle: 'user_buy',
			token: localStorage.getItem("token"),
			direction: type, //交易方向 1买 2卖
			number: $scope.inputData.number,
			money: 100, //当前价格
			user_money: $scope.inputData.price, //用户输入价格
			contract_id: $scope.contract_id //合约id
		};
		$httpService.httpFun(jsonData).then(function(data) {

		});
	};
	//隐藏 弹窗函数
	function fade_out(str) {
		$(str).fadeOut(200);
		setTimeout(function() {
			$(str).remove();
		}, 200);
	};
	//卖出
	$scope.sell = function() {
		var str = '<div class="position0 buy_box sell_bigBox">' +
			'<div class="buy_cont">' +
			'<p class="module_title">确认下单</p>' +
			'<p class="module_text">天津松江，25588，卖开，1手 </p>' +
			'<div class="module_button">' +
			'<button class="sell_cancel">取消</button>' +
			'<button class="sell_sure">确认</button>' +
			'</div></div></div>';
		$('body').append(str);
		$('.sell_bigBox').fadeIn(200);
		$('.sell_cancel').click(function() {
			fade_out('.sell_bigBox');
		});
		$('.sell_sure').click(function() {
			fade_out('.sell_bigBox');
			var html = '<div class="position0 buy_box sell_confirmBox">' +
				'<div class="buy_cont">' +
				'<p class="module_title">委托</p>' +
				'<p class="module_text">123456 您的可用资金（120.00）不足，此次操作需要360.55</p>' +
				'<div class="module_button">' +
				'<button class="sell_confirm">确认</button>' +
				'</div></div></div>';
			$('body').append(html);
			$('.sell_confirmBox').fadeIn(200);
			$('.sell_confirm').click(function() {
				$scope.sell_buy(2);
				fade_out('.sell_confirmBox');
			});
		});
	};
	//平仓
	$scope.close = function() {
		var str = '<div class="position0 buy_box close_bigBox">' +
			'<div class="buy_cont">' +
			'<p class="module_title">确认下单</p>' +
			'<p class="module_text">天津松江，平仓，1手 </p>' +
			'<div class="module_button">' +
			'<button class="close_cancel">取消</button>' +
			'<button class="close_sure">确认</button>' +
			'</div></div></div>';
		$('body').append(str);
		$('.close_bigBox').fadeIn(200);
		$('.close_cancel').click(function() {
			fade_out('.close_bigBox');
		});
		$('.close_sure').click(function() {
			fade_out('.close_bigBox');
		});
	};
	//点击持仓列表  判断属于哪个交易所  刷新交易所列表 并且给持仓的合约 添加selected
	$scope.hold_click = function() {
		console.log(this.item);
	};
	//撤单
	$scope.cancel = function() {
		var index = this.$index;
		var order_id = this.item.id;
		var str = '<div class="position0 buy_box cancel_bigBox">' +
			'<div class="buy_cont">' +
			'<p class="module_title">撤单</p>' +
			'<p class="module_text">确认撤回 美原油1901 的订单吗?</p>' +
			'<div class="module_button">' +
			'<button class="cancel_cancel">取消</button>' +
			'<button class="cancel_sure">确认</button>' +
			'</div></div></div>';
		$('body').append(str);
		$('.cancel_bigBox').fadeIn(200);
		$('.cancel_cancel').click(function() {
			fade_out('.cancel_bigBox');
		});
		$('.cancel_sure').click(function() {
			var jsonData = {
				nozzle: 'revocation_order',
				token: localStorage.getItem("token"),
				order_id: order_id
			};
			$httpService.httpFun(jsonData).then(function(data) {
				fade_out('.cancel_bigBox');
				$scope.cancel_list.splice(index, 1);
			});
		});
	};

	//设置止损
	$scope.set_profit = function() {
		var index = this.$index;
		var order_id = this.item.id;
		//		$scope.hold_list[index].profit_close = 10;
		var str = '<div class="position0 profit_lose profit_box">' +
			'<div class="profit_lose_cont">' +
			'<p class="profit_lose_title ">止盈设置</p>' +
			'<p class="profit_lose_title1 ma_bom10">合约：美原油1901</p>' +
			'<div class="profit_lose_input ma_bom10">止盈方式：价格</div>' +
			'<div class="profit_lose_input ma_bom10">止盈价格：<input class="profi_close" type="text" value=""/></div>' +
			//			'<div class="profit_lose_input ma_bom10">止盈手数：<input class="profi_number" type="number" value=""/></div>' +
			//			'<div class="profit_lose_input ma_bom15">状态：运行</div>' +
			'<div class="module_button">' +
			'<button class="profit_sure">确认</button>' +
			'<button class="profit_cancel">取消</button>' +
			'</div></div></div>';
		$('body').append(str);
		$('.profit_box').fadeIn(200);
		$('.profi_close').val($scope.hold_list[index].profit_close);
		$('.profit_sure').click(function() {
			var value = $('.profi_close').val();
			var jsonData = {
				nozzle: 'set_close',
				token: localStorage.getItem("token"),
				type: "profit_close",
				number: value,
				order_id: order_id
			};
			$httpService.httpFun(jsonData).then(function(data) {
				fade_out('.profit_box');
				$scope.hold_list[index].profit_close = value;
			});
		});
		$('.profit_cancel').click(function() {
			fade_out('.profit_box');
		});
	};
	//设置止损
	$scope.set_lose = function() {
		var index = this.$index;
		var order_id = this.item.id;
		var str = '<div class="position0 profit_lose lose_box">' +
			'<div class="profit_lose_cont">' +
			'<p class="profit_lose_title ">止损设置</p>' +
			'<p class="profit_lose_title1 ma_bom10">合约：美原油1901</p>' +
			'<div class="profit_lose_input ma_bom10">止损方式：价格</div>' +
			'<div class="profit_lose_input ma_bom10">止损价格：<input class="lose_close" type="text" value=""/></div>' +
			//			'<div class="profit_lose_input ma_bom10">止损手数：<input class="lose_number" type="number" value=""/></div>' +
			//			'<div class="profit_lose_input ma_bom15">状态：运行</div>' +
			'<div class="module_button">' +
			'<button class="lose_sure">确认</button>' +
			'<button class="lose_cancel">取消</button>' +
			'</div></div></div>';
		$('body').append(str);
		$('.lose_box').fadeIn(200);
		$('.lose_close').val($scope.hold_list[index].lose_close);
		$('.lose_sure').click(function() {
			var value = $('.lose_close').val();
			var jsonData = {
				nozzle: 'set_close',
				token: localStorage.getItem("token"),
				type: "lose_close",
				number: value,
				order_id: order_id
			};
			$httpService.httpFun(jsonData).then(function(data) {
				fade_out('.lose_box');
				$scope.hold_list[index].lose_close = value;
			});
			fade_out('.lose_box');
		});
		$('.lose_cancel').click(function() {
			fade_out('.lose_box');
		});
	};
	//K线
	$scope.go_k = function() {
		$state.go('market', {
			cut: 1
		});
	};
	//分时
	$scope.go_time = function() {
		$state.go('market', {
			cut: 0
		});
	};
	//退出交易登录
	$scope.exit_login = function() {
		var str = '<div class="position0 buy_box exit_confirm">' +
			'<div class="buy_cont">' +
			'<p class="module_title">退出登录</p>' +
			'<p class="module_text">退出登录将不能进入交易页面,确认退出吗? </p>' +
			'<div class="module_button">' +
			'<button class="exit_cancel">取消</button>' +
			'<button class="exit_sure">确认</button>' +
			'</div></div></div>';
		$('body').append(str);
		$('.exit_confirm').fadeIn(200);
		$('.exit_cancel').click(function() {
			fade_out('.exit_confirm');
		});
		$('.exit_sure').click(function() {
			var jsonData = {
				nozzle: 'pay_login_lose'
			};
			$httpService.httpFun(jsonData).then(function(data) {
				fade_out('.exit_confirm');
				$state.go("tab.tab4");
			});
		});
	};

}]);
//资金明细
app.controller('capitalCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.back = function() {
		javascript: history.back(-1);
	};
	var jsonData = {
		nozzle: 'capital_detail',
		token: window.localStorage.getItem("token")
	};
	$httpService.httpFun(jsonData).then(function(data) {
		$scope.data = data[1];
		$scope.data.account = parseFloat($scope.data.account);
		$scope.data.frozen = parseFloat($scope.data.frozen);
	});

}]);
//出金入金
app.controller('goldCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.back = function() {
		javascript: history.back(-1);
	};
	$scope.inputData = {
		money: "",
		card: ""
	};
	//查询用户绑定的银行卡
	var jsonData = {
		nozzle: 'find_user_bank',
		token: window.localStorage.getItem("token")
	};
	$httpService.httpFun(jsonData).then(function(data) {
		if(data.length != 0) {
			$scope.inputData.card = data.card;
			$scope.scale = parseFloat(data.parities[3].number);
			//			$scope.usd_money =
		} else {
			layer.msg("请先设定签约银行", {
				icon: 2
			});
		}
	});
	//入金
	$scope.recharge = function() {
		if($scope.inputData.card == "") {
			layer.msg("请先设定签约银行", {
				icon: 2
			});
		} else if($scope.inputData.money <= 0) {
			layer.msg("请输入正确的入金金额", {
				icon: 2
			});
		} else {
			$scope.usd_money = Math.round($scope.inputData.money / $scope.scale * 100) / 100;
			var html = '<div class="position0 buy_box recharge">' +
				'<div class="buy_cont">' +
				'<p class="module_title">确认入金</p>' +
				'<p class="module_text">确认入金人民币' + $scope.inputData.money + '元(约' + $scope.usd_money + '美元)</p>' +
				'<div class="module_button">' +
				'<button class="recharge_cancel">取消</button>' +
				'<button class="recharge_sure">确认</button>' +
				'</div></div></div>';
			$("body").append(html);
			$(".recharge").fadeIn(300);
			$('.recharge_cancel').click(function() {
				$(".recharge").fadeOut(300);
				setTimeout(function() {
					$(".recharge").remove();
				}, 300);
			});
			$('.recharge_sure').click(function() {
				var jsonData = {
					nozzle: 'recharge_apply',
					token: window.localStorage.getItem("token"),
					number: $scope.inputData.money,
					fee: 0,
					money_type: 2
				};
				$httpService.httpFun(jsonData).then(function(data) {
					$(".recharge").fadeOut(300);
					setTimeout(function() {
						$(".recharge").remove();
					}, 300);
				});
			});
		}
	};
	//出金
	$scope.forward = function() {
		if($scope.inputData.card == "") {
			layer.msg("请先设定签约银行", {
				icon: 2
			});
		} else if($scope.inputData.money <= 0) {
			layer.msg("请输入正确的出金金额", {
				icon: 2
			});
		} else {
			$scope.usd_money = Math.round($scope.inputData.money / $scope.scale * 100) / 100;
			var html = '<div class="position0 buy_box forward">' +
				'<div class="buy_cont">' +
				'<p class="module_title">确认出金</p>' +
				'<p class="module_text">确认出金人民币' + $scope.inputData.money + '元(约' + $scope.usd_money + '美元)</p>' +
				'<div class="module_button">' +
				'<button class="recharge_cancel">取消</button>' +
				'<button class="recharge_sure">确认</button>' +
				'</div></div></div>';
			$("body").append(html);
			$(".forward").fadeIn(300);
			$('.recharge_cancel').click(function() {
				$(".forward").fadeOut(300);
				setTimeout(function() {
					$(".forward").remove();
				}, 300);
			});
			$('.recharge_sure').click(function() {
				var jsonData = {
					nozzle: 'withdraw_apply',
					token: window.localStorage.getItem("token"),
					number: $scope.inputData.money,
					fee: 0
				};
				$httpService.httpFun(jsonData).then(function(data) {
					$(".forward").fadeOut(300);
					setTimeout(function() {
						$(".forward").remove();
					}, 300);
				});
			});
		}
	};

}]);
//设定签约银行
app.controller('bind_cardCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.back = function() {
		javascript: history.back(-1);
	};
	$scope.inputData = {
		user_name: "",
		id_card: "",
		address: "",
		bank_card: ""
	}
	//查询用户绑定的银行卡
	$scope.get_user_bank = function() {
		var jsonData = {
			nozzle: 'find_user_bank',
			token: window.localStorage.getItem("token")
		};
		$httpService.httpFun(jsonData).then(function(data) {
			if(data.length != 0) {
				$scope.bank_id = data.bank;
				$scope.inputData = {
					user_name: data.username,
					id_card: data.number,
					address: data.address,
					bank_card: data.card
				}
			} else {
				$scope.bank_id = $scope.bank_list[0].id;
			}
		});
	};
	//获取银行列表
	var jsonData = {
		nozzle: 'find_bank_name'
	};
	$httpService.httpFun(jsonData).then(function(data) {
		$scope.bank_list = data;
		$scope.get_user_bank();
	});
	//选择银行  改变 $scope.bank_id
	$scope.change_bank = function() {
		$scope.bank_id = this.bank_id;
	};
	$scope.bind_bank = function() {
		if($scope.inputData.user_name == "") {
			layer.msg("请输入姓名", {
				icon: 2
			});
		} else if(!(/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/.test($scope.inputData.id_card))) {
			layer.msg("身份证号格式不正确", {
				icon: 2
			});
		} else if($scope.inputData.address == "") {
			layer.msg("请输入开户行地址", {
				icon: 2
			});
		} else if($scope.inputData.bank_card == "") {
			layer.msg("请输入银行卡号", {
				icon: 2
			});
		} else {
			var jsonData = {
				nozzle: 'sign_bank',
				token: window.localStorage.getItem("token"),
				username: $scope.inputData.user_name,
				number: $scope.inputData.id_card,
				bank: $scope.bank_id,
				address: $scope.inputData.address,
				card: $scope.inputData.bank_card
			};
			$httpService.httpFun(jsonData).then(function(data) {
				setTimeout(function() {
					javascript: history.back(-1);
				}, 1500);
			});
		}
	};

}]);
//交易设置
app.controller('deal_setCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.back = function() {
		javascript: history.back(-1);
	};
	$scope.confirm = false;
	$scope.confirm_open = function() {
		$scope.confirm = !$scope.confirm;
	};
	//设定 交易数量默认加量
	$scope.set_number = function() {
		var html = '<div class="position0 buy_box set_number">' +
			'<div class="buy_cont">' +
			'<p class="module_title">提示</p>' +
			'<p class="module_text">请输入默认交易加量数 </p>' +
			'<input type="number" placeholder="请输入数量" value=""/>' +
			'<div class="module_button">' +
			'<button class="set_cancel">取消</button>' +
			'<button class="set_sure">确认</button>' +
			'</div></div></div>';
		$('body').append(html);
		$('.set_number').fadeIn(200);
		$('.set_cancel').click(function() {
			fade_out(".set_number");
		});
		$('.set_sure').click(function() {
			fade_out(".set_number");
		});
	};
	//隐藏 弹窗函数
	function fade_out(str) {
		$(str).fadeOut(200);
		setTimeout(function() {
			$(str).remove();
		}, 200);
	};
}]);

//条件单
app.controller('tabaCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', 'dateSelect', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory, dateSelect) {
	$scope.top_name = sessionStorage.getItem("activename");
	$scope.back = function() {
		$scope.reset();
		$state.go("tab.tab1");
	}
	$scope.toTip = function() {
		$state.go("condition_risk_tip");
	}
	$scope.focus_index = 0;
	$scope.time = "";
	$scope.o_id = 1;
	$scope.list_id = 1;

	$scope.active_id = 0;
	$scope.hand_arr = [];
	$scope.hand_num = 1;
	$scope.p_type = 1;
	$scope.p_type1 = 0;
	$scope.bool = 0;
	$scope.bool1 = 0;
	$scope.bs_btn = "买";
	$scope.order_type = "开仓";

	$scope.bs_btn1 = "买";
	$scope.order_type1 = "开仓";

	//左
	$scope.left_price_arr = [3, 9, 1, 8];
	$scope.left_price = 3918;

	$scope.leftc_price_arr = [];
	$scope.leftc_price = "对手价";

	$scope.left_hand_arr = [];
	$scope.left_hand = 1;

	//右
	$scope.right_price_arr = [3, 9, 1, 8];
	$scope.right_price = 3918;

	$scope.rightc_price_arr = [];
	$scope.rightc_price = "对手价";

	$scope.right_hand_arr = [];
	$scope.right_hand = 1;

	//初始化时间自定义picker
	$scope.initPicker = function(x, y, z) {
		var arr1 = [];
		var arr2 = [];
		var arr3 = [];

		for(var i = 0; i < 24; i++) {
			if(i < 10) {
				i = "0" + i;
			}
			arr1.push(i + "时")
		}
		for(var i = 0; i < 60; i++) {
			if(i < 10) {
				i = "0" + i;
			}
			arr2.push(i + "分")
		}
		for(var i = 0; i < 60; i++) {
			if(i < 10) {
				i = "0" + i;
			}
			arr3.push(i + "秒")
		}

		var mobileSelect2 = new MobileSelect({
			trigger: '#trigger',
			//		    title: '时间选择',
			triggerDisplayData: false,
			position: [x, y, z],
			wheels: [{
					data: arr1
				},
				{
					data: arr2
				},
				{
					data: arr3
				},
			],
			callback: function(indexArr, data) {
				data[0] = data[0].replace(/[^\d]/g, '');
				data[1] = data[1].replace(/[^\d]/g, '');
				data[2] = data[2].replace(/[^\d]/g, '');
				$scope.time = data.join(":");
				console.log($scope.time)
				$scope.$apply();
			}
		});
	}
	//	$scope.initPicker();
	//主菜单
	$scope.showMenu = function() {
		$(".right_modal_mask").fadeIn();
		$(".right_modal").show();
		$(".right_modal").animate({
			right: 0
		}, 300)
	}

	//重置
	$scope.reset = function(e) {
		$scope.focus_index = 0;
		$(".user_keyboard").slideUp();
		$(".d_border").removeClass("d_border1");
		//		$scope.$apply();
	}
	//买卖切换
	$scope.bsType = function() {
		if($scope.bs_btn == "买") {
			$scope.bs_btn = "卖"
		} else {
			$scope.bs_btn = "买"
		}
	}
	$scope.bsType1 = function() {
		if($scope.bs_btn1 == "买") {
			$scope.bs_btn1 = "卖"
		} else {
			$scope.bs_btn1 = "买"
		}
	}
	$scope.orderType = function() {
		if($scope.order_type == "开仓") {
			$scope.order_type = "平仓"
		} else if($scope.order_type == "平仓") {
			$scope.order_type = "平今"
		} else {
			$scope.order_type = "开仓"
		}
	}
	$scope.orderType1 = function() {
		if($scope.order_type1 == "开仓") {
			$scope.order_type1 = "平仓"
		} else if($scope.order_type1 == "平仓") {
			$scope.order_type1 = "平今"
		} else {
			$scope.order_type1 = "开仓"
		}
	}
	//价格附加
	$scope.open = function(i) {
		$scope.p_type1 = i;
	}
	//价格切换
	$scope.pType = function(i) {
		$scope.p_type = i;
	}
	//期限切换
	$scope.navTime = function(i) {
		$scope.bool = i;
	}
	$scope.navTime1 = function(i) {
		$scope.bool1 = i;
	}

	//价格条件单--价格
	$scope.setPrice = function(e) {
		e.stopPropagation();
		$scope.focus_index = 1;
		var str = `<div class="user_keyboard user_keyboard2">
						<div>
							<div class="top_head2">
								<div class="ov_a">					
									<span class="f_l">价格输入</span>
									<img class="f_r" src="img/jianpan.png"/>
								</div>
								<div class="ov_a">
									<span class="f_l">涨停：4090&nbsp;&nbsp;&nbsp;跌停：4090</span>
									<span class="f_r">最小变动：1</span>
								</div>
							</div>
							<div class="kb_con2">
								<table class="layui-table">
									<tbody>
										<tr>
											<td class="kb_num">1</td>
											<td class="kb_num">2</td>
											<td class="kb_num">3</td>
											<td class="td kb_add" rowspan="2">+</td>
										</tr>
										<tr>
											<td class="kb_num">4</td>
											<td class="kb_num">5</td>
											<td class="kb_num">6</td>
										</tr>
										<tr>
											<td class="kb_num">7</td>
											<td class="kb_num">8</td>
											<td class="kb_num">9</td>
											<td class="td kb_decrease" rowspan="2">-</td>
										</tr>
										<tr>
											<td class="kb_num" colspan="2">0</td>
											<td class="td kb_devare">
												<span class="iconfont icon-jianpanshanchu"></span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>`
		$(".bot_modal").html(str);
		$(".user_keyboard2").slideDown();
		$(".top_head2 img").on("click", function() {
			$scope.focus_index = 0;
			$scope.$apply();
			$scope.reset();
		})
		//jq绑定(start)
		//阻止冒泡
		$(".user_keyboard2").on("click", function(e) {
			e.stopPropagation();
		});
		$(".user_keyboard3").on("click", function(e) {
			e.stopPropagation();
		});
		//条件单价格
		$(".kb_con2 .kb_num").on("click", function() { //点击数字
			if($scope.left_price_arr[0] == 0) {
				$scope.left_price_arr.shift();
			}
			if($scope.left_price_arr.length < 10) {
				$scope.left_price_arr.push(Number($(this).html()));
				$scope.left_price = Number($scope.left_price_arr.join(""));
				console.log($scope.left_price);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_add").on("click", function() { //点击加号
			$scope.left_price++;
			$scope.left_price_arr = $scope.left_price.toString().split("");
			console.log($scope.left_price_arr);
			$scope.$apply();
		})
		$(".kb_con2 .kb_decrease").on("click", function() { //点击减号
			if($scope.left_price) {
				$scope.left_price--;
				$scope.left_price_arr = $scope.left_price.toString().split("");
				console.log($scope.left_price_arr);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_devare").on("click", function() { //点击删除
			if($scope.left_price) {
				$scope.left_price_arr.pop();
				$scope.left_price = Number($scope.left_price_arr.join(""));
				console.log($scope.left_price);
				$scope.$apply();
			}
		})
		//jq绑定(end)
	}

	//价格条件单--对手价
	$scope.setcPrice = function(e) {
		e.stopPropagation();
		$scope.focus_index = 2;
		var str = `<div class="user_keyboard user_keyboard3">
						<div>
							<div class="top_head3">
								<div class="ov_a">					
									<span class="f_l">价格输入</span>
									<img class="f_r" src="img/jianpan.png"/>
								</div>
								<div class="ov_a">
									<span class="f_l">涨停：4090&nbsp;&nbsp;&nbsp;跌停：4090</span>
									<span class="f_r">最小变动：1</span>
								</div>
							</div>
							<div class="kb_con3 ov_a">
								<div class="kb_left f_l">
									<span>对手</span>
									<span>买一</span>
									<span>卖一</span>
									<span>超价</span>
									<span>市价</span>
								</div>
								<div class="kb_mid f_l">
									<div>
										<span class="kb_num">1</span>
										<span class="kb_num">2</span>
										<span class="kb_num">3</span>
									</div>
									<div>
										<span class="kb_num">4</span>
										<span class="kb_num">5</span>
										<span class="kb_num">6</span>
									</div>
									<div>
										<span class="kb_num">7</span>
										<span class="kb_num">8</span>
										<span class="kb_num">9</span>
									</div>
									<div>
										<span class="kb_dot">.</span>
										<span class="kb_num">0</span>
										<span class="d_icon kb_devare">
											<span class="iconfont icon-jianpanshanchu"></span>
										</span>
									</div>
								</div>
								<div class="kb_right f_l">
									<span>最新</span>
									<span class="calc kb_add">+</span>
									<span class="calc kb_decrease">-</span>
								</div>
							</div>
						</div>
					</div>`
		$(".bot_modal").html(str);
		$(".user_keyboard3").slideDown();
		$(".top_head3 img").on("click", function() {
			$scope.focus_index = 0;
			$scope.$apply();
			$scope.reset();
		})
		//jq绑定(start)
		//阻止冒泡
		$(".user_keyboard2").on("click", function(e) {
			e.stopPropagation();
		});
		$(".user_keyboard3").on("click", function(e) {
			e.stopPropagation();
		});
		//条件单对手价
		$(".kb_con3 .kb_num").on("click", function() { //点击数字
			if($scope.leftc_price_arr[0] == 0) {
				$scope.leftc_price_arr.shift();
			}
			if($scope.leftc_price_arr.length < 10) {
				$scope.leftc_price_arr.push(Number($(this).html()));
				$scope.leftc_price = Number($scope.leftc_price_arr.join(""));
				console.log($scope.leftc_price);
				$scope.$apply();
			}
		})
		$(".kb_con3 .kb_add").on("click", function() { //点击加号
			$scope.leftc_price++;
			$scope.leftc_price_arr = $scope.leftc_price.toString().split("");
			console.log($scope.leftc_price_arr);
			$scope.$apply();
		})
		$(".kb_con3 .kb_decrease").on("click", function() { //点击减号
			if($scope.leftc_price) {
				$scope.leftc_price--;
				$scope.leftc_price_arr = $scope.leftc_price.toString().split("");
				console.log($scope.leftc_price_arr);
				$scope.$apply();
			}
		})
		$(".kb_con3 .kb_devare").on("click", function() { //点击删除
			if($scope.leftc_price) {
				$scope.leftc_price_arr.pop();
				$scope.leftc_price = Number($scope.leftc_price_arr.join(""));
				console.log($scope.leftc_price);
				$scope.$apply();
			}
		})
		$(".kb_con3 .kb_dot").on("click", function() {
			if($scope.leftc_price.toString().indexOf(".") == -1) {
				$scope.leftc_price_arr.push(".");
				$scope.leftc_price = Number($scope.leftc_price_arr.join(""));
				console.log($scope.leftc_price_arr, $scope.leftc_price);
				$scope.$apply();
			}
		})
		//jq绑定(end)
	}
	//价格条件单--手数
	$scope.setHand = function(e) {
		e.stopPropagation();
		$scope.focus_index = 3;
		var str = `<div class="user_keyboard user_keyboard2">
						<div>
							<div class="top_head2">
								<div class="ov_a">					
									<span class="f_l">手数输入</span>
									<img class="f_r" src="img/jianpan.png"/>
								</div>
								<div>最大开仓手数：买0，卖0</div>
							</div>
							<div class="kb_con2">
								<table class="layui-table">
									<tbody>
										<tr>
											<td class="kb_num">1</td>
											<td class="kb_num">2</td>
											<td class="kb_num">3</td>
											<td class="td kb_add" rowspan="2">+</td>
										</tr>
										<tr>
											<td class="kb_num">4</td>
											<td class="kb_num">5</td>
											<td class="kb_num">6</td>
										</tr>
										<tr>
											<td class="kb_num">7</td>
											<td class="kb_num">8</td>
											<td class="kb_num">9</td>
											<td class="td kb_decrease" rowspan="2">-</td>
										</tr>
										<tr>
											<td class="kb_num" colspan="2">0</td>
											<td class="td kb_devare">
												<span class="iconfont icon-jianpanshanchu"></span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>`
		$(".bot_modal").html(str);
		$(".user_keyboard2").slideDown();
		$(".top_head2 img").on("click", function() {
			$scope.focus_index = 0;
			$scope.$apply();
			$scope.reset();
		})
		//jq绑定(start)
		//阻止冒泡
		$(".user_keyboard2").on("click", function(e) {
			e.stopPropagation();
		});
		$(".user_keyboard3").on("click", function(e) {
			e.stopPropagation();
		});
		//手数
		$(".kb_con2 .kb_num").on("click", function() { //点击数字
			if($scope.left_hand_arr[0] == 0) {
				$scope.left_hand_arr.shift();
			}
			if($scope.left_hand_arr.length < 5) {
				$scope.left_hand_arr.push(Number($(this).html()));
				$scope.left_hand = Number($scope.left_hand_arr.join(""));
				console.log($scope.left_hand);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_add").on("click", function() { //点击加号
			$scope.left_hand++;
			$scope.left_hand_arr = $scope.left_hand.toString().split("");
			console.log($scope.left_hand_arr);
			$scope.$apply();
		})
		$(".kb_con2 .kb_decrease").on("click", function() { //点击减号
			if($scope.left_hand) {
				$scope.left_hand--;
				$scope.left_hand_arr = $scope.left_hand.toString().split("");
				console.log($scope.left_hand_arr);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_devare").on("click", function() { //点击删除
			if($scope.left_hand) {
				$scope.left_hand_arr.pop();
				$scope.left_hand = Number($scope.left_hand_arr.join(""));
				console.log($scope.left_hand);
				$scope.$apply();
			}
		})
		//jq绑定(end)
	}

	//时间条件单--价格
	$scope.setPrice1 = function(e) {
		e.stopPropagation();
		$scope.focus_index = 1;
		var str = `<div class="user_keyboard user_keyboard2">
						<div>
							<div class="top_head2">
								<div class="ov_a">					
									<span class="f_l">价格输入</span>
									<img class="f_r" src="img/jianpan.png"/>
								</div>
								<div class="ov_a">
									<span class="f_l">涨停：4090&nbsp;&nbsp;&nbsp;跌停：4090</span>
									<span class="f_r">最小变动：1</span>
								</div>
							</div>
							<div class="kb_con2">
								<table class="layui-table">
									<tbody>
										<tr>
											<td class="kb_num">1</td>
											<td class="kb_num">2</td>
											<td class="kb_num">3</td>
											<td class="td kb_add" rowspan="2">+</td>
										</tr>
										<tr>
											<td class="kb_num">4</td>
											<td class="kb_num">5</td>
											<td class="kb_num">6</td>
										</tr>
										<tr>
											<td class="kb_num">7</td>
											<td class="kb_num">8</td>
											<td class="kb_num">9</td>
											<td class="td kb_decrease" rowspan="2">-</td>
										</tr>
										<tr>
											<td class="kb_num" colspan="2">0</td>
											<td class="td kb_devare">
												<span class="iconfont icon-jianpanshanchu"></span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>`
		$(".bot_modal").html(str);
		$(".user_keyboard2").slideDown();
		$(".top_head2 img").on("click", function() {
			$scope.focus_index = 0;
			$scope.$apply();
			$scope.reset();
		})
		//jq绑定(start)
		//阻止冒泡
		$(".user_keyboard2").on("click", function(e) {
			e.stopPropagation();
		});
		$(".user_keyboard3").on("click", function(e) {
			e.stopPropagation();
		});
		//时间条件单  价格
		$(".kb_con2 .kb_num").on("click", function() { //点击数字
			if($scope.right_price_arr[0] == 0) {
				$scope.right_price_arr.shift();
			}
			if($scope.right_price_arr.length < 10) {
				$scope.right_price_arr.push(Number($(this).html()));
				$scope.right_price = Number($scope.right_price_arr.join(""));
				console.log($scope.right_price);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_add").on("click", function() { //点击加号
			$scope.right_price++;
			$scope.right_price_arr = $scope.right_price.toString().split("");
			console.log($scope.right_price_arr);
			$scope.$apply();
		})
		$(".kb_con2 .kb_decrease").on("click", function() { //点击减号
			if($scope.right_price) {
				$scope.right_price--;
				$scope.right_price_arr = $scope.right_price.toString().split("");
				console.log($scope.right_price_arr);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_devare").on("click", function() { //点击删除
			if($scope.right_price) {
				$scope.right_price_arr.pop();
				$scope.right_price = Number($scope.right_price_arr.join(""));
				console.log($scope.right_price);
				$scope.$apply();
			}
		})
		//jq绑定(end)
	}

	//时间条件单--对手价
	$scope.setcPrice1 = function(e) {
		e.stopPropagation();
		$scope.focus_index = 2;
		var str = `<div class="user_keyboard user_keyboard3">
						<div>
							<div class="top_head3">
								<div class="ov_a">					
									<span class="f_l">价格输入</span>
									<img class="f_r" src="img/jianpan.png"/>
								</div>
								<div class="ov_a">
									<span class="f_l">涨停：4090&nbsp;&nbsp;&nbsp;跌停：4090</span>
									<span class="f_r">最小变动：1</span>
								</div>
							</div>
							<div class="kb_con3 ov_a">
								<div class="kb_left f_l">
									<span>对手</span>
									<span>买一</span>
									<span>卖一</span>
									<span>超价</span>
									<span>市价</span>
								</div>
								<div class="kb_mid f_l">
									<div>
										<span class="kb_num">1</span>
										<span class="kb_num">2</span>
										<span class="kb_num">3</span>
									</div>
									<div>
										<span class="kb_num">4</span>
										<span class="kb_num">5</span>
										<span class="kb_num">6</span>
									</div>
									<div>
										<span class="kb_num">7</span>
										<span class="kb_num">8</span>
										<span class="kb_num">9</span>
									</div>
									<div>
										<span class="kb_dot">.</span>
										<span class="kb_num">0</span>
										<span class="d_icon kb_devare">
											<span class="iconfont icon-jianpanshanchu"></span>
										</span>
									</div>
								</div>
								<div class="kb_right f_l">
									<span>最新</span>
									<span class="calc kb_add">+</span>
									<span class="calc kb_decrease">-</span>
								</div>
							</div>
						</div>
					</div>`
		$(".bot_modal").html(str);
		$(".user_keyboard3").slideDown();
		$(".top_head3 img").on("click", function() {
			$scope.focus_index = 0;
			$scope.$apply();
			$scope.reset();
		})
		//jq绑定(start)
		//阻止冒泡
		$(".user_keyboard2").on("click", function(e) {
			e.stopPropagation();
		});
		$(".user_keyboard3").on("click", function(e) {
			e.stopPropagation();
		});
		//对手价
		$(".kb_con3 .kb_num").on("click", function() { //点击数字
			if($scope.rightc_price_arr[0] == 0) {
				$scope.rightc_price_arr.shift();
			}
			if($scope.rightc_price_arr.length < 10) {
				$scope.rightc_price_arr.push(Number($(this).html()));
				$scope.rightc_price = Number($scope.rightc_price_arr.join(""));
				console.log($scope.rightc_price);
				$scope.$apply();
			}
		})
		$(".kb_con3 .kb_add").on("click", function() { //点击加号
			$scope.rightc_price++;
			$scope.rightc_price_arr = $scope.rightc_price.toString().split("");
			console.log($scope.rightc_price_arr);
			$scope.$apply();
		})
		$(".kb_con3 .kb_decrease").on("click", function() { //点击减号
			if($scope.rightc_price) {
				$scope.rightc_price--;
				$scope.rightc_price_arr = $scope.rightc_price.toString().split("");
				console.log($scope.rightc_price_arr);
				$scope.$apply();
			}
		})
		$(".kb_con3 .kb_devare").on("click", function() { //点击删除
			if($scope.rightc_price) {
				$scope.rightc_price_arr.pop();
				$scope.rightc_price = Number($scope.rightc_price_arr.join(""));
				console.log($scope.rightc_price);
				$scope.$apply();
			}
		})
		$(".kb_con3 .kb_dot").on("click", function() {
			if($scope.rightc_price.toString().indexOf(".") == -1) {
				$scope.rightc_price_arr.push(".");
				$scope.rightc_price = Number($scope.rightc_price_arr.join(""));
				console.log($scope.rightc_price_arr, $scope.rightc_price);
				$scope.$apply();
			}
		})
		//jq绑定(end)
	}
	//时间条件单--手数
	$scope.setHand1 = function(e) {
		e.stopPropagation();
		$scope.focus_index = 3;
		var str = `<div class="user_keyboard user_keyboard2">
						<div>
							<div class="top_head2">
								<div class="ov_a">					
									<span class="f_l">手数输入</span>
									<img class="f_r" src="img/jianpan.png"/>
								</div>
								<div>最大开仓手数：买0，卖0</div>
							</div>
							<div class="kb_con2">
								<table class="layui-table">
									<tbody>
										<tr>
											<td class="kb_num">1</td>
											<td class="kb_num">2</td>
											<td class="kb_num">3</td>
											<td class="td kb_add" rowspan="2">+</td>
										</tr>
										<tr>
											<td class="kb_num">4</td>
											<td class="kb_num">5</td>
											<td class="kb_num">6</td>
										</tr>
										<tr>
											<td class="kb_num">7</td>
											<td class="kb_num">8</td>
											<td class="kb_num">9</td>
											<td class="td kb_decrease" rowspan="2">-</td>
										</tr>
										<tr>
											<td class="kb_num" colspan="2">0</td>
											<td class="td kb_devare">
												<span class="iconfont icon-jianpanshanchu"></span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>`
		$(".bot_modal").html(str);
		$(".user_keyboard2").slideDown();
		$(".top_head2 img").on("click", function() {
			$scope.focus_index = 0;
			$scope.$apply();
			$scope.reset();
		})
		//jq绑定(start)
		//阻止冒泡
		$(".user_keyboard2").on("click", function(e) {
			e.stopPropagation();
		});
		$(".user_keyboard3").on("click", function(e) {
			e.stopPropagation();
		});
		//手数
		$(".kb_con2 .kb_num").on("click", function() { //点击数字
			if($scope.right_hand_arr[0] == 0) {
				$scope.right_hand_arr.shift();
			}
			if($scope.right_hand_arr.length < 5) {
				$scope.right_hand_arr.push(Number($(this).html()));
				$scope.right_hand = Number($scope.right_hand_arr.join(""));
				console.log($scope.right_hand);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_add").on("click", function() { //点击加号
			$scope.right_hand++;
			$scope.right_hand_arr = $scope.right_hand.toString().split("");
			console.log($scope.right_hand_arr);
			$scope.$apply();
		})
		$(".kb_con2 .kb_decrease").on("click", function() { //点击减号
			if($scope.right_hand) {
				$scope.right_hand--;
				$scope.right_hand_arr = $scope.right_hand.toString().split("");
				console.log($scope.right_hand_arr);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_devare").on("click", function() { //点击删除
			if($scope.right_hand) {
				$scope.right_hand_arr.pop();
				$scope.right_hand = Number($scope.right_hand_arr.join(""));
				console.log($scope.right_hand);
				$scope.$apply();
			}
		})
		//jq绑定(end)
	}

	//添加按钮事件
	$scope.addFun = function(i) {
		if(i) {
			$("#top_info").fadeIn("fast", function() {
				setTimeout(function() {
					$("#top_info").hide();
				}, 1500)
			});
		} else {
			$("#top_info").fadeIn("fast", function() {
				setTimeout(function() {
					$("#top_info").hide();
				}, 1500)
			});
		}
	}

	//切换
	$scope.navTap = function(i) {
		$scope.o_id = i;
		if($scope.o_id == 2) {
			var h = new Date().getHours();
			var m = new Date().getMinutes();
			var s = new Date().getSeconds();
			if(h < 10) {
				h = "0" + h;
			}
			if(m < 10) {
				m = "0" + m;
			}
			if(s < 10) {
				s = "0" + s;
			}
			$scope.time = h + ":" + m + ":" + s;
			$scope.initPicker(Number(h), Number(m), Number(s));
		}
	}
	$scope.navTap1 = function(i) {
		$scope.list_id = i;
	}
	//下单
	$scope.makeDeal = function() {
		layer.confirm('确定下单吗？', {
			title: "",
			closeBtn: 0,
			btn: ['确定', '取消'],
		}, function(index) {
			//按钮【按钮一】的回调
			layer.close(index);
		}, function(index) {
			//按钮【按钮二】的回调
			layer.close(index);
		});
	}
}]);
//盘口
app.controller('tabbCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.top_name = sessionStorage.getItem("activename");
	$scope.back = function() {
		$scope.reset();
		$state.go("tab.tab1");
	}
	$scope.info = [];

	//替换内容(start)
	//查询盘口信息
	var active_arr = JSON.parse(sessionStorage.getItem("activearr"));
	console.log($scope.top_name, active_arr);
	for(let i in active_arr) {
		if($scope.top_name == active_arr[i].name) {
			var active_bourse = active_arr[i].bourse_name;
			var code = sessionStorage.getItem("activecode");
			console.log("所属交易所:", active_bourse);
			$.ajax({
				cache: true,
				url: `https://hq.sinajs.cn/?list=${code}`,
				type: 'GET',
				dataType: 'script',
				async: false,
				success: function() {
					var x = "hq_str_" + code;
					var info = eval(x).split(",");
					console.log("合约信息:", info);
					if(active_bourse == "中金所") {
						//中金所合约格式信息
						//$scope.info=[];
					} else {
						//其他合约格式信息
						//$scope.info=[];
					}
				}
			})
			break;
		}
	}
	//替换内容(end)

	//被替换内容(start)
	var list = JSON.parse(sessionStorage.getItem("activecontract"));
	if(sessionStorage.getItem("activebourse") == "中金所") {
		$scope.info.push(["--", "--", "--", "--", list[list.length - 1][1], list[list.length - 1][2], list[0], list[4], list[1], list[6], list[2], "--", list[9], list[10], "--", list[14], "--", list[13]]);
	} else {
		$scope.info.push(["--", "--", "--", "--", list[list.length - 1][1], list[list.length - 1][2], list[2], list[14], list[3], list[13], list[4], "--", "--", "--", "--", list[5], list[9], list[10]]);
	}
	//被替换内容(end)

	//jq绑定(start)
	//阻止冒泡
	$(".user_keyboard2").on("click", function(e) {
		e.stopPropagation();
	});
	$(".user_keyboard3").on("click", function(e) {
		e.stopPropagation();
	});
	//手数
	$(".kb_con2 .kb_num").on("click", function() { //点击数字
		if($scope.hand_arr[0] == 0) {
			$scope.hand_arr.shift();
		}
		$scope.hand_arr.push(Number($(this).html()));
		$scope.hand_num = Number($scope.hand_arr.join(""));
		console.log($scope.hand_num);
	})
	$(".kb_con2 .kb_add").on("click", function() { //点击加号
		$scope.hand_num++;
		$scope.hand_arr = $scope.hand_num.toString().split("");
		console.log($scope.hand_arr)
	})
	$(".kb_con2 .kb_decrease").on("click", function() { //点击减号
		if($scope.hand_num) {
			$scope.hand_num--;
			$scope.hand_arr = $scope.hand_num.toString().split("");
			console.log($scope.hand_arr)
		}
	})
	$(".kb_con2 .kb_devare").on("click", function() { //点击删除
		if($scope.hand_num) {
			$scope.hand_arr.pop();
			$scope.hand_num = Number($scope.hand_arr.join(""));
			console.log($scope.hand_num);
		}
	})
	//jq绑定(end)

	$scope.c_id = 1;
	$scope.active_deal = false;
	$scope.hand_arr = [];
	$scope.hand_num = 1;
	//主菜单
	$scope.showMenu = function() {
		$(".right_modal_mask").fadeIn();
		$(".right_modal").show();
		$(".right_modal").animate({
			right: 0
		}, 300)
	}
	//重置
	$scope.reset = function(e) {
		$scope.active_id = 0;
		$(".user_keyboard").slideUp();
		$(".pk_modal_top").slideUp();
		$(".pankou_modal").hide();
		$(".d_border").removeClass("d_border1");
	}
	//种类 手数 价格
	$scope.doDeal = function(e) {
		//		e.stopPropagation();
		$scope.active_deal = !$scope.active_deal;
		var str = `<div class="pankou_modal"><div>
						<div class="pk_modal_top">
							<div class="do_hand">
								<span>手数</span>
								<span>1</span>
							</div>
							<div class="doc_price">
								<span>对手价</span>
							</div>
							<div class="d_tb">
								<span>3935</span>
								<span>买</span>
							</div>
							<div class="d_tb">
								<span>3934</span>
								<span>卖</span>
							</div>
							<div class="d_tb">
								<span>无仓位</span>
								<span>平</span>
							</div>
						</div>
						<!--手数-->
						<div class="user_keyboard user_keyboard2">
							<div>
								<div class="top_head2">
									<div class="ov_a">
										<span class="f_l">手数输入</span>
										<img class="f_r" src="img/jianpan.png" />
									</div>
									<div>最大开仓手数：买0，卖0</div>
								</div>
								<div class="kb_con2">
									<table class="layui-table">
										<tbody>
											<tr>
												<td class="kb_num">1</td>
												<td class="kb_num">2</td>
												<td class="kb_num">3</td>
												<td class="td kb_add" rowspan="2">+</td>
											</tr>
											<tr>
												<td class="kb_num">4</td>
												<td class="kb_num">5</td>
												<td class="kb_num">6</td>
											</tr>
											<tr>
												<td class="kb_num">7</td>
												<td class="kb_num">8</td>
												<td class="kb_num">9</td>
												<td class="td kb_decrease" rowspan="2">-</td>
											</tr>
											<tr>
												<td class="kb_num" colspan="2">0</td>
												<td class="td kb_devare">
													<span class="iconfont icon-jianpanshanchu"></span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<!--对手价-->
						<div class="user_keyboard user_keyboard3">
							<div>
								<div class="top_head3">
									<div class="ov_a">
										<span class="f_l">价格输入</span>
										<img class="f_r" src="img/jianpan.png" />
									</div>
									<div class="ov_a">
										<span class="f_l">涨停：4090&nbsp;&nbsp;&nbsp;跌停：4090</span>
										<span class="f_r">最小变动：1</span>
									</div>
								</div>
								<div class="kb_con3 ov_a">
									<div class="kb_left f_l">
										<span>对手</span>
										<span>买一</span>
										<span>卖一</span>
										<span>超价</span>
										<span>市价</span>
									</div>
									<div class="kb_mid f_l">
										<div>
											<span>1</span>
											<span>2</span>
											<span>3</span>
										</div>
										<div>
											<span>4</span>
											<span>5</span>
											<span>6</span>
										</div>
										<div>
											<span>7</span>
											<span>8</span>
											<span>9</span>
										</div>
										<div>
											<span>.</span>
											<span>0</span>
											<span class="d_icon">
									<span class="iconfont icon-jianpanshanchu"></span>
											</span>
										</div>
									</div>
									<div class="kb_right f_l">
										<span>最新</span>
										<span class="calc">+</span>
										<span class="calc">-</span>
									</div>
								</div>
							</div>
						</div>
					</div></div>`
		$(".bot_modal").html(str);
		if($scope.active_deal) {
			$(".pk_modal_top").slideDown();
		} else {
			$(".pk_modal_top").slideUp();
			$(".user_keyboard").slideUp();
		}
		//手数
		$(".do_hand").on("click", function() {
			$(".user_keyboard3").hide();
			$(".user_keyboard2").slideDown();
		})
		//对手价
		$(".doc_price").on("click", function() {
			$(".user_keyboard2").hide();
			$(".user_keyboard3").slideDown();
		})
		//按钮
		$(".pk_modal_top .d_tb").on("click", function() {
			var i = $(this).index();
			if(i == 2) {
				//买
				layer.confirm('是否进行下单？', {
					title: "",
					closeBtn: 0,
					btn: ['确定', '取消'],
				}, function(index) {
					//按钮【按钮一】的回调
					layer.close(index);
				}, function(index) {
					//按钮【按钮二】的回调
					layer.close(index);
				});
			} else if(i == 3) {
				//卖
				layer.confirm('是否进行下单？', {
					title: "",
					closeBtn: 0,
					btn: ['确定', '取消'],
				}, function(index) {
					//按钮【按钮一】的回调
					layer.close(index);
				}, function(index) {
					//按钮【按钮二】的回调
					layer.close(index);
				});
			} else {
				//平
				layer.confirm('是否进行下单？', {
					title: "",
					closeBtn: 0,
					btn: ['确定', '取消'],
				}, function(index) {
					//按钮【按钮一】的回调
					layer.close(index);
				}, function(index) {
					//按钮【按钮二】的回调
					layer.close(index);
				});
			}
		})
	}
	//切换
	$scope.navTap = function(i) {
		$scope.c_id = i;
	}
	//下单
	$scope.makeDeal = function() {
		layer.confirm('确定下单吗？', {
			title: "",
			closeBtn: 0,
			btn: ['确定', '取消'],
		}, function(index) {
			//按钮【按钮一】的回调
			layer.close(index);
		}, function(index) {
			//按钮【按钮二】的回调
			layer.close(index);
		});
	}
}]);
//下单
app.controller('tabeCtr', ['$interval','$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($interval,$ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	var nameEl = document.getElementById('com_picker');
	var arr = JSON.parse(sessionStorage.getItem("activearr"));
	var data=[];
	//获取当前选中合约在当前合约组的索引
	for(let i in arr){
		if(arr[i].code == sessionStorage.getItem("activecode")){
			$scope.global_cindex = i;
			break;
		}
	}
	for(let i in arr){
		let obj={};
		obj.value = i+1;
		obj.text = arr[i].name;
		data.push(obj);
	}

	$scope.picker = new Picker({
		data: [data],
		selectedIndex: [sessionStorage.getItem("activeindex")],
		title: ''
	});

	$scope.picker.on('picker.change', function(index,selectedIndex) {
		console.log(data[selectedIndex]);
		window.t1 = setTimeout(function(){
			$scope.picker.hide();
			if_show = false;
			clearTimeout(window.t1);
			sessionStorage.setItem("activebourse",JSON.parse(sessionStorage.getItem("activearr"))[selectedIndex].bourse_name);
			sessionStorage.setItem("activecode",JSON.parse(sessionStorage.getItem("activearr"))[selectedIndex].code);
			sessionStorage.setItem("activeindex",selectedIndex);
			sessionStorage.setItem("activename",JSON.parse(sessionStorage.getItem("activearr"))[selectedIndex].name);
			sessionStorage.setItem("activeshort",JSON.parse(sessionStorage.getItem("activearr"))[selectedIndex].short);
			$scope.top_name = sessionStorage.getItem("activename");
			$scope.active_name = sessionStorage.getItem("activename");
			$scope.global_cindex = selectedIndex;
			$scope.$apply();
		},1200)
		
	});


	var if_show=false;
	if($(".picker").length>1){
		$(".picker")[$(".picker").length-1].remove();
	}
	EventUtil.listenTouchDirection(document, true,touch , function(){}, touch, function(){})
	
	function touch(){
		clearTimeout(window.t0);
		console.log($scope.global_cindex);
		window.t0 = setTimeout(function(){
			if($scope.global_cindex == sessionStorage.getItem("activeindex")){
				$scope.picker.hide();
				if_show = false;
			}
		},1200)
		if(!if_show){
			if_show = true;
			$scope.picker.show();
			$(".picker-choose").hide();
		}else{
			clearTimeout(window.t1);
		}
	}
	
	$scope.top_name = sessionStorage.getItem("activename");
	$scope.active_name = sessionStorage.getItem("activename");
	$scope.back = function() {
		$scope.reset();
		$state.go("tab.tab1");
	}

	$scope.c_id = 1;
	$scope.active_id = 0;
	$scope.hand_arr = [];
	$scope.hand_num = 1;

	$scope.u_deals = [];

	//手数
	$scope.left_hand_arr = [];
	$scope.left_hand = 1;

	//对手价
	$scope.leftc_price_arr = [];
	$scope.leftc_price = "对手价";

	//主菜单
	$scope.showMenu = function() {
		$(".right_modal_mask").fadeIn();
		$(".right_modal").show();
		$(".right_modal").animate({
			right: 0
		}, 300)
	}
	//重置
	$scope.reset = function(e) {
		$scope.active_id = 0;
		$(".user_keyboard").slideUp();
		$(".d_border").removeClass("d_border1");
	}
	//种类 手数 价格
	$scope.select = function(i, e) {
		e.stopPropagation();
		$scope.active_id = i;
		if(i == 1) {
			//种类
			var arr = JSON.parse(sessionStorage.getItem("activearr"));
			var str0 = "";
			for(let v of arr) {
				str0 += `<span class="span" data-code="${v.code}" data-short="${v.short}">${v.name}</span>`
			}
			var str = `<div class="user_keyboard user_keyboard1">
						<div>
							<div class="top_head1">
								<span class="f_l">从当天列表中选择合约</span>
								<img class="f_r" src="img/jianpan.png"/>
							</div>
							<div class="kb_con1">${str0}</div>
						</div>
					</div>`
			$(".bot_modal").html(str);
			$(".user_keyboard1").slideDown();
			$(".top_head1").on("click", function() {
				$scope.reset();
			})
			$(".kb_con1>.span").on("click", function() {
				sessionStorage.setItem("activename", $(this).html());
				sessionStorage.setItem("activecode", $(this).data("code"));
				sessionStorage.setItem("activeshort", $(this).data("short"));
				$scope.top_name = sessionStorage.getItem("activename");
				$scope.active_name = sessionStorage.getItem("activename");
				$scope.reset();
			})
		} else if(i == 2) {
			//手数
			var str = `<div class="user_keyboard user_keyboard2">
							<div>
								<div class="top_head2">
									<div class="ov_a">					
										<span class="f_l">手数输入</span>
										<img class="f_r" src="img/jianpan.png"/>
									</div>
									<div>最大开仓手数：买0，卖0</div>
								</div>
								<div class="kb_con2">
									<table class="layui-table">
										<tbody>
											<tr>
												<td class="kb_num">1</td>
												<td class="kb_num">2</td>
												<td class="kb_num">3</td>
												<td class="td kb_add" rowspan="2">+</td>
											</tr>
											<tr>
												<td class="kb_num">4</td>
												<td class="kb_num">5</td>
												<td class="kb_num">6</td>
											</tr>
											<tr>
												<td class="kb_num">7</td>
												<td class="kb_num">8</td>
												<td class="kb_num">9</td>
												<td class="td kb_decrease" rowspan="2">-</td>
											</tr>
											<tr>
												<td class="kb_num" colspan="2">0</td>
												<td class="td kb_devare">
													<span class="iconfont icon-jianpanshanchu"></span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>`
			$(".bot_modal").html(str);
			$(".user_keyboard2").slideDown();
			$(".top_head2 img").on("click", function() {
				$scope.focus_index = 0;
				$scope.$apply();
				$scope.reset();
			})
			//jq绑定(start)
			//阻止冒泡
			$(".user_keyboard2").on("click", function(e) {
				e.stopPropagation();
			});
			$(".user_keyboard3").on("click", function(e) {
				e.stopPropagation();
			});
			//手数
			$(".kb_con2 .kb_num").on("click", function() { //点击数字
				if($scope.left_hand_arr[0] == 0) {
					$scope.left_hand_arr.shift();
				}
				if($scope.left_hand_arr.length < 5) {
					$scope.left_hand_arr.push(Number($(this).html()));
					$scope.left_hand = Number($scope.left_hand_arr.join(""));
					console.log($scope.left_hand);
					$scope.$apply();
				}
			})
			$(".kb_con2 .kb_add").on("click", function() { //点击加号
				$scope.left_hand++;
				$scope.left_hand_arr = $scope.left_hand.toString().split("");
				console.log($scope.left_hand_arr);
				$scope.$apply();
			})
			$(".kb_con2 .kb_decrease").on("click", function() { //点击减号
				if($scope.left_hand) {
					$scope.left_hand--;
					$scope.left_hand_arr = $scope.left_hand.toString().split("");
					console.log($scope.left_hand_arr);
					$scope.$apply();
				}
			})
			$(".kb_con2 .kb_devare").on("click", function() { //点击删除
				if($scope.left_hand) {
					$scope.left_hand_arr.pop();
					$scope.left_hand = Number($scope.left_hand_arr.join(""));
					console.log($scope.left_hand);
					$scope.$apply();
				}
			})
			//jq绑定(end)
		} else {
			//价格--对手价
			var str = `<div class="user_keyboard user_keyboard3">
						<div>
							<div class="top_head3">
								<div class="ov_a">					
									<span class="f_l">价格输入</span>
									<img class="f_r" src="img/jianpan.png"/>
								</div>
								<div class="ov_a">
									<span class="f_l">涨停：4090&nbsp;&nbsp;&nbsp;跌停：4090</span>
									<span class="f_r">最小变动：1</span>
								</div>
							</div>
							<div class="kb_con3 ov_a">
								<div class="kb_left f_l">
									<span>对手</span>
									<span>买一</span>
									<span>卖一</span>
									<span>超价</span>
									<span>市价</span>
								</div>
								<div class="kb_mid f_l">
									<div>
										<span class="kb_num">1</span>
										<span class="kb_num">2</span>
										<span class="kb_num">3</span>
									</div>
									<div>
										<span class="kb_num">4</span>
										<span class="kb_num">5</span>
										<span class="kb_num">6</span>
									</div>
									<div>
										<span class="kb_num">7</span>
										<span class="kb_num">8</span>
										<span class="kb_num">9</span>
									</div>
									<div>
										<span class="kb_dot">.</span>
										<span class="kb_num">0</span>
										<span class="d_icon kb_devare">
											<span class="iconfont icon-jianpanshanchu"></span>
										</span>
									</div>
								</div>
								<div class="kb_right f_l">
									<span>最新</span>
									<span class="calc kb_add">+</span>
									<span class="calc kb_decrease">-</span>
								</div>
							</div>
						</div>
					</div>`
			$(".bot_modal").html(str);
			$(".user_keyboard3").slideDown();
			$(".top_head3 img").on("click", function() {
				$scope.focus_index = 0;
				$scope.$apply();
				$scope.reset();
			})
			//jq绑定(start)
			//阻止冒泡
			$(".user_keyboard2").on("click", function(e) {
				e.stopPropagation();
			});
			$(".user_keyboard3").on("click", function(e) {
				e.stopPropagation();
			});
			//手数
			$(".kb_con3 .kb_num").on("click", function() { //点击数字
				if($scope.leftc_price_arr[0] == 0) {
					$scope.leftc_price_arr.shift();
				}
				if($scope.leftc_price_arr.length < 10) {
					$scope.leftc_price_arr.push(Number($(this).html()));
					$scope.leftc_price = Number($scope.leftc_price_arr.join(""));
					console.log($scope.leftc_price);
					$scope.$apply();
				}
			})
			$(".kb_con3 .kb_add").on("click", function() { //点击加号
				$scope.leftc_price++;
				$scope.leftc_price_arr = $scope.leftc_price.toString().split("");
				console.log($scope.leftc_price_arr);
				$scope.$apply();
			})
			$(".kb_con3 .kb_decrease").on("click", function() { //点击减号
				if($scope.leftc_price) {
					$scope.leftc_price--;
					$scope.leftc_price_arr = $scope.leftc_price.toString().split("");
					console.log($scope.leftc_price_arr);
					$scope.$apply();
				}
			})
			$(".kb_con3 .kb_devare").on("click", function() { //点击删除
				if($scope.leftc_price) {
					$scope.leftc_price_arr.pop();
					$scope.leftc_price = Number($scope.leftc_price_arr.join(""));
					console.log($scope.leftc_price);
					$scope.$apply();
				}
			})
			$(".kb_con3 .kb_dot").on("click", function() {
				if($scope.leftc_price.toString().indexOf(".") == -1) {
					$scope.leftc_price_arr.push(".");
					$scope.leftc_price = Number($scope.leftc_price_arr.join(""));
					console.log($scope.leftc_price_arr, $scope.leftc_price);
					$scope.$apply();
				}
			})
			//jq绑定(end)
		}
	}
	//切换
	$scope.navTap = function(i) {
		$scope.c_id = i;
		switch(i) {
			//持仓
			case 1:
				$httpService.httpFun({
					nozzle: "depot_info",
					token: sessionStorage.getItem("token")
				}).then(function(data) {
					$scope.u_deals = data;
				});
				break;
				//挂单
			case 2:
				$httpService.httpFun({
					nozzle: "cancel_index",
					token: sessionStorage.getItem("token")
				}).then(function(data) {
					$scope.u_deals = data;
				});
				//委托
			case 3:
				$httpService.httpFun({
					nozzle: "entrust_info",
					token: sessionStorage.getItem("token")
				}).then(function(data) {
					$scope.u_deals = data;
				});
				break;
				//成交
			case 4:
				$httpService.httpFun({
					nozzle: "deal_info",
					token: sessionStorage.getItem("token")
				}).then(function(data) {
					$scope.u_deals = data;
				});
				break;
		}
	}
	$scope.navTap(1);
	//下单
	$scope.makeDeal = function(i) {
		layer.confirm('确定下单吗？', {
			title: "",
			closeBtn: 0,
			btn: ['确定', '取消'],
		}, function(index) {
			//按钮【按钮一】的回调
			switch(i) {
				case 1: //买多
					break;
				case 2: //卖空
					break;
				case 3: //平仓
					break;
			}
			layer.close(index);
		}, function(index) {
			//按钮【按钮二】的回调
			layer.close(index);
		});
	}
}]);

//系统设置
app.controller('settingCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.switchs = [0, 0, 0, 0, 0, 0];
	$scope.tips = [
		[0, 1],
		[0, 1],
		[0, 1],
		[0, 1]
	]
	$scope.li_id = 2; //下单默认价格配置
	$scope.price_type = "快速价";
	$scope.back = function() {
		javascript: history.back(-1);
	};
	//开关
	$scope.openOff = function(i) {
		if($scope.switchs[i]) {
			$scope.switchs[i] = 0
		} else {
			$scope.switchs[i] = 1
		}
	}
	//下单默认价格配置
	$scope.showModal = function() {
		$(".mask").fadeIn();
		$(".d_modal0").slideDown();
	}
	$scope.liClick = function(i, p) {
		$scope.li_id = i;
		$scope.price_type = p;
	}
	$scope.hideModal = function() {
		$(".mask").fadeOut();
		$(".d_modal0").slideUp();
	}
	//提醒方式
	$scope.showModal1 = function() {
		$(".mask").fadeIn();
		$(".d_modal1").slideDown();
	}
	$scope.change = function(i, j) {
		if($scope.tips[i][j]) {
			$scope.tips[i][j] = 0;
		} else {
			$scope.tips[i][j] = 1
		}
	}
	$scope.hideModal1 = function() {
		$(".mask").fadeOut();
		$(".d_modal1").slideUp();
	}
	//品种默认参数设置
	$scope.toLink = function() {
		$state.go('variety_set');
	}
}]);

//品种默认参数设置
app.controller('variety_setCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.c_id = "";
	$scope.l_id = 1;

	$scope.back = function() {
		javascript: history.back(-1);
	};
	$scope.navTap = function(i) {
		$scope.l_id = i;
	};
	$scope.select = function() {
		$scope.c_id = 1;
		$(".ac_modal").slideDown();
	}
	$scope.hide = function() {
		$(".ac_modal").slideUp();
	};
}]);
//云端条件单
app.controller('cloud_conditionCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.c_id = 1;

	$scope.back = function() {
		javascript: history.back(-1);
	};
	$scope.navTap = function(i) {
		$scope.c_id = i;
	}
	$scope.toAdd = function() {
		$state.go('add_condition_order');
	}
}]);
//添加条件单
app.controller('add_condition_orderCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.back = function() {
		$scope.reset();
		javascript: history.back(-1);
	};
	$scope.toTip = function() {
		$state.go("condition_risk_tip");
	}

	$scope.o_id = 1;
	$scope.list_id = 1;

	$scope.active_id = 0;
	$scope.hand_arr = [];
	$scope.hand_num = 1;
	$scope.p_type = 1;
	$scope.p_type1 = 0;
	$scope.bool = 0;
	$scope.bool1 = 0;
	$scope.bs_btn = "买";
	$scope.order_type = "开仓";

	$scope.bs_btn1 = "买";
	$scope.order_type1 = "开仓";
	$scope.time = "";

	//左
	$scope.left_price_arr = [3, 9, 1, 8];
	$scope.left_price = 3918;

	$scope.leftc_price_arr = [];
	$scope.leftc_price = "对手价";

	$scope.left_hand_arr = [];
	$scope.left_hand = 1;

	//右
	$scope.right_price_arr = [3, 9, 1, 8];
	$scope.right_price = 3918;

	$scope.rightc_price_arr = [];
	$scope.rightc_price = "对手价";

	$scope.right_hand_arr = [];
	$scope.right_hand = 1;
	0

	//初始化时间自定义picker
	$scope.initPicker = function(x, y, z) {
		var arr1 = [];
		var arr2 = [];
		var arr3 = [];

		for(var i = 0; i < 24; i++) {
			if(i < 10) {
				i = "0" + i;
			}
			arr1.push(i + "时")
		}
		for(var i = 0; i < 60; i++) {
			if(i < 10) {
				i = "0" + i;
			}
			arr2.push(i + "分")
		}
		for(var i = 0; i < 60; i++) {
			if(i < 10) {
				i = "0" + i;
			}
			arr3.push(i + "秒")
		}

		var mobileSelect2 = new MobileSelect({
			trigger: '#trigger',
			//		    title: '时间选择',
			triggerDisplayData: false,
			position: [x, y, z],
			wheels: [{
					data: arr1
				},
				{
					data: arr2
				},
				{
					data: arr3
				},
			],
			callback: function(indexArr, data) {
				data[0] = data[0].replace(/[^\d]/g, '');
				data[1] = data[1].replace(/[^\d]/g, '');
				data[2] = data[2].replace(/[^\d]/g, '');
				$scope.time = data.join(":");
				console.log($scope.time)
				$scope.$apply();
			}
		});
	}
	//	$scope.initPicker();
	//重置
	$scope.reset = function(e) {
		$scope.active_id = 0;
		$(".user_keyboard").slideUp();
		$(".d_border").removeClass("d_border1");
	}
	//买卖切换
	$scope.bsType = function() {
		if($scope.bs_btn == "买") {
			$scope.bs_btn = "卖"
		} else {
			$scope.bs_btn = "买"
		}
	}
	$scope.bsType1 = function() {
		if($scope.bs_btn1 == "买") {
			$scope.bs_btn1 = "卖"
		} else {
			$scope.bs_btn1 = "买"
		}
	}
	$scope.orderType = function() {
		if($scope.order_type == "开仓") {
			$scope.order_type = "平仓"
		} else if($scope.order_type == "平仓") {
			$scope.order_type = "平今"
		} else {
			$scope.order_type = "开仓"
		}
	}
	$scope.orderType1 = function() {
		if($scope.order_type1 == "开仓") {
			$scope.order_type1 = "平仓"
		} else if($scope.order_type1 == "平仓") {
			$scope.order_type1 = "平今"
		} else {
			$scope.order_type1 = "开仓"
		}
	}
	//价格附加
	$scope.open = function(i) {
		$scope.p_type1 = i;
	}
	//价格切换
	$scope.pType = function(i) {
		$scope.p_type = i;
	}
	//期限切换
	$scope.navTime = function(i) {
		$scope.bool = i;

	}
	$scope.navTime1 = function(i) {
		$scope.bool1 = i;

	}
	//种类 手数 价格
	$scope.select = function(i, e) {
		e.stopPropagation();
		$scope.active_id = i;
		$scope.focus_index = 0;
		//		if(i == 1) {
		var str = `<div class="user_keyboard user_keyboard1">
						<div>
							<div class="top_head1">
								<span class="f_l">从当天列表中选择合约</span>
								<img class="f_r" src="img/jianpan.png"/>
							</div>
							<div class="kb_con1">
								<span>鸡蛋1901</span>
								<span>鸡蛋1901</span>
								<span>鸡蛋1901</span>
								<span>鸡蛋1901</span>
								<span>鸡蛋1901</span>
								<span>鸡蛋1901</span>
								<span>鸡蛋1901</span>
							</div>
						</div>
					</div>`
		$(".bot_modal").html(str);
		$(".user_keyboard1").slideDown();
		$(".top_head1 img").on("click", function() {
			$scope.active_id = 0;
			$scope.$apply();
			$scope.reset();
		})
		//合约列表选择
		$(".kb_con1>span").on("click", function() {

		})

		//		}
	}

	//价格条件单--价格
	$scope.setPrice = function(e) {
		e.stopPropagation();
		$scope.focus_index = 1;
		$scope.active_id = 0;
		var str = `<div class="user_keyboard user_keyboard2">
						<div>
							<div class="top_head2">
								<div class="ov_a">					
									<span class="f_l">价格输入</span>
									<img class="f_r" src="img/jianpan.png"/>
								</div>
								<div class="ov_a">
									<span class="f_l">涨停：4090&nbsp;&nbsp;&nbsp;跌停：4090</span>
									<span class="f_r">最小变动：1</span>
								</div>
							</div>
							<div class="kb_con2">
								<table class="layui-table">
									<tbody>
										<tr>
											<td class="kb_num">1</td>
											<td class="kb_num">2</td>
											<td class="kb_num">3</td>
											<td class="td kb_add" rowspan="2">+</td>
										</tr>
										<tr>
											<td class="kb_num">4</td>
											<td class="kb_num">5</td>
											<td class="kb_num">6</td>
										</tr>
										<tr>
											<td class="kb_num">7</td>
											<td class="kb_num">8</td>
											<td class="kb_num">9</td>
											<td class="td kb_decrease" rowspan="2">-</td>
										</tr>
										<tr>
											<td class="kb_num" colspan="2">0</td>
											<td class="td kb_devare">
												<span class="iconfont icon-jianpanshanchu"></span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>`
		$(".bot_modal").html(str);
		$(".user_keyboard2").slideDown();
		$(".top_head2 img").on("click", function() {
			$scope.focus_index = 0;
			$scope.$apply();
			$scope.reset();
		})
		//jq绑定(start)
		//阻止冒泡
		$(".user_keyboard2").on("click", function(e) {
			e.stopPropagation();
		});
		$(".user_keyboard3").on("click", function(e) {
			e.stopPropagation();
		});
		//手数
		$(".kb_con2 .kb_num").on("click", function() { //点击数字
			if($scope.left_price_arr[0] == 0) {
				$scope.left_price_arr.shift();
			}
			if($scope.left_price_arr.length < 10) {
				$scope.left_price_arr.push(Number($(this).html()));
				$scope.left_price = Number($scope.left_price_arr.join(""));
				console.log($scope.left_price);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_add").on("click", function() { //点击加号
			$scope.left_price++;
			$scope.left_price_arr = $scope.left_price.toString().split("");
			console.log($scope.left_price_arr);
			$scope.$apply();
		})
		$(".kb_con2 .kb_decrease").on("click", function() { //点击减号
			if($scope.left_price) {
				$scope.left_price--;
				$scope.left_price_arr = $scope.left_price.toString().split("");
				console.log($scope.left_price_arr);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_devare").on("click", function() { //点击删除
			if($scope.left_price) {
				$scope.left_price_arr.pop();
				$scope.left_price = Number($scope.left_price_arr.join(""));
				console.log($scope.left_price);
				$scope.$apply();
			}
		})
		//jq绑定(end)
	}

	//价格条件单--对手价
	$scope.setcPrice = function(e) {
		e.stopPropagation();
		$scope.focus_index = 2;
		var str = `<div class="user_keyboard user_keyboard3">
						<div>
							<div class="top_head3">
								<div class="ov_a">					
									<span class="f_l">价格输入</span>
									<img class="f_r" src="img/jianpan.png"/>
								</div>
								<div class="ov_a">
									<span class="f_l">涨停：4090&nbsp;&nbsp;&nbsp;跌停：4090</span>
									<span class="f_r">最小变动：1</span>
								</div>
							</div>
							<div class="kb_con3 ov_a">
								<div class="kb_left f_l">
									<span>对手</span>
									<span>买一</span>
									<span>卖一</span>
									<span>超价</span>
									<span>市价</span>
								</div>
								<div class="kb_mid f_l">
									<div>
										<span class="kb_num">1</span>
										<span class="kb_num">2</span>
										<span class="kb_num">3</span>
									</div>
									<div>
										<span class="kb_num">4</span>
										<span class="kb_num">5</span>
										<span class="kb_num">6</span>
									</div>
									<div>
										<span class="kb_num">7</span>
										<span class="kb_num">8</span>
										<span class="kb_num">9</span>
									</div>
									<div>
										<span class="kb_dot">.</span>
										<span class="kb_num">0</span>
										<span class="d_icon kb_devare">
											<span class="iconfont icon-jianpanshanchu"></span>
										</span>
									</div>
								</div>
								<div class="kb_right f_l">
									<span>最新</span>
									<span class="calc kb_add">+</span>
									<span class="calc kb_decrease">-</span>
								</div>
							</div>
						</div>
					</div>`
		$(".bot_modal").html(str);
		$(".user_keyboard3").slideDown();
		$(".top_head3 img").on("click", function() {
			$scope.focus_index = 0;
			$scope.$apply();
			$scope.reset();
		})
		//jq绑定(start)
		//阻止冒泡
		$(".user_keyboard2").on("click", function(e) {
			e.stopPropagation();
		});
		$(".user_keyboard3").on("click", function(e) {
			e.stopPropagation();
		});
		//手数
		$(".kb_con3 .kb_num").on("click", function() { //点击数字
			if($scope.leftc_price_arr[0] == 0) {
				$scope.leftc_price_arr.shift();
			}
			if($scope.leftc_price_arr.length < 10) {
				$scope.leftc_price_arr.push(Number($(this).html()));
				$scope.leftc_price = Number($scope.leftc_price_arr.join(""));
				console.log($scope.leftc_price);
				$scope.$apply();
			}
		})
		$(".kb_con3 .kb_add").on("click", function() { //点击加号
			$scope.leftc_price++;
			$scope.leftc_price_arr = $scope.leftc_price.toString().split("");
			console.log($scope.leftc_price_arr);
			$scope.$apply();
		})
		$(".kb_con3 .kb_decrease").on("click", function() { //点击减号
			if($scope.leftc_price) {
				$scope.leftc_price--;
				$scope.leftc_price_arr = $scope.leftc_price.toString().split("");
				console.log($scope.leftc_price_arr);
				$scope.$apply();
			}
		})
		$(".kb_con3 .kb_devare").on("click", function() { //点击删除
			if($scope.leftc_price) {
				$scope.leftc_price_arr.pop();
				$scope.leftc_price = Number($scope.leftc_price_arr.join(""));
				console.log($scope.leftc_price);
				$scope.$apply();
			}
		})
		$(".kb_con3 .kb_dot").on("click", function() {
			if($scope.leftc_price.toString().indexOf(".") == -1) {
				$scope.leftc_price_arr.push(".");
				$scope.leftc_price = Number($scope.leftc_price_arr.join(""));
				console.log($scope.leftc_price_arr, $scope.leftc_price);
				$scope.$apply();
			}
		})
		//jq绑定(end)
	}
	//价格条件单--手数
	$scope.setHand = function(e) {
		e.stopPropagation();
		$scope.focus_index = 3;
		var str = `<div class="user_keyboard user_keyboard2">
						<div>
							<div class="top_head2">
								<div class="ov_a">					
									<span class="f_l">手数输入</span>
									<img class="f_r" src="img/jianpan.png"/>
								</div>
								<div>最大开仓手数：买0，卖0</div>
							</div>
							<div class="kb_con2">
								<table class="layui-table">
									<tbody>
										<tr>
											<td class="kb_num">1</td>
											<td class="kb_num">2</td>
											<td class="kb_num">3</td>
											<td class="td kb_add" rowspan="2">+</td>
										</tr>
										<tr>
											<td class="kb_num">4</td>
											<td class="kb_num">5</td>
											<td class="kb_num">6</td>
										</tr>
										<tr>
											<td class="kb_num">7</td>
											<td class="kb_num">8</td>
											<td class="kb_num">9</td>
											<td class="td kb_decrease" rowspan="2">-</td>
										</tr>
										<tr>
											<td class="kb_num" colspan="2">0</td>
											<td class="td kb_devare">
												<span class="iconfont icon-jianpanshanchu"></span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>`
		$(".bot_modal").html(str);
		$(".user_keyboard2").slideDown();
		$(".top_head2 img").on("click", function() {
			$scope.focus_index = 0;
			$scope.$apply();
			$scope.reset();
		})
		//jq绑定(start)
		//阻止冒泡
		$(".user_keyboard2").on("click", function(e) {
			e.stopPropagation();
		});
		$(".user_keyboard3").on("click", function(e) {
			e.stopPropagation();
		});
		//手数
		$(".kb_con2 .kb_num").on("click", function() { //点击数字
			if($scope.left_hand_arr[0] == 0) {
				$scope.left_hand_arr.shift();
			}
			if($scope.left_hand_arr.length < 5) {
				$scope.left_hand_arr.push(Number($(this).html()));
				$scope.left_hand = Number($scope.left_hand_arr.join(""));
				console.log($scope.left_hand);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_add").on("click", function() { //点击加号
			$scope.left_hand++;
			$scope.left_hand_arr = $scope.left_hand.toString().split("");
			console.log($scope.left_hand_arr);
			$scope.$apply();
		})
		$(".kb_con2 .kb_decrease").on("click", function() { //点击减号
			if($scope.left_hand) {
				$scope.left_hand--;
				$scope.left_hand_arr = $scope.left_hand.toString().split("");
				console.log($scope.left_hand_arr);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_devare").on("click", function() { //点击删除
			if($scope.left_hand) {
				$scope.left_hand_arr.pop();
				$scope.left_hand = Number($scope.left_hand_arr.join(""));
				console.log($scope.left_hand);
				$scope.$apply();
			}
		})
		//jq绑定(end)
	}

	//时间条件单--价格
	$scope.setPrice1 = function(e) {
		e.stopPropagation();
		$scope.focus_index = 1;
		var str = `<div class="user_keyboard user_keyboard2">
						<div>
							<div class="top_head2">
								<div class="ov_a">					
									<span class="f_l">价格输入</span>
									<img class="f_r" src="img/jianpan.png"/>
								</div>
								<div class="ov_a">
									<span class="f_l">涨停：4090&nbsp;&nbsp;&nbsp;跌停：4090</span>
									<span class="f_r">最小变动：1</span>
								</div>
							</div>
							<div class="kb_con2">
								<table class="layui-table">
									<tbody>
										<tr>
											<td class="kb_num">1</td>
											<td class="kb_num">2</td>
											<td class="kb_num">3</td>
											<td class="td kb_add" rowspan="2">+</td>
										</tr>
										<tr>
											<td class="kb_num">4</td>
											<td class="kb_num">5</td>
											<td class="kb_num">6</td>
										</tr>
										<tr>
											<td class="kb_num">7</td>
											<td class="kb_num">8</td>
											<td class="kb_num">9</td>
											<td class="td kb_decrease" rowspan="2">-</td>
										</tr>
										<tr>
											<td class="kb_num" colspan="2">0</td>
											<td class="td kb_devare">
												<span class="iconfont icon-jianpanshanchu"></span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>`
		$(".bot_modal").html(str);
		$(".user_keyboard2").slideDown();
		$(".top_head2 img").on("click", function() {
			$scope.focus_index = 0;
			$scope.$apply();
			$scope.reset();
		})
		//jq绑定(start)
		//阻止冒泡
		$(".user_keyboard2").on("click", function(e) {
			e.stopPropagation();
		});
		$(".user_keyboard3").on("click", function(e) {
			e.stopPropagation();
		});
		//手数
		$(".kb_con2 .kb_num").on("click", function() { //点击数字
			if($scope.right_price_arr[0] == 0) {
				$scope.right_price_arr.shift();
			}
			if($scope.right_price_arr.length < 10) {
				$scope.right_price_arr.push(Number($(this).html()));
				$scope.right_price = Number($scope.right_price_arr.join(""));
				console.log($scope.right_price);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_add").on("click", function() { //点击加号
			$scope.right_price++;
			$scope.right_price_arr = $scope.right_price.toString().split("");
			console.log($scope.right_price_arr);
			$scope.$apply();
		})
		$(".kb_con2 .kb_decrease").on("click", function() { //点击减号
			if($scope.right_price) {
				$scope.right_price--;
				$scope.right_price_arr = $scope.right_price.toString().split("");
				console.log($scope.right_price_arr);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_devare").on("click", function() { //点击删除
			if($scope.right_price) {
				$scope.right_price_arr.pop();
				$scope.right_price = Number($scope.right_price_arr.join(""));
				console.log($scope.right_price);
				$scope.$apply();
			}
		})
		//jq绑定(end)
	}

	//时间条件单--对手价
	$scope.setcPrice1 = function(e) {
		e.stopPropagation();
		$scope.focus_index = 2;
		var str = `<div class="user_keyboard user_keyboard3">
						<div>
							<div class="top_head3">
								<div class="ov_a">					
									<span class="f_l">价格输入</span>
									<img class="f_r" src="img/jianpan.png"/>
								</div>
								<div class="ov_a">
									<span class="f_l">涨停：4090&nbsp;&nbsp;&nbsp;跌停：4090</span>
									<span class="f_r">最小变动：1</span>
								</div>
							</div>
							<div class="kb_con3 ov_a">
								<div class="kb_left f_l">
									<span>对手</span>
									<span>买一</span>
									<span>卖一</span>
									<span>超价</span>
									<span>市价</span>
								</div>
								<div class="kb_mid f_l">
									<div>
										<span class="kb_num">1</span>
										<span class="kb_num">2</span>
										<span class="kb_num">3</span>
									</div>
									<div>
										<span class="kb_num">4</span>
										<span class="kb_num">5</span>
										<span class="kb_num">6</span>
									</div>
									<div>
										<span class="kb_num">7</span>
										<span class="kb_num">8</span>
										<span class="kb_num">9</span>
									</div>
									<div>
										<span class="kb_dot">.</span>
										<span class="kb_num">0</span>
										<span class="d_icon kb_devare">
											<span class="iconfont icon-jianpanshanchu"></span>
										</span>
									</div>
								</div>
								<div class="kb_right f_l">
									<span>最新</span>
									<span class="calc kb_add">+</span>
									<span class="calc kb_decrease">-</span>
								</div>
							</div>
						</div>
					</div>`
		$(".bot_modal").html(str);
		$(".user_keyboard3").slideDown();
		$(".top_head3 img").on("click", function() {
			$scope.focus_index = 0;
			$scope.$apply();
			$scope.reset();
		})
		//jq绑定(start)
		//阻止冒泡
		$(".user_keyboard2").on("click", function(e) {
			e.stopPropagation();
		});
		$(".user_keyboard3").on("click", function(e) {
			e.stopPropagation();
		});
		//手数
		$(".kb_con3 .kb_num").on("click", function() { //点击数字
			if($scope.rightc_price_arr[0] == 0) {
				$scope.rightc_price_arr.shift();
			}
			if($scope.rightc_price_arr.length < 10) {
				$scope.rightc_price_arr.push(Number($(this).html()));
				$scope.rightc_price = Number($scope.rightc_price_arr.join(""));
				console.log($scope.rightc_price);
				$scope.$apply();
			}
		})
		$(".kb_con3 .kb_add").on("click", function() { //点击加号
			$scope.rightc_price++;
			$scope.rightc_price_arr = $scope.rightc_price.toString().split("");
			console.log($scope.rightc_price_arr);
			$scope.$apply();
		})
		$(".kb_con3 .kb_decrease").on("click", function() { //点击减号
			if($scope.rightc_price) {
				$scope.rightc_price--;
				$scope.rightc_price_arr = $scope.rightc_price.toString().split("");
				console.log($scope.rightc_price_arr);
				$scope.$apply();
			}
		})
		$(".kb_con3 .kb_devare").on("click", function() { //点击删除
			if($scope.rightc_price) {
				$scope.rightc_price_arr.pop();
				$scope.rightc_price = Number($scope.rightc_price_arr.join(""));
				console.log($scope.rightc_price);
				$scope.$apply();
			}
		})
		$(".kb_con3 .kb_dot").on("click", function() {
			if($scope.rightc_price.toString().indexOf(".") == -1) {
				$scope.rightc_price_arr.push(".");
				$scope.rightc_price = Number($scope.rightc_price_arr.join(""));
				console.log($scope.rightc_price_arr, $scope.rightc_price);
				$scope.$apply();
			}
		})
		//jq绑定(end)
	}
	//时间条件单--手数
	$scope.setHand1 = function(e) {
		e.stopPropagation();
		$scope.focus_index = 3;
		var str = `<div class="user_keyboard user_keyboard2">
						<div>
							<div class="top_head2">
								<div class="ov_a">					
									<span class="f_l">手数输入</span>
									<img class="f_r" src="img/jianpan.png"/>
								</div>
								<div>最大开仓手数：买0，卖0</div>
							</div>
							<div class="kb_con2">
								<table class="layui-table">
									<tbody>
										<tr>
											<td class="kb_num">1</td>
											<td class="kb_num">2</td>
											<td class="kb_num">3</td>
											<td class="td kb_add" rowspan="2">+</td>
										</tr>
										<tr>
											<td class="kb_num">4</td>
											<td class="kb_num">5</td>
											<td class="kb_num">6</td>
										</tr>
										<tr>
											<td class="kb_num">7</td>
											<td class="kb_num">8</td>
											<td class="kb_num">9</td>
											<td class="td kb_decrease" rowspan="2">-</td>
										</tr>
										<tr>
											<td class="kb_num" colspan="2">0</td>
											<td class="td kb_devare">
												<span class="iconfont icon-jianpanshanchu"></span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>`
		$(".bot_modal").html(str);
		$(".user_keyboard2").slideDown();
		$(".top_head2 img").on("click", function() {
			$scope.focus_index = 0;
			$scope.$apply();
			$scope.reset();
		})
		//jq绑定(start)
		//阻止冒泡
		$(".user_keyboard2").on("click", function(e) {
			e.stopPropagation();
		});
		$(".user_keyboard3").on("click", function(e) {
			e.stopPropagation();
		});
		//手数
		$(".kb_con2 .kb_num").on("click", function() { //点击数字
			if($scope.right_hand_arr[0] == 0) {
				$scope.right_hand_arr.shift();
			}
			if($scope.right_hand_arr.length < 5) {
				$scope.right_hand_arr.push(Number($(this).html()));
				$scope.right_hand = Number($scope.right_hand_arr.join(""));
				console.log($scope.right_hand);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_add").on("click", function() { //点击加号
			$scope.right_hand++;
			$scope.right_hand_arr = $scope.right_hand.toString().split("");
			console.log($scope.right_hand_arr);
			$scope.$apply();
		})
		$(".kb_con2 .kb_decrease").on("click", function() { //点击减号
			if($scope.right_hand) {
				$scope.right_hand--;
				$scope.right_hand_arr = $scope.right_hand.toString().split("");
				console.log($scope.right_hand_arr);
				$scope.$apply();
			}
		})
		$(".kb_con2 .kb_devare").on("click", function() { //点击删除
			if($scope.right_hand) {
				$scope.right_hand_arr.pop();
				$scope.right_hand = Number($scope.right_hand_arr.join(""));
				console.log($scope.right_hand);
				$scope.$apply();
			}
		})
		//jq绑定(end)
	}

	//添加按钮事件
	$scope.addFun = function(i) {
		if(i) {
			$("#top_info").fadeIn("fast", function() {
				setTimeout(function() {
					$("#top_info").hide();
				}, 1500)
			});
		} else {
			$("#top_info").fadeIn("fast", function() {
				setTimeout(function() {
					$("#top_info").hide();
				}, 1500)
			});
		}
	}

	//切换
	$scope.navTap = function(i) {
		$scope.o_id = i;
		if($scope.o_id == 2) {
			var h = new Date().getHours();
			var m = new Date().getMinutes();
			var s = new Date().getSeconds();
			if(h < 10) {
				h = "0" + h;
			}
			if(m < 10) {
				m = "0" + m;
			}
			if(s < 10) {
				s = "0" + s;
			}
			$scope.time = h + ":" + m + ":" + s;
			$scope.initPicker(Number(h), Number(m), Number(s));
		}
	}

	//切换
	$scope.navTap1 = function(i) {
		$scope.list_id = i;
	}
	//下单
	$scope.makeDeal = function() {
		layer.confirm('确定下单吗？', {
			title: "",
			closeBtn: 0,
			btn: ['确定', '取消'],
		}, function(index) {
			//按钮【按钮一】的回调
			layer.close(index);
		}, function(index) {
			//按钮【按钮二】的回调
			layer.close(index);
		});
	}
}]);

//云端止损止盈单
app.controller('cloud_stop_lossCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory) {
	$scope.c_id = 1;
	$scope.refreshIf = 0;

	$scope.back = function() {
		javascript: history.back(-1);
	};
	$scope.refresh = function() {
		$scope.refreshIf = 1;
		$(".loader").show();
	};
}]);

//历史账单查询
app.controller('order_historyCtr', ['$ionicLoading', '$httpService', '$stateParams', '$rootScope', '$httpService', '$scope', '$http', '$state', '$ionicHistory', 'dateSelect', function($ionicLoading, $httpService, $stateParams, $rootScope, $httpService, $scope, $http, $state, $ionicHistory, dateSelect) {
	$scope.back = function() {
		javascript: history.back(-1);
	};

	//时间
	$scope.inputData = {
		startDate: '选择日期',
		//		endDate: '选择日期'
	};
	dateSelect.init({
		d1: '#date1',
		//		d2: '#date2'
	});
}]);