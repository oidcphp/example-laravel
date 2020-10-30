<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenIDConnect\Core\Token\TokenFactory;
use OpenIDConnect\OAuth2\Token\TokenFactoryInterface;

class OpenIDConnectServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(TokenFactoryInterface::class, function() {
            return new TokenFactory();
        });
    }
}
