<?php

namespace NovinVision\MultiStepLogin\Auth;

use Illuminate\Auth\Passwords\PasswordBrokerManager as PasswordBrokerManagerBase;
use Illuminate\Support\Str;

class PasswordBrokerManager extends PasswordBrokerManagerBase
{

    protected function createTokenRepository(array $config)
    {
        $key = $this->app['config']['app.key'];
        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        $connection = $config['connection'] ?? null;
        // return an instance of your new repository
        // it's in the same namespace, no need to alias it
        return new DatabaseTokenRepository(
            $this->app['db']->connection($connection),
            $this->app['hash'],
            $config['table'],
            $key,
            $config['expire']
        );
    }
}
