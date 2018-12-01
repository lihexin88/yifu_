<?php
// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
define('BIND_MODULE', 'index');
$allow_origin = array('http://192.168.1.234:8080','http://yfapp.bjfable.com', 'http://yfweb.bjfable.com','http://192.168.1.132','http://inter.yifu.com');
if (in_array($origin, $allow_origin)) {
    header('Access-Control-Allow-Origin:' . $origin);
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT');
    header("Access-Control-Allow-Credentials:true");
}
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
