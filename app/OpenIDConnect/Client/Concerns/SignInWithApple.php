<?php

namespace App\OpenIDConnect\Client\Concerns;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\Util\JsonConverter;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\Algorithm\ES256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;
use MilesChou\Psr\Http\Client\HttpClientInterface;
use OpenIDConnect\Client as OpenIDConnectClient;
use OpenIDConnect\Config;
use OpenIDConnect\Http\Authentication\ClientSecretPost;
use OpenIDConnect\Jwt\JwkSet;
use OpenIDConnect\Metadata\ClientMetadata;
use OpenIDConnect\Metadata\ProviderMetadata;

trait SignInWithApple
{
    public function createSignInWithAppleDriver(): OpenIDConnectClient
    {
        $provider = new ProviderMetadata(
            $this->config->get('openid_connect.sign_in_with_apple.configuration'),
            new JwkSet(config('openid_connect.sign_in_with_apple.jwk_set'))
        );

        $clientId = $this->config->get('services.sign_in_with_apple.client_id');
        $clientSecret = $this->generateSignInWithAppleSecret();

        $client = new ClientMetadata([
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ]);

        $openidConnectClient = new OpenIDConnectClient(
            new Config($provider, $client),
            $this->container->make(HttpClientInterface::class)
        );

        $openidConnectClient->setClientAuthentication(
            new ClientSecretPost(
                $clientId,
                $clientSecret
            )
        );

        return $openidConnectClient;
    }

    protected function generateSignInWithAppleSecret(): string
    {
        $privateKey = base64_decode($this->config->get('services.sign_in_with_apple.private_key'));
        $keyId = $this->config->get('services.sign_in_with_apple.key_id');

        $jwk = JWKFactory::createFromKey($privateKey, null, [
            'kid' => $keyId,
        ]);

        $build = new JWSBuilder(new AlgorithmManager([
            new ES256(),
        ]));

        $arr = [
            'aud' => 'https://appleid.apple.com',
            'exp' => time() + 60,
            'iat' => time(),
            'iss' => $this->config->get('services.sign_in_with_apple.team_id'),
            'sub' => $this->config->get('services.sign_in_with_apple.client_id'),
        ];

        $jws = $build->withPayload(JsonConverter::encode($arr))
            ->addSignature($jwk, ['kid' => $keyId, 'alg' => 'ES256'])
            ->build();

        return (new CompactSerializer())->serialize($jws);
    }
}

