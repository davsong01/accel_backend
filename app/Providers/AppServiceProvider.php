<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Channels\DatabaseNotificationChannel;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        $this->app->instance(IlluminateDatabaseChannel::class, new DatabaseNotificationChannel);

        Validator::extend('website', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(https?:\/\/(www\.)?|www\.)[a-zA-Z0-9]+\.[a-zA-Z]{2,}(\/\S*)?$/', $value);
        });
    }
}
