//数据模型 time0 open1 close2 min3 max4 vol5 tag6 macd7 dif8 dea9
//['2015-10-19',18.56,18.25,18.19,18.56,55.00,0,-1,0.0['0.00,0.08,0.09] 
//function build_timeask(short, type) {
//	$.ajax({
//		url: "http://dt.jctytech.com/stock.php?u=jurunjob&symbol=" + short + "&type=kline&line=" + type + "&num=480&sort=Date%20desc",
//		dataType: 'json',
//		type: 'GET',
//		crossDomain: true,
//		async: false
//	}).done(function(res) {
//		var res = res.reverse();
//		var time_data = buildAllTimeData(res);
//		var time_line_data = splitData(time_data);
//		build_timeline(time_line_data);
//	});
//};
function build_timeask(code){
	$.ajax({
		cache: true,
		url: `https://stock2.finance.sina.com.cn/futures/api/jsonp.php/var t1nf_${code}=/InnerFuturesNewService.getMinLine?symbol=${code}`,
		type: 'GET',
		dataType: 'script',
		success: function() {
			let res = eval("t1nf_"+code);
//			console.log(res);
//			var time_data = buildAllTimeData(res);
			var time_line_data = splitData(res);
//			console.log(time_line_data);
			build_timeline(time_line_data);
		}
	});
}
//时间戳转换成日期
//function timesToTime(timestamp) {
//	var date = new Date(timestamp * 1000); //时间戳为10位需*1000，时间戳为13位的话不需乘1000
//	var Y = date.getFullYear() + '/';
//	var M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '/';
//	var D = date.getDate() + '/';
//	if(date.getHours() < 10) {
//		var h = "0" + date.getHours() + ':';
//	} else {
//		var h = date.getHours() + ':';
//	}
//	if(date.getMinutes() < 10) {
//		var m = "0" + date.getMinutes() + ':';
//	} else {
//		var m = date.getMinutes() + '';
//	}
//	var s = date.getSeconds();
//	if(s == 0) {
//		s = "00";
//	}
//	return h + m;
//};
//创建全部数据的数组
//function buildAllTimeData(res) {
//	var all_data = [];
//	for(var i = 15; i < res.length; i++) {
////		var time = timesToTime(res[i].Date);
//		var time = res[i][0];
//		var arr = [time, res[i].Open, res[i].Close, res[i].Low, res[i].High, res[i].Volume];
//		all_data.push(arr);
//	}
//	return all_data;
//};
//数组处理
function splitData(rawData) {
	var times = [];
	var price = [];
	var vols = [];
	var datas = [];
	var ave = [];
	for(var i = 15; i < rawData.length; i++) {
		datas.push(rawData[i]);
		price.push(parseFloat(rawData[i][1]));
		vols.push(parseInt(rawData[i][3]));
		ave.push(parseFloat(rawData[i][2]));
		times.push(rawData[i].splice(0, 1)[0]);
	}
	return {
		datas: datas,
		times: times,
		price: price,
		ave: ave,
		vols: vols
	};
};

//MA计算公式
function calculateMA(dayCount, data) {
	var result = [];
	for(var i = 0, len = data.length; i < len; i++) {
		if(i < dayCount - 1) {
			result.push('-');
			continue;
		}
		var sum = 0;
		for(var j = 0; j < dayCount; j++) {
			sum += data[i - j];
		}
		result.push((sum / dayCount).toFixed(2));
	}
	return result;
};

function build_timeline(data) {
	var option = {
		animation: false,
		backgroundColor: "#000",
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
//			backgroundColor: 'transparent',
			showContent: true,
			position: [0, 0],
			formatter: function(params) {
				for(var i = 0; i < params.length; i++) {
					if(params[i].seriesIndex == 0) {
						//价格折线图
						var time = params[i].name;
						var price = params[i].value;
					} else if(params[i].seriesIndex == 1) {
						//价格 MA5
						var price_ma5 = params[i].value;
					} else if(params[i].seriesIndex == 2) {
						//价格bar 销量 柱状图
						var vols = params[i].value;
					} else if(params[i].seriesIndex == 3) {
						//销量MA5
						var vols_ma5 = params[i].value;
					} else if(params[i].seriesIndex == 4) {
						//销量MA10
						var vols_ma10 = params[i].value;
					}
				}
				return "时间:" + time + "&nbsp;&nbsp;价格:" + price + "&nbsp;&nbsp;MA5:" + price_ma5 + "<br>" +
					"vols:" + vols + "&nbsp;&nbsp;MA5:" + vols_ma5 + "&nbsp;&nbsp;MA10:" + vols_ma10;
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
			height: '66%',
			top: '10',
		}, {
			left: '0',
			right: '0',
			top: '66%',
			height: '28%'
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
				lineStyle:{
					color:"rgba(255,0,0,.3)",
					type:"dashed"
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
			axisLine: {
				show: true,
				lineStyle: {
					color: 'red'
				}
			},
			axisLabel: {
				show: true,

			},
			splitLine: {
				show: true,
				lineStyle:{
					color:"rgba(255,0,0,.3)",
					type:"dashed"
				}
			},
		}],
		yAxis: [{
			scale: true,
			position: 'left',
			boundaryGap: true,
			splitArea: {
				show: false
			},
			splitLine: {
				show: true,
				lineStyle:{
					color:"rgba(255,0,0,.3)",
					type:"dashed"
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
				lineStyle:{
					color:"rgba(255,0,0,.3)",
					type:"dashed"
				}
			},
			axisLabel: {
				show: true
			}
		}],
		dataZoom: [{
			type: 'inside',
			xAxisIndex: [0, 1],
			start:0,
			end: 100
		}],
		series: [{
			name: 'price',
			type: 'line',
			smooth: true,
			data: data.price,
			hoverAnimation: false,
			showSymbol: false,
			showAllSymbol: false,
			itemStyle: {
				normal: {
					color: "#ccc"	
				}
			}
		}, {
			name: 'MA5',
			type: 'line',
			data: calculateMA(5, data.price),
			smooth: true,
			symbol: 'none',
			hoverAnimation: false,
			showSymbol: false,
			showAllSymbol: false,
			itemStyle: {
				normal: {
					color: "#FF9800"	
				}
			}
		}, {
			name: 'Volumn',
			type: 'bar',
			xAxisIndex: 1,
			yAxisIndex: 1,
			data: data.vols,
			itemStyle: {
				normal: {
					color: function(params) {
						var colorList;
						if(params.dataIndex != 0){
							if(data.datas[params.dataIndex][0] > data.datas[params.dataIndex-1][0]) {
								colorList = '#ef232a';
							} else {
								colorList = '#14b143';
							}
						}
						return colorList;
					},
				}
			}
		}, {
			name: 'MA5vols',
			type: 'line',
			symbol: 'none',
			hoverAnimation: false,
			showSymbol: false,
			showAllSymbol: false,
			xAxisIndex: 1,
			yAxisIndex: 1,
			data: calculateMA(5, data.vols)
		}, {
			name: 'MA10vols',
			type: 'line',
			symbol: 'none',
			hoverAnimation: false,
			showSymbol: false,
			showAllSymbol: false,
			xAxisIndex: 1,
			yAxisIndex: 1,
			data: calculateMA(10, data.vols)
		}]
	};
	var myChart = echarts.init(document.getElementById('main'));
	myChart.clear(option);
	myChart.setOption(option, true);
};