<?php

namespace App\OpenIDConnect\Client\Concerns;

use Jose\Component\KeyManagement\JWKFactory;
use MilesChou\Psr\Http\Client\HttpClientInterface;
use OpenIDConnect\Client as OpenIDConnectClient;
use OpenIDConnect\Config;
use OpenIDConnect\Jwt\JwkSet;
use OpenIDConnect\Metadata\ClientMetadata;
use OpenIDConnect\Metadata\ProviderMetadata;

trait Line
{
    public function createLineDriver(): OpenIDConnectClient
    {
        $provider = new ProviderMetadata(
            config('openid_connect.line.configuration'),
            new JwkSet(config('openid_connect.line.jwk_set'))
        );

        $client = new ClientMetadata([
            'client_id' => config('services.line.client_id'),
            'client_secret' => config('services.line.client_secret'),
        ]);

        // Addition JWK for LINE
        $provider->addJwk(JWKFactory::createFromSecret($client->secret(), [
            'alg' => 'HS256',
        ])->all());

        return new OpenIDConnectClient(
            new Config($provider, $client),
            $this->container->make(HttpClientInterface::class)
        );
    }
}

