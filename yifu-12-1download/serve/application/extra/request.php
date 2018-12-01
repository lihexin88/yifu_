<?php
function do_get($url)
{
    $headerArr[] = 'Content-Type: application/x-www-form-urlencoded; charset=utf-8';
    $ch = curl_init();        //初始化
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);//设置cURL允许执行的最长秒数。
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_HEADER, false);//启用时会将头文件的信息作为数据流输出。
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        // 跳过证书检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);        // 不从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    $output = curl_exec($ch);        //执行并获取HTML文档内容
    curl_close($ch);     //释放curl句柄
    return $output;
}

/**
 * @param $url
 * @param array $request
 * @return mixed
 */
function do_post($url,  array $request = array())
{ // 模拟提交数据函数
    $post_data = http_build_query($request, '', '&');
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        echo 'Errno' . curl_error($curl);//捕抓异常
    }
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据，json格式
}