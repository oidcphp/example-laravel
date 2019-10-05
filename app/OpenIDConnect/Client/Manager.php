<?php

namespace App\OpenIDConnect\Client;

use Illuminate\Support\Manager as BaseManager;
use Jose\Component\KeyManagement\JWKFactory;
use OpenIDConnect\Client as OpenIDConnectClient;
use OpenIDConnect\Metadata\ClientRegistration;
use OpenIDConnect\Metadata\ProviderMetadata;

class Manager extends BaseManager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        throw new \LogicException('No default driver');
    }

    public function createLineDriver(): OpenIDConnectClient
    {
        $provider = new ProviderMetadata(
            config('openid_connect.line.discovery'),
            config('openid_connect.line.jwk')
        );

        $client = new ClientRegistration([
            'client_id' => config('services.line.client_id'),
            'client_secret' => config('services.line.client_secret'),
            'redirect_uris' => [
                config('services.line.redirect_uri'),
            ],
        ]);

        // Addition JWK for LINE
        $provider->withJwkInstances(JWKFactory::createFromSecret($client->secret(), [
            'alg' => 'HS256',
        ]));

        return new OpenIDConnectClient($provider, $client, $this->container);
    }
}
