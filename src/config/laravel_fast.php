<?php
return [
    //Http Server
    'http_server'=>[
        'host'=>'0.0.0.0',  //监听ip地址  0.0.0.0代表监听全部
        'port'=>'9501', //监听端口
        'worker_num'=>8, //worker进程数
        'daemonize'=>1,  //是否后台运行
        'pid_file' => storage_path('swoole_server.pid'), //PID文件存储路径
        'log_file'=> storage_path('/logs/'). date('Ymd') . '_swoole_server.log',
    ],
    //socket
    'socket'=>[
        'host'=>'0.0.0.0',  //监听ip地址  0.0.0.0代表监听全部
        'port'=>'9502', //监听端口
        'worker_num'=>8, //worker进程数
        'daemonize'=>1,  //是否后台运行
        'pid_file' => storage_path('swoole_socket.pid'), //PID文件存储路径
        'log_file'=> storage_path('/logs/'). date('Ymd') . '_socket.log',
    ]

];
