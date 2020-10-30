<?php

namespace App\OpenIDConnect\Client;

use Illuminate\Support\Manager as BaseManager;
use Jose\Component\KeyManagement\JWKFactory;
use OpenIDConnect\Core\Client as OpenIDConnectClient;
use OpenIDConnect\Core\Issuer;
use OpenIDConnect\OAuth2\Metadata\ClientInformation;
use OpenIDConnect\OAuth2\Metadata\JwkSet;
use OpenIDConnect\OAuth2\Metadata\ProviderMetadata;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

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
            config('openid_connect.line.configuration'),
            new JwkSet(config('openid_connect.line.jwk_set'))
        );

        $client = new ClientInformation([
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

        return new OpenIDConnectClient($provider, $client, $this->container);
    }
}
