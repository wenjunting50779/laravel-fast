<?php


namespace LaravelFast;


use Symfony\Component\Console\Command\Command;

abstract class Server
{
    //配置文件
    protected $config;

    //Symfony\Component\Console\Command\Command
    protected $command;

    //服务实例
    protected $server;

    //服务名称
    protected $server_name;

    public function __construct($server_name = 'http_server')
    {
        $this->server_name = $server_name;
        $this->setCofig($this->server_name);
        $this->init();
    }


    public function init()
    {
        if (!is_file($this->config['log_file'])) {
            $resource = fopen($this->config['log_file'], "w");
            fclose($resource);
        }
    }


    public function handle(Command $command, $action)
    {
        $this->command = $command;
        switch ($action) {
            case 'start':
                //检测进程是否已开启
                $this->start();
                break;
            case 'stop':
                $this->stop();
                break;
            case 'reload':
                $this->reload();
                break;
            case 'restart':
                $this->restart();
                break;
            default:
                $help = <<<EOS
Usage: 
  [%s] artisan lars_http_server <service> <action>
Arguments:
  service               http_server|web_socket
  action                start|stop|restart|reload|help
EOS;
                $this->command->info(sprintf($help, PHP_BINARY));
                break;
        }
    }

    /**
     * 启动服务
     *
     * @return mixed
     */
    abstract protected function start();

    /**
     * 平滑重启服务
     */
    protected function reload()
    {
        $pid = $this->getPid();
        if (!$pid) {
            $this->command->error("\r\nswoole {$this->server_name} process don't exist!\r\n");
            return false;
        }
        if (\Swoole\Process::kill((int)$pid, SIGUSR1)) {
            $this->command->info("\r\nswoole {$this->server_name} success to reload!\r\n");
        } else {
            $this->command->info("\r\nswoole {$this->server_name} fail to reload!\r\n");
        }
    }

    /**
     * 重启服务
     */
    protected function restart()
    {
        $pid = $this->getPid();
        if (!$pid) {
            $this->command->error("\r\nswoole {$this->server_name} process don't exist!\r\n");
            return false;
        }
        //KILL掉已启用的进程
        if (\Swoole\Process::kill((int)$pid, SIGTERM)) {
            $this->command->info("\r\nswoole {$this->server_name} successful to shutdown!\r\n");
        } else {
            $this->command->info("\r\nswoole {$this->server_name} failed to shutdown!\r\n");
            return false;
        }
        sleep(2);
        $this->start();
    }


    /**
     * 停止服务
     */
    protected function stop()
    {
        if (!$pid = $this->getPid()) {
            $this->command->error("\r\nswoole {$this->server_name} process not started!\r\n");
            exit;
        }
        if (\Swoole\Process::kill((int)$pid)) {
            $this->command->info("\r\nswoole {$this->server_name} process close successful!\r\n");
            exit;
        }
        $this->command->info("\r\nswoole {$this->server_name} process close failed!\r\n");
    }


    /**
     * 获取配置文件
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    public function setCofig($server = 'http_server'): void
    {
        $config       = config('lars_http_server') ?? require_once __DIR__ . '/config/lars_http_server.php';
        $this->config = $config[$server];
    }


    //获取pid
    protected function getPid()
    {
        return file_exists($this->config['pid_file']) ? file_get_contents($this->config['pid_file']) : false;
    }
}
