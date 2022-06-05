<?php


namespace App\Helpers\sms;


use Illuminate\Support\ServiceProvider;

class smsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('sms' , function (){
            return new smsService();
        });
    }
}
