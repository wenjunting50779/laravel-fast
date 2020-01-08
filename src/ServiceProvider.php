<?php


namespace LaravelFast;
use \Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        $this->setConfig();
    }

    public function register()
    {

    }


    protected function setConfig(){
        $source = realpath(__DIR__.'/config/lars_http_server.php');
        if($this->app->runningInConsole()) {
            $this->publishes([ $source=> config_path('lars_http_server.php')], 'lars_http_server');
        }
    }
}
