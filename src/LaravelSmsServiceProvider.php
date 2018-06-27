<?php

namespace Cammac\LaravelSms;

use Cammac\LaravelSms\SMS\DriverManager;
use SimpleSoftwareIO\SMS\SMS;
use SimpleSoftwareIO\SMS\SMSServiceProvider;

class LaravelSmsServiceProvider extends SMSServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('sms', function ($app) {
            $sms = new SMS($app['sms.sender']);
            $sms->setContainer($app);
            $sms->setQueue($app['queue']);

            //Set the from setting
            if ($app['config']->has('sms.from')) {
                $sms->alwaysFrom($app['config']['sms']['from']);
            }

            return $sms;
        });
        app()->bind('sms.sender', function ($app) {
            return (new DriverManager($app))->driver();
        });
    }
}
