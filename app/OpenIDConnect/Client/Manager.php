<?php

namespace App\OpenIDConnect\Client;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Manager as BaseManager;
use Jose\Component\KeyManagement\JWKFactory;
use League\OAuth2\Client\OptionProvider\HttpBasicAuthOptionProvider;
use OpenIDConnect\Client as OpenIDConnectClient;
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
        $provider = new ProviderMetadata(
            config('openid_connect.line.discovery'),
            config('openid_connect.line.jwk')
        );

        $client = new ClientMetadata([
            'client_id' => config('services.line.client_id'),
            'client_secret' => config('services.line.client_secret'),
            'redirect_uri' => config('services.line.redirect_uri'),
        ]);

        $options = [
            'httpClient' => $this->container->make(HttpClient::class),
            'optionProvider' => new HttpBasicAuthOptionProvider(),
        ];

        // Addition JWK for LINE
        $provider->withJwkInstances(JWKFactory::createFromSecret($client->secret(), [
            'alg' => 'HS256',
        ]));

        return new OpenIDConnectClient($provider, $client, $options);
    }
}
