<?php if (!defined('THINK_PATH')) exit(); /*a:8:{s:87:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\public/../application/index\view\agents\add.html";i:1530342664;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\meta.html";i:1529999324;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\link.html";i:1529999332;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\form.html";i:1529999356;s:80:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\header.html";i:1540279177;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\left.html";i:1529999338;s:85:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\content_top.html";i:1529999370;s:78:"D:\phpStudy\PHPTutorial\WWW\yifu\admin\application\index\view\public\foot.html";i:1529999360;}*/ ?>
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
    .am-form-group .s1 {
        display: inline-block;
        width: 150px;
    }

    .am-form-group .s2 {
        margin-left: 20px;
        color: #F37B1D;
    }

    .am-form-group input {
        width: 300px;
        display: inline-block;
    }

    .am-form-group select {
        width: 300px;
        display: inline-block;
    }

    .am-form-label {
        font-weight: inherit;
    }

    .am-form-group {
        margin-bottom: 0;
    }

    .am-panel-bd {
        padding-bottom: 0;
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
            <ol class="am-breadcrumb" style="    margin-bottom: 0;">
                <li><a href="../home/index.html" class="am-icon-home">首页</a></li>
                <li><a href="#">添加下级代理</a></li>
            </ol>
            <div class="widget am-cf">
                <form class="am-cf" id="form" onsubmit="return false">
                    <div class="am-panel am-panel-primary">
                        <div class="am-panel-hd">基础信息</div>
                        <div class="am-panel-bd">
                            <input type="hidden" name="id" value="<?php echo isset($list['uid'])?$list['uid']:''; ?>"/>
                            <div class="am-form-group">
                                <span class="s1">所属代理：</span>
                                <label class="am-form-label">
                                    <select name="pid" title="" class="am-form-field am-input-sm">
                                        <?php foreach($agent as $val): if(($list['id'])==($val['id'])): ?>
                                        <option selected value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                                        <?php endif; endforeach; ?>
                                    </select>
                                </label>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">代理编号：</span>
                                <label class="am-form-label">
                                    <input type="text" id="number" class="am-form-field am-input-sm" name="number" value="" placeholder="请输入代理编号"/>
                                </label>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">登录密码：</span>
                                <label class="am-form-label">
                                    <input type="password" id="password" class="am-form-field am-input-sm" name="password" value="" placeholder="请输入登录密码"/>
                                </label>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">名称：</span>
                                <label class="am-form-label">
                                    <input type="text" id="name" class="am-form-field am-input-sm" name="name" value="" placeholder="请输入名称"/>
                                </label>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">禁用状态：</span>
                                <label class="am-form-label">
                                    <select name="status" title="" class="am-form-field am-input-sm">
                                        <option value="">请选择</option>
                                        <option value="1">否</option>
                                        <option value="2">是</option>   
                                    </select>
                                </label>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">提现银行卡号：</span>
                                <label class="am-form-label">
                                    <input type="text" id="bank_card" class="am-form-field am-input-sm" name="bank_card" value="" placeholder="请输入银行卡号"/>
                                </label>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">开户号：</span>
                                <label class="am-form-label">
                                    <input type="text" id="bank_account" class="am-form-field am-input-sm" name="bank_account" value="" placeholder="请输入开户号"/>
                                </label>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">开户行：</span>
                                <label class="am-form-label">
                                    <input type="text" id="bank_name" class="am-form-field am-input-sm" name="bank_name" value="" placeholder="请输入开户行"/>
                                </label>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">开户分行：</span>
                                <label class="am-form-label">
                                    <input type="text" id="bank_address" class="am-form-field am-input-sm" name="bank_address" value="" placeholder="请输入开户分行"/>
                                </label>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">提现状态：</span>
                                <label class="am-form-label">
                                    <select name="withdrawals_status" title="" class="am-form-field am-input-sm">
                                        <option value="">请选择</option>
                                        <option value="1">否</option>
                                        <option value="2">是</option>   
                                    </select>
                                </label>
                                <span class="s2">*是否禁止该代理提现</span>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">代理域名：</span>
                                <label class="am-form-label">
                                    <input type="text" id="agent_url" class="am-form-field am-input-sm" name="agent_url" value="" placeholder="请输入代理域名"/>
                                </label>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">联系人姓名：</span>
                                <label class="am-form-label">
                                    <input type="text" id="real_name" class="am-form-field am-input-sm" name="real_name" value="" placeholder="请输入联系人姓名"/>
                                </label>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">联系人电话：</span>
                                <label class="am-form-label">
                                    <input type="text" id="phone" class="am-form-field am-input-sm" name="phone" value="" placeholder="请输入联系人电话"/>
                                </label>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">保证金：</span>
                                <label class="am-form-label">
                                    <input type="text" id="bond" class="am-form-field am-input-sm" name="bond" value="" placeholder="请输入保证金"/>
                                </label>
                                <span class="s2">*不允许提现</span>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">盈利分配提成比例：</span>
                                <label class="am-form-label">
                                    <input type="text" id="wit_ratio" class="am-form-field am-input-sm" name="wit_ratio" value="" placeholder="请输入盈利分配提成比例"/>
                                </label>
                                <span class="s2">1=100%</span>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">手续费提成比例：</span>
                                <label class="am-form-label">
                                    <input type="text" id="fee_ratio" class="am-form-field am-input-sm" name="fee_ratio" value="" placeholder="请输入手续费提成比例"/>
                                </label>
                                <span class="s2">1=100%</span>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">递延费提成比例：</span>
                                <label class="am-form-label">
                                    <input type="text" id="defer_ratio" class="am-form-field am-input-sm" name="defer_ratio" value="" placeholder="请输入递延费提成比例"/>
                                </label>
                                <span class="s2">1=100%</span>
                            </div>
                            <div class="am-form-group">
                                <span class="s1">备注：</span>
                                <label class="am-form-label">
                                    <input type="text" id="remake" class="am-form-field am-input-sm" name="remake" value="" placeholder="请输入备注"/>
                                </label>
                            </div>
                            <div class="am-panel-bd" style="padding-bottom: 1rem;margin-left: 13rem">
                                <button type="submit" class="am-btn am-btn-primary am-btn-sm" onclick="sub()">确定</button>
                                <a href="index.html" class="am-btn am-btn-secondary am-btn-sm">返回</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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
        <script type="text/javascript" src="/static/ajaxupload.js"></script>
        <script>


        </script>
        <script type="text/javascript">
            $(function () {
            var nav = $('.left-sidebar li.sidebar-nav-link');
            nav.removeClass("active");
            nav.eq(6).find('ul').show();
            // nav.eq(3).find('ul li').eq(0).find('a').addClass('active');

            });
            function sub() {
            var arr = parseFormJson("#form");
            $.ajax({
            url: "<?php echo url('Agents/add'); ?>",
                    data: {arr: arr},
                    type: "post",
                    success: function (r) {
                    if (r['code'] == 1) {
                    alert_open(r['msg'])
                    } else {
                    alert_msg(r['msg']);
                    }
                    }
            });
            }
        </script>
    </body>
</html>