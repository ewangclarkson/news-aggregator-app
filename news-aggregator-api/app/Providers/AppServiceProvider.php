<?php

namespace App\Providers;

use App\Http\Domain\Services\ArticleServiceImpl;
use App\Http\Domain\Services\AuthServiceImpl;
use App\Http\Domain\Services\UserPreferenceServiceImpl;
use App\Http\Factory\NewsApiFactory;
use App\Http\Services\ArticleService;
use App\Http\Services\AuthService;
use App\Http\Services\UserPreferenceService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Singleton binding for NewsApiFactory
        $this->app->singleton(NewsApiFactory::class, function ($app) {
            return new NewsApiFactory();
        });

        // Array of interface to implementation bindings
        $bindings = [
            AuthService::class => AuthServiceImpl::class,
            ArticleService::class => ArticleServiceImpl::class,
            UserPreferenceService::class => UserPreferenceServiceImpl::class, // Fixed the binding
        ];

        // Bind all interfaces to their implementations
        foreach ($bindings as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
