<?php

namespace NovinVision\MultiStepLogin;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;

class MultistepLoginServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/multi-step-login.php', 'multi-step-login');

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'multi-step-login');

        $this->publishes([
            __DIR__ . '/config/multi-step-login.php' => config_path('multi-step-login.php'),
            __DIR__ . '/resources/css' => public_path('css'),
            __DIR__ . '/resources/js' => public_path('js'),
            __DIR__ . '/resources/fonts' => public_path('fonts'),
            __DIR__ . '/resources/views' => resource_path('views/vendor/multi-step-login'),
        ], 'multi-step-login');

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'novinvision.multi-step-login');

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        \Illuminate\Support\Facades\Validator::extend('auth_username', function ($attribute, $value, $parameters, $validator) {
            try {
                if(filter_var($value, FILTER_VALIDATE_EMAIL)){
                    return true;
                }

                $defaultCountry = config('multi-step-login.mobile_default_country');
                $number = (new PhoneNumber($value, $defaultCountry));
                return (!$number->isOfCountry('IR') && $number->isValid()) || ($number->isOfCountry('IR') && Str::startsWith($number->formatE164(), '+989'));
            } catch (\Exception) {
                return false;
            }

        }, ':attribute وارد شده نامعتبر است');

    }
}
