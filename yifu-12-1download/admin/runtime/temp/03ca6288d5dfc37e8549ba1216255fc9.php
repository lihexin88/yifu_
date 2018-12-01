<?php if (!defined('THINK_PATH')) exit(); /*a:9:{s:73:"/www/web/test/yifu/admin/public/../application/index/view/banks/edit.html";i:1539671382;s:64:"/www/web/test/yifu/admin/application/index/view/public/meta.html";i:1529999324;s:64:"/www/web/test/yifu/admin/application/index/view/public/link.html";i:1529999332;s:64:"/www/web/test/yifu/admin/application/index/view/public/form.html";i:1529999356;s:66:"/www/web/test/yifu/admin/application/index/view/public/header.html";i:1540460820;s:64:"/www/web/test/yifu/admin/application/index/view/public/left.html";i:1529999338;s:71:"/www/web/test/yifu/admin/application/index/view/public/content_top.html";i:1529999370;s:66:"/www/web/test/yifu/admin/application/index/view/public/editor.html";i:1521772962;s:64:"/www/web/test/yifu/admin/application/index/view/public/foot.html";i:1529999360;}*/ ?>
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
    <style>
        input.check_box{
            width:100px;
        }
        .p_checkbox{
            width: 25%;
            float: left;
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
        <li><a href="#">内容管理</a></li>
        <li class="am-active">帮助中心</li>
    </ol>
    <div class="widget am-cf">
        <form class="am-cf" id="form" onsubmit="return false">
            <div class="am-panel am-panel-primary">
                <div class="am-panel-hd">基础信息</div>
               
                <div class="am-form-group">
                    <span class="s1">银行名称：</span>
                    <label class="am-form-label">
                        <input type="text" id="title" class="am-form-field am-input-sm" name="name" value="<?php echo $list['name']; ?>" required="required"/>
                    </label>
                </div>
                <div class="am-form-group" id="no">
                    <span class="s1">图片：</span>
                    <label class="am-form-label">
                        <img id="newPhoto" style="max-height:300px;max-width:200px;" src="<?php echo $list['pic']; ?>"/>
                    </label>
                    <span class="s2" id="uploadPhotoConfirm"></span>
                </div>
                <div class="am-form-group">
                    <span class="s1">上传图片：</span>
                    <label class="am-form-label">
                        <input type="button" class="am-btn am-btn-primary" id="upload_button" value="上传图片"/><br/>
                        <input type="hidden" id="newPhotoName" name="pic" value="<?php echo $list['pic']; ?>"/>
                        <input type="file" style="display:none" name="file">
                    </label>
                    <span class="s2">*请上传格式为.png .jpg .jpeg 的图片必须为208*72</span>
                </div>
                <input type="hidden" id="title" class="am-form-field am-input-sm" name="id" value="<?php echo $list['id']; ?>" />
                <div class="am-form-group">
                    <span class="s1">银行代码：</span>
                    <label class="am-form-label">
                        <input type="text" id="title" class="am-form-field am-input-sm" name="code" value="<?php echo $list['code']; ?>"  placeholder=" "/>
                        
                    </label>
                </div>
               <div class="am-form-group">
                    <span class="s1">状态：</span>
                    <label class="am-form-label">
                        <select name="status" title="" class="am-form-field am-input-sm">
                            <?php if($list['status'] == 1): ?>
                            <option value="1" selected>正常</option>
                            <?php else: ?>
                            <option value="1">正常</option>
                            <?php endif; if($list['status'] == 0): ?>
                            <option value="0" selected>禁用</option>
                            <?php else: ?>
                            <option value="0">禁用</option>
                            <?php endif; ?>
                        </select>
                    </label>
                </div>
                
                <div class="am-panel-bd" style="padding-bottom: 1rem;margin-left: 13rem">
                    <button type="submit" class="am-btn am-btn-primary am-btn-sm" onclick="sub() ">确定</button>
                    <a href="index.html?a=<?php echo $a; ?>&b=<?php echo $b; ?>" class="am-btn am-btn-secondary am-btn-sm">返回</a>
                </div>
            </div>
    </div>
    </form>
</div>
</div>
<script type="text/javascript" charset="utf-8" src="/static/ud/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/static/ud/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/static/ud/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
    UE.getEditor('content', {
        autoHeight: true,
        toolbars: [
            [
                'anchor', 'undo', 'redo', 'bold', 'indent', 'italic', 'underline', 'strikethrough', 'subscript',
                'fontborder', 'superscript', 'formatmatch', 'blockquote', 'pasteplain', 'selectall', 'horizontal',
                'removeformat', 'time', 'date', 'unlink', 'inserttitle', 'cleardoc', 'insertcode', 'fontfamily',
                'fontsize', 'paragraph', 'simpleupload', 'edittable', 'edittd', 'link', 'spechars', 'searchreplace',
                'justifyleft', 'justifyright', 'justifycenter', 'justifyjustify', 'forecolor', 'backcolor',
                'insertorderedlist', 'insertunorderedlist', 'directionalityltr', 'directionalityrtl', 'rowspacingtop',
                'rowspacingbottom', 'insertframe', 'imagenone', 'imageleft', 'imageright', 'attachment', 'imagecenter',
                'lineheight', 'edittip ', 'customstyle', 'autotypeset', 'touppercase', 'tolowercase', 'background'
            ]
        ]
    });
</script>
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
<script type="text/javascript">
    $(function () {
        var nav = $('.left-sidebar li.sidebar-nav-link');
        nav.removeClass("active");
        nav.eq(<?php echo $a; ?>).find('ul').show();
        nav.eq(<?php echo $a; ?>).find('ul li').eq(<?php echo $b; ?>).find('a').addClass('active');
    });
    function sub() {
        var arr = parseFormJson("#form");
        $.ajax({
            url: "<?php echo url('Banks/banks_add_edit'); ?>",
            data: {arr: arr},
            type: "post",
            success: function (r) {
                // console.log(r);
                // return false;
                if (r['code'] == 1) {
                    alert_open(r['msg'])
                } else {
                    alert_msg(r['msg']);
                }
            }
        });
    }
</script>
<script type="text/javascript">
    $(function () {
        var button = $('#upload_button'), interval;
        var confirmDiv = $('#uploadPhotoConfirm');
        var fileType = 'pic', fileNum = 'one';
        new AjaxUpload(button, {
            action: "<?php echo url('Upload/upload'); ?>",
            name: 'file',
            onSubmit: function (file, ext) {
                if (fileType == 'pic') {
                    if (ext && /^(jpg|png|jpeg|gif|JPG)$/.test(ext)) {
                        this.setData({'info': '文件类型为图片'});
                    } else {
                        confirmDiv.text('文件格式错误，请上传格式为.png .jpg .jpeg 的图片');
                        return false;
                    }
                }
                confirmDiv.text('文件上传中');
                if (fileNum == 'one') this.disable();
                interval = window.setInterval(function () {
                    var text = confirmDiv.text();
                    if (text.length < 14) {
                        confirmDiv.text(text + '.');
                    } else {
                        confirmDiv.text('文件上传中');
                    }
                }, 200);
            },
            onComplete: function (file, response) {
                if (response != 'success') {
                    if (response == '2') {
                        confirmDiv.text("文件格式错误，请上传格式为.png .jpg .jpeg 的图片");
                    } else {
                        confirmDiv.text('上传完成');
                        $("#newPhotoName").val("/uploads/" + response);
                        $("#newPhoto").attr("src", "/uploads/" + response);
                        $('#no').show();
                    }
                }
                window.clearInterval(interval);
                this.enable();
                if (response == 'success') alert('上传成功');
            }
        });
    });
</script>
</body>
</html>