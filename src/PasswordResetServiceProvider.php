<?php

namespace NovinVision\MultiStepLogin;

use Illuminate\Auth\Passwords\PasswordResetServiceProvider as PasswordResetServiceProviderBase;
use NovinVision\MultiStepLogin\Auth\PasswordBrokerManager;

class PasswordResetServiceProvider extends PasswordResetServiceProviderBase
{
    protected function registerPasswordBroker()
    {
        $this->app->singleton('auth.password', function ($app) {
            return new PasswordBrokerManager($app);
        });

        $this->app->bind('auth.password.broker', function ($app) {
            return $app->make('auth.password')->broker();
        });
    }
}
