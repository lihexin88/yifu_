<?php if (!defined('THINK_PATH')) exit(); /*a:8:{s:84:"/www/web/test/yifu/admin/public/../application/index/view/transaction/parameter.html";i:1540543918;s:64:"/www/web/test/yifu/admin/application/index/view/public/meta.html";i:1529999324;s:64:"/www/web/test/yifu/admin/application/index/view/public/link.html";i:1529999332;s:66:"/www/web/test/yifu/admin/application/index/view/public/header.html";i:1540460820;s:64:"/www/web/test/yifu/admin/application/index/view/public/left.html";i:1529999338;s:71:"/www/web/test/yifu/admin/application/index/view/public/content_top.html";i:1529999370;s:72:"/www/web/test/yifu/admin/application/index/view/public/content_foot.html";i:1529999376;s:64:"/www/web/test/yifu/admin/application/index/view/public/foot.html";i:1529999360;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
<meta HTTP-EQUIV="X-UA-Compatible" content="IE=edge">
<title>后台管理系统</title>
<meta name="description" content="">
<meta name="keywords" content="index">
<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
<meta name="renderer" content="webkit">
<meta HTTP-EQUIV="Cache-Control" content="no-siteapp" />
<!--<link rel="icon" type="image/png" href="/static/i/favicon.png">-->
<!--<link rel="apple-touch-icon-precomposed" href="/static/i/app-icon72x72@2x.png">-->
<meta name="apple-mobile-web-app-title" content="Amaze UI" />
    <link rel="stylesheet" href="/static/css/amazeui.min.css"/>
<link rel="stylesheet" href="/static/css/amazeui.datatables.min.css"/>
<link rel="stylesheet" href="/static/css/app.css">
    <style type="text/css">
        .am-form-group {
            width: 49%;
            display: inline-block;
        }
        .am-form-group span {
            min-width: 180px;
            text-align: right;
            display: inline-block;
        }
    </style>
</head>
<body data-type="index" class="theme-white">
<div class="am-g tpl-g">
    <header>
    <div class="am-fl tpl-header-logo">
        <a href="#">
            <h1 style="margin: 0;line-height: 40px">后台管理系统</h1>
        </a>
    </div>
    <!-- 右侧内容 -->
    <div class="tpl-header-fluid">
        <!-- 侧边切换 -->
        <div class="am-fl tpl-header-switch-button am-icon-list">
            <span></span>
        </div>
        <!-- 其它功能-->
        <div class="am-fr tpl-header-navbar">
            <ul>
                <!-- 欢迎语 -->
                <li class="am-text-sm tpl-header-navbar-welcome">
                    <a href="#">欢迎你, <span>管理员</span> </a>
                </li>
                <!-- 欢迎语 -->
                <li class="am-text-sm tpl-header-navbar-welcome">
                    <a href="<?php echo url('home/password'); ?>">修改密码</a>
                </li>
                <!-- 退出 -->
                <li class="am-text-sm">
                    <a href="../system/logout">
                        <span class="am-icon-sign-out"></span> 退出
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
    <div class="left-sidebar">
    <ul class="sidebar-nav">
        <li class="sidebar-nav-link">
            <a href="../home/index.html">
                <i class="am-icon-home sidebar-nav-link-logo"></i> 首页
            </a>
        </li>
        <?php if(is_array($left) || $left instanceof \think\Collection || $left instanceof \think\Paginator): $i = 0; $__LIST__ = $left;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <li class="sidebar-nav-link">
                <a href="#" class="sidebar-nav-sub-title">
                    <i class="<?php echo $vo['name']['class']; ?> sidebar-nav-link-logo"></i> <?php echo $vo['name']['name']; ?>
                    <span class="am-icon-chevron-down am-fr am-margin-right-sm sidebar-nav-sub-ico"></span>
                </a>
                <?php if(is_array($vo['rela']) || $vo['rela'] instanceof \think\Collection || $vo['rela'] instanceof \think\Paginator): $k = 0; $__LIST__ = $vo['rela'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>
                    <ul class="sidebar-nav sidebar-nav-sub">
                        <li>
                            <a href="<?php echo $v['url']; ?>?a=<?php echo $i; ?>&b=<?php echo $k-1; ?>">
                                <span class="am-icon-angle-right sidebar-nav-link-logo"></span> <?php echo $v['name']; ?>
                            </a>
                        </li>
                    </ul>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </li>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
    <div class="tpl-content-wrapper">
    <div class="am-cf">
        <div class="row">
            <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
    <ol class="am-breadcrumb">
        <li><a href="../home/index.html" class="am-icon-home">首页</a></li>
        <li><a href="#">参数管理</a></li>
        <li class="am-active">交易参数设置</li>
    </ol>
     <form class="am-cf" id="form" onsubmit="return false">
    <div class="widget am-cf">
        <hr data-am-widget="divider" class="am-divider am-divider-default"/>
        <div class="am-panel am-panel-primary">
                <div class="am-panel-hd">交易参数设置</div>
                <br>
               
                <div class="am-form-group">
                    <span class="s1">下单前确认：</span>
                    <label class="am-form-label">
                        <select name="istrue_placeorder" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['istrue_placeorder']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">确认</option>
                            <option <?php echo $list['istrue_placeorder']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">不确认</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">开仓默认手数：</span>
                    <label class="am-form-label">
                        <input type="text" id="open_transaction_number" class="am-form-field am-input-sm" name="open_transaction_number" value="<?php echo $list['open_transaction_number']; ?>" />
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">报单最大手数：　</span>
                    <label class="am-form-label">
                        <input type="text" id="customs_max_number" class="am-form-field am-input-sm" name="customs_max_number" value="<?php echo $list['customs_max_number']; ?>" />
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">平仓默认数量：　</span>
                    <label class="am-form-label">
                        <select name="close_number" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['close_number']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">可平持仓量</option>
                            <option <?php echo $list['close_number']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">开仓默认量</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">选择合约焦点位置：</span>
                    <label class="am-form-label">
                        <select name="select_order_focus" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['select_order_focus']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">合约</option>
                            <option <?php echo $list['select_order_focus']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">价格</option>
                            <option <?php echo $list['select_order_focus']==3?'selected':''; ?> value="3" class="am-form-field am-input-sm">手数</option>
                            <option <?php echo $list['select_order_focus']==4?'selected':''; ?> value="4" class="am-form-field am-input-sm">不处理</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">下单后焦点位置：</span>
                    <label class="am-form-label">
                        <select name="place_order_focus" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['place_order_focus']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">合约</option>
                            <option <?php echo $list['place_order_focus']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">价格</option>
                            <option <?php echo $list['place_order_focus']==3?'selected':''; ?> value="3" class="am-form-field am-input-sm">手数</option>
                            <option <?php echo $list['place_order_focus']==4?'selected':''; ?> value="4" class="am-form-field am-input-sm">不处理</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">下单后清空：    </span>
                    <label class="am-form-label">
                        <select name="place_order_close" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['place_order_close']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">清空所有</option>
                            <option <?php echo $list['place_order_close']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">清空价格</option>
                            <option <?php echo $list['place_order_close']==3?'selected':''; ?> value="3" class="am-form-field am-input-sm">清空手数</option>
                            <option <?php echo $list['place_order_close']==4?'selected':''; ?> value="4" class="am-form-field am-input-sm">清空价格和手数</option>
                            <option <?php echo $list['place_order_close']==4?'selected':''; ?> value="4" class="am-form-field am-input-sm">不清空</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">单击行情买卖方向：    </span>
                    <label class="am-form-label">
                        <select name="click_market_direction" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['click_market_direction']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">反向</option>
                            <option <?php echo $list['click_market_direction']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">同向</option>
                            <option <?php echo $list['click_market_direction']==3?'selected':''; ?> value="3" class="am-form-field am-input-sm">按ctrl临时取反</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">合约切换开平方向：    </span>
                    <label class="am-form-label">
                        <select name="order_cut_kaiping_direction" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['order_cut_kaiping_direction']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">总是开仓</option>
                            <option <?php echo $list['order_cut_kaiping_direction']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">自动开平</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">合约切换下单价格类型：    </span>
                    <label class="am-form-label">
                        <select name="order_cut_market_type" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['order_cut_market_type']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">限价</option>
                            <option <?php echo $list['order_cut_market_type']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">最新价</option>
                            <option <?php echo $list['order_cut_market_type']==3?'selected':''; ?> value="1" class="am-form-field am-input-sm">对手价</option>
                            <option <?php echo $list['order_cut_market_type']==4?'selected':''; ?> value="2" class="am-form-field am-input-sm">挂单价</option>
                            <option <?php echo $list['order_cut_market_type']==5?'selected':''; ?> value="2" class="am-form-field am-input-sm">快速价</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">列表操作前确认：    </span>
                    <label class="am-form-label">
                        <select name="list_operation_is_true" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['list_operation_is_true']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">确认</option>
                            <option <?php echo $list['list_operation_is_true']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">不确认</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">双击挂单撤单：    </span>
                    <label class="am-form-label">
                        <select name="double_click_order" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['double_click_order']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">确认</option>
                            <option <?php echo $list['double_click_order']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">不确认</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">双击持仓平仓：    </span>
                    <label class="am-form-label">
                        <select name="double_click_open_close" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['double_click_open_close']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">确认</option>
                            <option <?php echo $list['double_click_open_close']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">不确认</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">双击持仓平仓下单价格：    </span>
                    <label class="am-form-label">
                        <select name="double_open_price" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['double_open_price']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">对手价</option>
                            <option <?php echo $list['double_open_price']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">快速成交价</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">双击持仓平仓下单手数：    </span>
                    <label class="am-form-label">
                        <select name="double_open_num" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['double_open_num']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">可平持仓量</option>
                            <option <?php echo $list['double_open_num']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">开仓默认量</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快速反手：    </span>
                    <label class="am-form-label">
                        <select name="speediness_backhand" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['speediness_backhand']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">确认</option>
                            <option <?php echo $list['speediness_backhand']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">不确认</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快速反手下单价格：    </span>
                    <label class="am-form-label">
                        <select name="speediness_backhand_open_price" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['speediness_backhand_open_price']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">对手价</option>
                            <option <?php echo $list['speediness_backhand_open_price']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">快速成交价</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快速锁仓：    </span>
                    <label class="am-form-label">
                        <select name="speediness_locked_position" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['speediness_locked_position']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">确认</option>
                            <option <?php echo $list['speediness_locked_position']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">不确认</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快速锁仓下单价格：    </span>
                    <label class="am-form-label">
                        <select name="speediness_locked_open_price" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['speediness_locked_open_price']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">对手价</option>
                            <option <?php echo $list['speediness_locked_open_price']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">快速成交价</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快捷操作锁定下单板：    </span>
                    <label class="am-form-label">
                        <select name="shortcut_operation" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['shortcut_operation']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">是</option>
                            <option <?php echo $list['shortcut_operation']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">否</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快捷操作前确认：    </span>
                    <label class="am-form-label">
                        <select name="shortcut_operation_is_sure" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['shortcut_operation_is_sure']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">是</option>
                            <option <?php echo $list['shortcut_operation_is_sure']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">否</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快捷操作买开：　</span>
                    <label class="am-form-label">
                        <input type="text" id="shortcut_buy_open" class="am-form-field am-input-sm" name="shortcut_buy_open" value="<?php echo $list['shortcut_buy_open']; ?>" />
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快捷操作卖开：　</span>
                    <label class="am-form-label">
                        <input type="text" id="shortcut_sell_open" class="am-form-field am-input-sm" name="shortcut_sell_open" value="<?php echo $list['shortcut_sell_open']; ?>" />
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快捷操作买平：　</span>
                    <label class="am-form-label">
                        <input type="text" id="shortcut_buy_close" class="am-form-field am-input-sm" name="shortcut_buy_close" value="<?php echo $list['shortcut_buy_close']; ?>" />
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快捷操作卖平：　</span>
                    <label class="am-form-label">
                        <input type="text" id="shortcut_sell_close" class="am-form-field am-input-sm" name="shortcut_sell_close" value="<?php echo $list['shortcut_sell_close']; ?>" />
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快捷操作撤单：　</span>
                    <label class="am-form-label">
                        <input type="text" id="shortcut_operation_cancellations" class="am-form-field am-input-sm" name="shortcut_operation_cancellations" value="<?php echo $list['shortcut_operation_cancellations']; ?>" />
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快捷操作清仓：　</span>
                    <label class="am-form-label">
                        <input type="text" id="shortcut_operation_inventory" class="am-form-field am-input-sm" name="shortcut_operation_inventory" value="<?php echo $list['shortcut_operation_inventory']; ?>" />
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快捷操作买开价：    </span>
                    <label class="am-form-label">
                        <select name="shortcut_operation_buy_open" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['shortcut_operation_buy_open']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">最新价</option>
                            <option <?php echo $list['shortcut_operation_buy_open']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">对手价</option>
                            <option <?php echo $list['shortcut_operation_buy_open']==3?'selected':''; ?> value="3" class="am-form-field am-input-sm">挂单价</option>
                            <option <?php echo $list['shortcut_operation_buy_open']==4?'selected':''; ?> value="4" class="am-form-field am-input-sm">快速价</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快捷操作卖开价：    </span>
                    <label class="am-form-label">
                        <select name="shortcut_operation_sell_open" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['shortcut_operation_sell_open']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">最新价</option>
                            <option <?php echo $list['shortcut_operation_sell_open']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">对手价</option>
                            <option <?php echo $list['shortcut_operation_sell_open']==3?'selected':''; ?> value="3" class="am-form-field am-input-sm">挂单价</option>
                            <option <?php echo $list['shortcut_operation_sell_open']==4?'selected':''; ?> value="4" class="am-form-field am-input-sm">快速价</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快捷操作买平价：    </span>
                    <label class="am-form-label">
                        <select name="shortcut_operation_buy_close" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['shortcut_operation_buy_close']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">最新价</option>
                            <option <?php echo $list['shortcut_operation_buy_close']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">对手价</option>
                            <option <?php echo $list['shortcut_operation_buy_close']==3?'selected':''; ?> value="3" class="am-form-field am-input-sm">挂单价</option>
                            <option <?php echo $list['shortcut_operation_buy_close']==4?'selected':''; ?> value="4" class="am-form-field am-input-sm">快速价</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">快捷操作卖平价：    </span>
                    <label class="am-form-label">
                        <select name="shortcut_operation_sell_close" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['shortcut_operation_sell_close']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">最新价</option>
                            <option <?php echo $list['shortcut_operation_sell_close']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">对手价</option>
                            <option <?php echo $list['shortcut_operation_sell_close']==3?'selected':''; ?> value="3" class="am-form-field am-input-sm">挂单价</option>
                            <option <?php echo $list['shortcut_operation_sell_close']==4?'selected':''; ?> value="4" class="am-form-field am-input-sm">快速价</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">成交提示消息提示：    </span>
                    <label class="am-form-label">
                        <select name="message_alert_bargain" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['message_alert_bargain']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">不提示</option>
                            <option <?php echo $list['message_alert_bargain']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">窗口提示</option>
                            <option <?php echo $list['message_alert_bargain']==3?'selected':''; ?> value="3" class="am-form-field am-input-sm">声音提示</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">下单提示：    </span>
                    <label class="am-form-label">
                        <select name="message_alert_buy" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['message_alert_buy']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">不提示</option>
                            <option <?php echo $list['message_alert_buy']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">窗口提示</option>
                            <option <?php echo $list['message_alert_buy']==3?'selected':''; ?> value="3" class="am-form-field am-input-sm">声音提示</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">撤单提示：    </span>
                    <label class="am-form-label">
                        <select name="message_alert_cancellations" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['message_alert_cancellations']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">不提示</option>
                            <option <?php echo $list['message_alert_cancellations']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">窗口提示</option>
                            <option <?php echo $list['message_alert_cancellations']==3?'selected':''; ?> value="3" class="am-form-field am-input-sm">声音提示</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">条件单提示：    </span>
                    <label class="am-form-label">
                        <select name="message_alert_condition" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['message_alert_condition']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">不提示</option>
                            <option <?php echo $list['message_alert_condition']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">窗口提示</option>
                            <option <?php echo $list['message_alert_condition']==3?'selected':''; ?> value="3" class="am-form-field am-input-sm">声音提示</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">导出路径：　</span>
                    <label class="am-form-label">
                        <input type="text" id="deriva_path" class="am-form-field am-input-sm" name="deriva_path" value="<?php echo $list['deriva_path']; ?>" />
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">下单板停靠位置：    </span>
                    <label class="am-form-label">
                        <select name="berth_location" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['berth_location']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">靠左</option>
                            <option <?php echo $list['berth_location']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">靠右</option>
                        </select>
                    </label>
                </div>

                <div class="am-form-group">
                    <span class="s1">是否展示持仓线：    </span>
                    <label class="am-form-label">
                        <select name="show_position_wire" title="" class="am-form-field am-input-sm">
                            <option <?php echo $list['show_position_wire']==1?'selected':''; ?> value="1" class="am-form-field am-input-sm">显示</option>
                            <option <?php echo $list['show_position_wire']==2?'selected':''; ?> value="2" class="am-form-field am-input-sm">不显示</option>
                        </select>
                    </label>
                </div>

                <div class="am-panel-bd" style="padding-bottom: 1rem;margin-left: 13rem">
                    <button type="submit" class="am-btn am-btn-primary am-btn-sm" onclick="sub() " >确定</button>
                    <a href="index.html" class="am-btn am-btn-secondary am-btn-sm">返回</a>
                </div>
        </div>
    
            </div>
        </div>
    </div>
</div>
</div>
</form>
<script type="text/javascript" src="/static/js/jquery.min.js"></script>
<script type="text/javascript" src="/static/js/amazeui.min.js"></script>
<script type="text/javascript" src="/static/js/amazeui.datatables.min.js"></script>
<script type="text/javascript" src="/static/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="/static/js/app.js"></script>
<script type="text/javascript" src="/static/layer/layer.js"></script>
<form action="<?php echo $url; ?>" class="am-form-inline" role="form" id="form" method="get">
    <input type="hidden" name="name"/>
    <input type="hidden" name="start_query">
    <input type="hidden" name="end_query">
    <input type="hidden" name="status">
    <input type="hidden" name="page">
        <input type="hidden" name="number">
    <input type="hidden" name="phone">
</form>
<script type="text/javascript">
    $(function () {
        var nav = $('.left-sidebar li.sidebar-nav-link');
        nav.removeClass("active");
//        nav.eq(1).find('a').addClass("active");
        nav.eq(<?php echo $a; ?>).find('ul').show();
        nav.eq(<?php echo $a; ?>).find('ul li').eq(<?php echo $b; ?>).find('a').addClass('active');
    });
    function sub() {
        var arr = parseFormJson("#form");
        if(arr.p_names == ""){
            alert("请输入推荐人信息,或填无");
        }else{
            $.ajax({
                url: "<?php echo url('Transaction/parameter_edit'); ?>",
                data: {arr: arr},
                type: "post",
                success: function (r) {
                    // console.log(r);
                    // return false;
                    if (r['code'] == 1) {
                        alert_open(r['msg']);

                    } else {
                        alert_msg(r['msg']);
                    }
                }
            });
        }    
    }

     function sub_fied() {
        var arr = parseFormJson("#form");
        if(arr.reid == ""){
            alert_open("请输入推荐人信息,或填无");
        }else if(arr.reid != "无"){
            $.ajax({
                url: "<?php echo url('Users/p_name'); ?>",
                data: {arr: arr.reid},
                type: "post",
                success: function (r) {
                    if (r['code'] == 1) {
                        //alert_open(r['msg']);
                        document.getElementById("p_names").value=r['msg'];
                        document.getElementById("p_name").style.display="inline";
                    } else {
                        alert_msg(r['msg']);
                        document.getElementById("reid").value="";
                        document.getElementById("p_name").style.display="none";
                    }
                    
                }
            });
        }else if(arr.reid == "无"){
            document.getElementById("p_name").style.display="none";
        }    
    }
</script>
</body>
</html>
