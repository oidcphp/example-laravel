<?php

namespace App\OpenIDConnect\Client;

use Illuminate\Support\Manager as BaseManager;
use Jose\Component\KeyManagement\JWKFactory;
use MilesChou\Psr\Http\Client\HttpClientInterface;
use OpenIDConnect\Client as OpenIDConnectClient;
use OpenIDConnect\Config;
use OpenIDConnect\Jwt\JwkSet;
use OpenIDConnect\Metadata\ClientMetadata;
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
        $httpClient = $this->container->make(HttpClientInterface::class);

        $provider = new ProviderMetadata(
            config('openid_connect.line.configuration'),
            new JwkSet(config('openid_connect.line.jwk_set'))
        );

        $client = new ClientMetadata([
            'client_id' => config('services.line.client_id'),
            'client_secret' => config('services.line.client_secret'),
            'redirect_uris' => [
                config('services.line.redirect_uri'),
            ],
        ]);

        // Addition JWK for LINE
        $provider->addJwk(JWKFactory::createFromSecret($client->secret(), [
            'alg' => 'HS256',
        ])->all());

        $config = new Config($provider, $client);

        return new OpenIDConnectClient($config, $httpClient);
    }
}
