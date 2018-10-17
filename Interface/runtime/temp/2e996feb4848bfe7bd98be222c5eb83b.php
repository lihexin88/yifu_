<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"C:\wamp64\www\yifu\Interface\public/../application/index\view\feedback\question.html";i:1539314251;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8">
    <title>逸富-银期转账</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/modules.css">
    <link rel="stylesheet" href="../css/question.css">
</head>
<body>

<main class="main">
    <nav class="navTab">
        <button type="button" class="pitchTab" id="question">提交问题</button>
        <button type="button" id="feedback">反馈记录</button>
    </nav>
    <div class="pay_box">
        <div class="pay_head">提交问题</div>
        <p class="questionTitle">问题分类：</p>
        <div class="questionType">
            <select id="questionType">
                <option value="1">交易</option>
                <option value="2">出入金</option>
                <option value="3">其他</option>
            </select>
        </div>
        <p class="questionTitle">问题描述：</p>
        <textarea class="content" id="content"></textarea>
        <p class="questionTitle">联系电话：</p>
        <div class="yourNum">
            <input type="number" maxlength="11" id="yourNum">
        </div>
        <p class="numHint">提示：请输入正确的手机号码，以便于我们与您及时联系</p>
        <div class="questionBtn">
            <button type="button" class="resetBtn" id="resetBtn">重置</button>
            <button type="button" class="submitBtn" id="submitBtn">提交问题</button>
        </div>
    </div>
</main>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../layui-v2.3.0/layui/layui.js"></script>
<script src="../js/question.js"></script>
</body>
</html>