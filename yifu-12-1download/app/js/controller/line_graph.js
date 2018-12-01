$.ajax({
    url: "/Line_Graph/index",
    type: 'POST',
    success: function (data) {
        k_line(data)
    }
});

function k_line(data) {
    data = splitData(data);
    var myChart = echarts.init(document.getElementById("contain"));
    var option = {
        animation: false,
        backgroundColor: '#181B2A',
        tooltip: {
            show: true,
            trigger: 'axis',
            axisPointer: {
                type: 'cross',
                lineStyle: {type: 'dashed', color: '#9394a3'},
                crossStyle: {type: 'dashed', color: '#9394a3'},
                label: {show: true, precision: 2, backgroundColor: '#585858'}
            },
            backgroundColor: 'transparent',
            showContent: true,
            position: [0, 0],
            formatter: function (params) {
                if (params[0].seriesIndex == 0) {
                    return '时间：' + params[0].name + ' 开：' + params[0].data[4] + ' 高：' + params[0].data[3] + ' 低：' + params[0].data[2] + ' 收：' + params[0].data[1] + ' 量：' + params[0].data[5] +
                        '<br/>' + params[1].seriesName + '：' + params[1].value + ' ' + params[3].seriesName + '：' + params[3].value + ' ' + params[2].seriesName + '：' + params[2].value;
                } else if (params[0].seriesIndex == 4) {
                    return '时间：' + params[1].name + ' 开：' + params[1].data[4] + ' 高：' + params[1].data[3] + ' 低：' + params[1].data[2] + ' 收：' + params[1].data[1] + ' 量：' + params[1].data[5] +
                        '<br/>' + params[2].seriesName + '：' + params[2].value + ' ' + params[4].seriesName + '：' + params[4].value + ' ' + params[3].seriesName + '：' + params[3].value;
                }
            },
            crossStyle: {opacity: 1}
        },
        axisPointer: {
            link: {xAxisIndex: 'all'}
        },
        title: [],
        grid: [
            {left: 0, right: '4%', top: 0, height: 280},
            {left: 0, right: '4%', top: 290, height: 80},
            {left: 0, right: '4%', top: 380, height: 80}
        ],
        xAxis: [
            {
                type: 'category',
                data: data.data,
                scale: true,
                boundaryGap: true,
                axisTick: {show: false},
                axisLabel: {show: false},
                axisLine: {show: true, lineStyle: {color: '#61688A'}},
                splitLine: {show: true, lineStyle: {color: '#1F2943'}},
                splitArea: {show: false},
                axisPointer: {
                    label : {show:false}
                }
            },
            {
                type: 'category',
                gridIndex: 1,
                data: data.data,
                scale: true,
                boundaryGap: true,
                axisTick: {show: false},
                axisLabel: {show: false},
                axisLine: {show: true, lineStyle: {color: '#61688A'}},
                splitLine: {show: true, lineStyle: {color: '#1F2943'}},
                splitArea: {show: false},
                axisPointer: {
                    label : {show:false}
                }
            },
            {
                type: 'category',
                gridIndex: 2,
                data: data.data,
                axisTick: {show: true},
                axisLabel: {show: true},
                axisLine: {show: true, lineStyle: {color: '#61688A'}},
                splitLine: {show: true, lineStyle: {color: '#1F2943'}},
                splitArea: {show: false}
            }
        ],
        yAxis: [
            {
                scale: true,
                position: 'right',
                boundaryGap: true,
                axisTick: {show: false},
                axisLabel: {
                    show: true,
                    verticalAlign:'top'
                },
                axisLine: {
                    show: true,
                    lineStyle: {color: '#61688A'}
                },
                splitLine: {show: true, lineStyle: {color: '#1F2943'}},
                splitArea: {show: false}
            },
            {
                gridIndex: 1,
                splitNumber: 4,
                scale: true,
                position: 'right',
                boundaryGap: true,
                axisTick: {show: false},
                axisLabel: {
                    show: true,
                    verticalAlign:'top'
                },
                axisLine: {
                    show: true,
                    lineStyle: {color: '#61688A'}
                },
                splitLine: {show: true, lineStyle: {color: '#1F2943'}},
                splitArea: {show: false}
            },
            {
                gridIndex: 2,
                splitNumber: 3,
                position: 'right',
                axisTick: {show: false},
                axisLabel: {
                    show: true,
                    verticalAlign:'top'
                },
                axisLine: {
                    show: true,
                    lineStyle: {color: '#61688A'}
                },
                splitLine: {show: true, lineStyle: {color: '#1F2943'}},
                splitArea: {show: false}
            }
        ],
        dataZoom: [
            {type: 'inside', xAxisIndex: [0, 1, 2],  start: 100, end: 96}
        ],
        series: [
            {
                name: '555',
                type: 'candlestick',
                data: data.values,
                barMaxWidth: 10,
                itemStyle: {
                    normal: {
                        color: '#c23531',
                        color0: '#589065',
                        borderColor: '#c23531',
                        borderColor0: '#589065'
                    }
                },
                markPoint: {data: [{name: 'XX标点'}]},
                markLine: {silent: true, data: [{yAxis: 2222}]}
            },
            {
                name: 'MA5',
                type: 'line',
                data: calculateMA(data, 5),
                smooth: true,
                symbol: 'none',
                hoverAnimation: false,
                showSymbol: false,
                showAllSymbol: false,
                lineStyle: {color: '#8054a5'}
            },
            {
                name: 'MA10',
                type: 'line',
                data: calculateMA(data, 10),
                smooth: true,
                symbol: 'none',
                hoverAnimation: false,
                showSymbol: false,
                showAllSymbol: false,
                lineStyle: {color: '#84AAD5'}
            },
            {
                name: 'MA20',
                type: 'line',
                data: calculateMA(data, 20),
                smooth: true,
                symbol: 'none',
                hoverAnimation: false,
                showSymbol: false,
                showAllSymbol: false,
                lineStyle: {color: '#3F7D4f'}
            },
            {
                name: 'MA30',
                type: 'line',
                data: calculateMA(data, 30),
                smooth: true,
                symbol: 'none',
                hoverAnimation: false,
                showSymbol: false,
                showAllSymbol: false,
                lineStyle: {color: '#7F2069'}
            },
            {
                name: '交易量',
                type: 'bar',
                xAxisIndex: 1,
                yAxisIndex: 1,
                barMaxWidth: 10,
                data: data.numbers,
                itemStyle: {
                    color: function (params) {
                        return data.values[params.dataIndex][1] > data.values[params.dataIndex][0] ? '#c23531' : '#589065'
                    }
                }
            },
            {
                name: 'MACD',
                type: 'bar',
                xAxisIndex: 2,
                yAxisIndex: 2,
                data: data.macd,
                itemStyle: {
                    color: function (params) {
                        return params.data >= 0 ? '#c23531' : '#589065';
                    }
                }
            },
            {
                name: 'DIF',
                type: 'line',
                xAxisIndex: 2,
                yAxisIndex: 2,
                data: data.dif,
                smooth: true,
                symbol: 'none',
                hoverAnimation: false,
                showSymbol: false,
                showAllSymbol: false,
                lineStyle: {color: '#3F7D4f'}
            },
            {
                name: 'DEA',
                type: 'line',
                xAxisIndex: 2,
                yAxisIndex: 2,
                data: data.dea,
                smooth: true,
                symbol: 'none',
                hoverAnimation: false,
                showSymbol: false,
                showAllSymbol: false,
                lineStyle: {color: '#FFF'}
            }
        ]
    };
    myChart.setOption(option, true);
}

function splitData(rawData) {
    var data = [], values = [], macd = [], dif = [], dea = [], numbers = [], times = [];
    for (var i = 0; i < rawData.length; i++) {
        data.push(rawData[i].splice(0, 1)[0]);
		//开高低收
        values.push(rawData[i]);
        numbers.push(rawData[i][4]);
        macd.push(rawData[i][5]);
        dif.push(rawData[i][6]);
        dea.push(rawData[i][7]);
    }
    return {data: data, values: values, numbers: numbers, macd: macd, dif: dif, dea: dea, times: times};
}

function calculateMA(data, dayCount) {
    var result = [];
    for (var i = 0, len = data.values.length; i < len; i++) {
        if (i < dayCount) {
            result.push('-');
            continue;
        }
        var sum = 0;
        for (var j = 0; j < dayCount; j++) {
            sum += data.values[i - j][1];
        }
        result.push(parseFloat(sum / dayCount).toFixed(2));
    }
    return result;
}