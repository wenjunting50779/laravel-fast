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
        $source = realpath(__DIR__.'/config/laravel_fast.php');
        if($this->app->runningInConsole()) {
            $this->publishes([ $source=> config_path('laravel_fast.php')], 'laravel_fast');
        }
    }
}
