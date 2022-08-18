<?php

namespace App\Http\Controllers\Office365;

use App\OpenIDConnect\Client\Manager as OpenIDConnect;
use Illuminate\Http\Request;
use OpenIDConnect\Client as OpenIDConnectClient;
use Psr\Http\Message\ResponseInterface;

class Login
{
    public function __invoke(Request $request, OpenIDConnect $manager): ResponseInterface
    {
        /** @var OpenIDConnectClient $provider */
        $provider = $manager->driver('office365');

        $response = $provider->createAuthorizeRedirectResponse([
            'response_type' => 'code',
            'scope' => 'openid profile',
            'redirect_uri' => config('services.office365.redirect_uri'),
        ]);

        $request->session()->put('state', $provider->getState());

        return $response;
    }
}
