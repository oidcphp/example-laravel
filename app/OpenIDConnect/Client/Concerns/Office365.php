<?php

namespace App\OpenIDConnect\Client\Concerns;

use Jose\Component\KeyManagement\JWKFactory;
use MilesChou\Psr\Http\Client\HttpClientInterface;
use OpenIDConnect\Client as OpenIDConnectClient;
use OpenIDConnect\Config;
use OpenIDConnect\Metadata\ClientMetadata;
use OpenIDConnect\Metadata\ProviderMetadata;

trait Office365
{
    public function createOffice365Driver(): OpenIDConnectClient
    {
        $this->updateIssuer();

        $provider = new ProviderMetadata(
            $this->config->get('openid_connect.office365.configuration'),
            $this->config->get('openid_connect.office365.jwk_set')
        );

        $client = new ClientMetadata([
            'client_id' => $this->config->get('services.office365.client_id'),
            'client_secret' => $this->config->get('services.office365.client_secret'),
            'scope' => 'https://graph.microsoft.com/.default',
            'grant_type' => 'client_credentials',
        ]);

        return new OpenIDConnectClient(
            new Config($provider, $client),
            $this->container->make(HttpClientInterface::class)
        );
    }

    private function updateIssuer(): void
    {
        $issuer = $this->config->get('openid_connect.office365.configuration.issuer');
        $tenantid = $this->config->get('services.office365.tenant_id');
        $this->config->set('openid_connect.office365.configuration.issuer', str_replace('{tenantid}', $tenantid, $issuer));
    }
}
