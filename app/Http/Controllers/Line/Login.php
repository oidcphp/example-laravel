<?php

namespace App\Http\Controllers\Line;

use App\OpenIDConnect\Client\Manager as OpenIDConnect;
use Illuminate\Http\Request;
use OpenIDConnect\Client as OpenIDConnectClient;
use Psr\Http\Message\ResponseInterface;

class Login
{
    public function __invoke(Request $request, OpenIDConnect $manager): ResponseInterface
    {
        /** @var OpenIDConnectClient $line */
        $line = $manager->driver('Line');

        $response = $line->createAuthorizeRedirectResponse([
            'response_type' => 'code',
            'scope' => 'openid profile',
            'redirect_uri' => config('services.line.redirect_uri'),
        ]);

        $request->session()->put('state', $line->getState());

        return $response;
    }
}
