<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use Exception;
use PDOException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Handle offline database

        //Use bootstrap 4 for pagination css
        Paginator::useBootstrapFour();
         // Override mail configuration
        config(['mail.default' => 'smtp']);
        config(['mail.mailers.smtp.host' => 'smtp.gmail.com']);
        config(['mail.mailers.smtp.port' => 587]);
        config(['mail.mailers.smtp.encryption' => 'tls']);
        config(['mail.mailers.smtp.username' => 'pbltunetic@gmail.com']);
        config(['mail.mailers.smtp.password' => 'cyrykpprqfupgmwa']);
    }
}
