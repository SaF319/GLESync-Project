<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $host = request()->getHost();
        if ($host === '127.0.0.1' || $host === 'localhost') {
            config(['session.domain' => null]);
        }
    }
}
