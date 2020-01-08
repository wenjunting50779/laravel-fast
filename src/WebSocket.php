<?php


namespace LaravelFast;


use Symfony\Component\Console\Command\Command;

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
        //绑定回调函数
        $this->server->on('open', [$this, 'onOpen']);

        $this->server->on('message', [$this, 'onMessage']);

        $this->server->on('close', [$this, 'onClose']);

        $this->command->info("\r\nswoole socket process created successful!\r\n");

        $this->server->start();
    }


    public function onOpen(){

    }
    public function onMessage(){

    }

    public function onClose(){

    }



}
