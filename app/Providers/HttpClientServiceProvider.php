<?php

namespace App\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use MilesChou\Psr\Http\Message\RequestFactory;
use MilesChou\Psr\Http\Message\ResponseFactory;
use MilesChou\Psr\Http\Message\ServerRequestFactory;
use MilesChou\Psr\Http\Message\StreamFactory;
use MilesChou\Psr\Http\Message\UploadedFileFactory;
use MilesChou\Psr\Http\Message\UriFactory;
use Psr\Http\Client\ClientInterface as Psr18ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Psr18Client;

class HttpClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function provides()
    {
        return [
            // PSR-18 Http Client
            Psr18ClientInterface::class,
            // PSR-17 HTTP Factories
            RequestFactoryInterface::class,
            ResponseFactoryInterface::class,
            ServerRequestFactoryInterface::class,
            StreamFactoryInterface::class,
            UploadedFileFactoryInterface::class,
            UriFactoryInterface::class,
        ];
    }

    public function register()
    {
        $this->registerPsr18HttpClient();

        $this->registerPsr18HttpFactories();
    }

    private function registerPsr18HttpClient(): void
    {
        $this->app->singleton(Psr18Client::class, function () {
            return new Psr18Client(
                HttpClient::create(),
                $this->app->make(ResponseFactory::class),
                $this->app->make(StreamFactory::class)
            );
        });

        $this->app->bind(Psr18ClientInterface::class, Psr18Client::class);
    }

    private function registerPsr18HttpFactories(): void
    {
        $this->app->singleton(RequestFactoryInterface::class, RequestFactory::class);
        $this->app->singleton(ResponseFactoryInterface::class, ResponseFactory::class);
        $this->app->singleton(ServerRequestFactoryInterface::class, ServerRequestFactory::class);
        $this->app->singleton(StreamFactoryInterface::class, StreamFactory::class);
        $this->app->singleton(UploadedFileFactoryInterface::class, UploadedFileFactory::class);
        $this->app->singleton(UriFactoryInterface::class, UriFactory::class);
    }
}
