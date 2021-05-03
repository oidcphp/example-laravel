<?php

namespace App\OpenIDConnect\Client\Concerns;

use Jose\Component\KeyManagement\JWKFactory;
use MilesChou\Psr\Http\Client\HttpClientInterface;
use OpenIDConnect\Client as OpenIDConnectClient;
use OpenIDConnect\Config;
use OpenIDConnect\Metadata\ClientMetadata;
use OpenIDConnect\Metadata\ProviderMetadata;

trait Line
{
    public function createLineDriver(): OpenIDConnectClient
    {
        $provider = new ProviderMetadata(
            $this->config->get('openid_connect.line.configuration'),
            $this->config->get('openid_connect.line.jwk_set')
        );

        $client = new ClientMetadata([
            'client_id' => $this->config->get('services.line.client_id'),
            'client_secret' => $this->config->get('services.line.client_secret'),
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

