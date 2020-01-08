<?php


namespace LaravelFast;


use Symfony\Component\Console\Command\Command;

class HttpServer extends Server
{
    //header Server 全局变量的映射关系
    private static $headerServerMapping = [
        'x-real-ip'       => 'REMOTE_ADDR',
        'x-real-port'     => 'REMOTE_PORT',
        'server-protocol' => 'SERVER_PROTOCOL',
        'server-name'     => 'SERVER_NAME',
        'server-addr'     => 'SERVER_ADDR',
        'server-port'     => 'SERVER_PORT',
        'scheme'          => 'REQUEST_SCHEME',
    ];

    /**
     * 启动http_server服务
     */
    protected  function start()
    {
        $pid = $this->getPid();
        if ($pid && \Swoole\Process::kill($pid, 0)) {
            $this->command->error("\r\nswoole http_server process already exist!\r\n");
            exit;
        }
        $this->server = new \Swoole\Http\Server($this->config['host'], $this->config['port']);
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
        $this->server->on('workerstart', [$this, 'onWorkerStart']);
        $this->server->on('request', [$this, 'onRequest']);

        $this->command->info("\r\nswoole http_server process created successful!\r\n");

        $this->server->start();
    }

    /**
     * 将laravel文件载入内存
     *
     * @param $serv
     * @param $worker_id
     */
    public function onWorkerStart($serv, $worker_id)
    {
        require base_path() . '/vendor/autoload.php';
        require_once base_path() . '/bootstrap/app.php';
    }

    /**
     * 处理http请求
     *
     * @param $request
     * @param $response
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function onRequest($request, $response)
    {
        //server信息
        $_SERVER = [];
        if (isset($request->server)) {
            foreach ($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        //header头信息
        if (isset($request->header)) {
            foreach ($request->header as $key => $value) {
                if (isset(self::$headerServerMapping[$key])) {
                    $_SERVER[self::$headerServerMapping[$key]] = $value;
                } else {
                    $key                                 = str_replace('-', '_', $key);
                    $_SERVER[strtoupper('http_' . $key)] = $value;
                }
            }
        }
        //是否开启https
        if (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https') {
            $_SERVER['HTTPS'] = 'on';
        }
        //request uri
        if (strpos($_SERVER['REQUEST_URI'], '?') === false && isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0
        ) {
            $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
        }

        //全局的
        if (!isset($_SERVER['argv'])) {
            $_SERVER['argv'] = isset($GLOBALS['argv']) ? $GLOBALS['argv'] : [];
            $_SERVER['argc'] = isset($GLOBALS['argc']) ? $GLOBALS['argc'] : 0;
        }

        //get信息
        $_GET = [];
        if (isset($request->get)) {
            foreach ($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }

        //post信息
        $_POST = [];
        if (isset($request->post)) {
            foreach ($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }

        //文件请求
        $_FILES = [];
        if (isset($request->files)) {
            foreach ($request->files as $k => $v) {
                $_FILES[$k] = $v;
            }
        }
        //cookie
        $_COOKIE = [];
        if (isset($request->cookie)) {
            foreach ($request->cookie as $k => $v) {
                $_COOKIE[$k] = $v;
            }
        }

        //响应头
        $responseHeader = '';
        ob_start();//启用缓存区
        \Illuminate\Http\Request::enableHttpMethodParameterOverride();
        $kernel          = app()->make(\Illuminate\Contracts\Http\Kernel::class);
        $laravelResponse = $kernel->handle(
        //Create an Illuminate request from a Symfony instance.
            $request = \Illuminate\Http\Request::createFromBase(new \Symfony\Component\HttpFoundation\Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER, $request->rawContent()))
        );
        $responseHeader  = $laravelResponse->headers;
        $laravelResponse->send();
        $kernel->terminate($request, $laravelResponse);
        $res = ob_get_contents();//获取缓存区的内容
        ob_end_clean();//清除缓存区
        //输出缓存区域的内容
        foreach (explode("\n", $responseHeader) as $val) {
            if ($item = trim($val)) {
                $pos = strpos($item, ':');
                $response->header(substr($item, 0, $pos), trim(substr($item, $pos + 1)));
            }
        }
        $response->end($res);
    }

}
