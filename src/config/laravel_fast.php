<?php
return [
    //Http Server
    'http_server' => [
        //监听ip地址  0.0.0.0代表监听全部
        'host'       => '0.0.0.0',
        //监听端口
        'port'       => 9501,
        //worker进程数
        'worker_num' => 8,
        //是否后台运行
        'daemonize'  => 1,
        //PID文件存储路径
        'pid_file'   => storage_path('swoole_server.pid'),
        //日志文件存储位置
        'log_file'   => storage_path('/logs/') . date('Ymd') . '_swoole_server.log',
    ],
    //socket
    'socket'      => [
        //监听ip地址  0.0.0.0代表监听全部
        'host'                  => '0.0.0.0',
        //监听端口
        'port'                  => 9502,
        //worker进程数
        'worker_num'            => 8,
        //是否后台运行
        'daemonize'             => 1,
        //PID文件存储路径
        'pid_file'              => storage_path('swoole_socket.pid'),
        //日志文件存储位置
        'log_file'              => storage_path('/logs/') . date('Ymd') . '_socket.log',
        //websocket具体逻辑的实现 传递类名称【需包含命名空间】  需要实现 LaravelFast\WebSocketInterface 接口
        //默认例子仅实现了简单的收发
        'web_socket_implements' => LaravelFast\Example\WebSocket::class,
    ]

];
