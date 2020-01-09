<?php


namespace LaravelFast;

class WebSocket extends Server
{
    public function __construct()
    {
        parent::__construct('socket');
    }

    /**
     * 启动sokect服务
     */
    public function start()
    {
        $pid = $this->getPid();
        if ($pid && \Swoole\Process::kill($pid, 0)) {
            $this->command->error("\r\nswoole socket process already exist!\r\n");
            exit;
        }
        $this->server = new \Swoole\WebSocket\Server($this->config['host'], $this->config['port']);
        $this->server->set([
            'worker_num'    => $this->config['worker_num'],
            'daemonize'     => $this->config['daemonize'],
            'max_request'   => 1000,
            'dispatch_mode' => 2,
            'pid_file'      => $this->config['pid_file'],
            'log_file'      => $this->config['log_file'],
            'log_level'     => 3, //日志等级 notice
        ]);

        //是否配置了实现WebSocketInterface接口且存在
        if (!$this->config['web_socket_implements'] || !class_exists($this->config['web_socket_implements'])) {
            throw new \Exception('please implement ' . WebSocketInterface::class . ' interface.');
        }
        $webSocketImplements = new $this->config['web_socket_implements'];
        //是否实现了websocket接口
        if (!$webSocketImplements instanceof WebSocketInterface) {
            throw new \Exception('please implement ' . WebSocketInterface::class . ' interface.');
        }
        //绑定回调函数
        $this->server->on('open', [$webSocketImplements, 'onOpen']);

        $this->server->on('message', [$webSocketImplements, 'onMessage']);

        $this->server->on('close', [$webSocketImplements, 'onClose']);

        $this->command->info("\r\nswoole socket process created successful!\r\n");

        $this->server->start();
    }


}
