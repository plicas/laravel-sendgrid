<?php

namespace jsantos\sendgrid;

use Illuminate\Support\ServiceProvider;

class SendgridServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('jsantos\sendgrid\SendgridController');
    }
}
