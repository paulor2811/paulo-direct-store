<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Register model observers
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \Modules\Products\Models\Produto::observe(\App\Observers\ProdutoObserver::class);
    }
}
