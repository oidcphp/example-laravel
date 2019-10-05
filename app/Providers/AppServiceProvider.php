<?php

namespace App\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Http\Factory\Guzzle\UriFactory;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Message\UriFactoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UriFactoryInterface::class, UriFactory::class);

        $this->app->singleton(ClientInterface::class, Client::class);
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
