<?php
return [
    'type' => 'mysql',    // 数据库类型
    'hostname' => '127.0.0.1',    // 服务器地址 47.75.170.151 192.168.1.110
    'database' => 'futures',// 数据库名 wolfpay
    'username' => 'root',    // 用户名 longlong
    'password' => '7938390ab..',    // 密码 longlong
    'hostport' => '3306',    // 端口
    'dsn' => '',  // 连接dsn
    'params' => [],    // 数据库连接参数
    'charset' => 'utf8',    // 数据库编码默认采用utf8
    'prefix' => 'sn_',    // 数据库表前缀
    'debug' => true,    // 数据库调试模式
    'deploy' => 0,    // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'rw_separate' => false,    // 数据库读写是否分离 主从式有效
    'master_num' => 1,    // 读写分离后 主服务器数量
    'slave_no' => '',    // 指定从服务器序号
    'fields_strict' => true,    // 是否严格检查字段是否存在
    'resultset_type' => '\think\Collection',    // 数据集返回类型
    'auto_timestamp' => false,    // 自动写入时间戳字段
    'datetime_format' => 'Y-m-d H:i:s',    // 时间字段取出后的默认时间格式
    'sql_explain' => false,    // 是否需要进行SQL性能分析
];
