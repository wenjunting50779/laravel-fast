<h2 align="center"> Laravel-Fast </h2>
<p align="center"> 使用swoole加速laravel框架.</p>

##简介
- 使用swoole作为后端http server来加速laravel框架，一次性加载文件到内存。
- 基于swoole开发的web_socket服务端。
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

### 发布配置文件
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
  ,--,                                                                                                                  
,---.'|                                                                                                                  
|   | :                                                     ,--,                 ,---,.                          ___     
:   : |                                                   ,--.'|               ,'  .' |                        ,--.'|_   
|   ' :                __  ,-.                            |  | :       ,---,.,---.'   |                        |  | :,'  
;   ; '              ,' ,'/ /|              .---.         :  : '     ,'  .' ||   |   .'              .--.--.   :  : ' :  
'   | |__   ,--.--.  '  | |' | ,--.--.    /.  ./|  ,---.  |  ' |   ,---.'   ,:   :  :    ,--.--.    /  /    '.;__,'  /   
|   | :.'| /       \ |  |   ,'/       \ .-' . ' | /     \ '  | |   |   |    |:   |  |-, /       \  |  :  /`./|  |   |    
'   :    ;.--.  .-. |'  :  / .--.  .-. /___/ \: |/    /  ||  | :   :   :  .' |   :  ;/|.--.  .-. | |  :  ;_  :__,'| :    
|   |  ./  \__\/: . .|  | '   \__\/: . .   \  ' .    ' / |'  : |__ :   |.'   |   |   .' \__\/: . .  \  \    `. '  : |__  
;   : ;    ," .--.; |;  : |   ," .--.; |\   \   '   ;   /||  | '.'|`---'     '   :  '   ," .--.; |   `----.   \|  | '.'| 
|   ,/    /  /  ,.  ||  , ;  /  /  ,.  | \   \  '   |  / |;  :    ;          |   |  |  /  /  ,.  |  /  /`--'  /;  :    ; 
'---'    ;  :   .'   \---'  ;  :   .'   \ \   \ |   :    ||  ,   /           |   :  \ ;  :   .'   \'--'.     / |  ,   /  
         |  ,     .-./      |  ,     .-./  '---" \   \  /  ---`-'            |   | ,' |  ,     .-./  `--'---'   ---`-'   
          `--`---'           `--`---'             `----'                     `----'    `--`---'                          
Usage: 
  [/usr/local/php/bin/php] artisan laravel_fast <service> <action>
Arguments:
  service               http_server|web_socket 开启的服务类型  
  action                start|stop|restart|reload|help  开启/停止/重启/平滑重启/帮助信息
```

## License

MIT
