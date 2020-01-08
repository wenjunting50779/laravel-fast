<?php

namespace LaravelFast;

use Illuminate\Console\Command;

class ManagerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lars_http_server {server} {action}';


    //swoole http server instance
    private $server;

    //进程ID
    private $pid_file;

    //日志文件
    private $log_file;

    //配置
    private $config=[];


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'start or stop the swoole http server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //获取传递的操作
        $action = $this->argument('action');

        $server = strtolower($this->argument('server'));

        switch ($server){
            case 'http_server':
                (new HttpServer())->handle($this,$action);
                break;
            case 'web_socket':
                (new WebSocket())->handle($this,$action);
                break;
            default:
                $this->error('服务不存在');
        }
    }

}
