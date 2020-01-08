<h1 align="center"> laravel-fast </h1>
<p align="center"> 使用swoole加速laravel框架.</p>

## 安装

```shell
$ composer require wenjunting50779/laravel-fast
```

## laravel
### 在config/app.config文件注册服务提供者
```$xslt
'providers' => [
    //...
    LaravelFast\ServiceProvider::class,
],
```

### 发布配置
```$xslt
php artisan vendor:publish --provider="LaravelFast\ServiceProvider"
```

### 注册命令
app/Console/Kerbel.php中加入
```$xslt
protected $commands = [
     //...
     LaravelFast\ManagerCommand::class,
],
```

###使用
```shell
$ php artisan laravel_fast help  //获取帮助信息
Usage: 
  [/usr/local/php/bin/php] artisan laravel_fast <service> <action>
Arguments:
  service               http_server|web_socket 开启的服务类型  
  action                start|stop|restart|reload|help  开启/停止/重启/平滑重启/帮助信息
```

## License

MIT
