<?php
define('APP_PATH', __DIR__ . '/../application/');
//define('BIND_MODULE', 'index');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
//（前端IP或域名）
header('Access-Control-Allow-Origin:192.168.1.129');
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Access-Control-Allow-Credentials:true");


