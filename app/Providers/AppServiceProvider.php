<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\User;
use Illuminate\Support\Facades\View;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GeminiService::class, function () {
            return new GeminiService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap(); 
        
        // Share the $users variable globally
        if (\Schema::hasTable('users')) {
            View::share('users', User::all());
        }
        // View::composer('*', function ($view) {
        //     $view->with('users', User::all());
        // });
    }
}

