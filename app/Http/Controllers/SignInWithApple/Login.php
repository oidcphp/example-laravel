<?php

namespace App\Http\Controllers\SignInWithApple;

use App\OpenIDConnect\Client\Manager as OpenIDConnect;
use Illuminate\Http\Request;
use OpenIDConnect\Client as OpenIDConnectClient;
use Psr\Http\Message\ResponseInterface;

class Login
{
    public function __invoke(Request $request, OpenIDConnect $manager): ResponseInterface
    {
        /** @var OpenIDConnectClient $provider */
        $provider = $manager->driver('SignInWithApple');

        $response = $provider->createAuthorizeRedirectResponse([
            'response_mode' => 'form_post',
            'response_type' => 'code',
            'redirect_uri' => config('services.sign_in_with_apple.redirect_uri'),
            'scope' => 'openid name email',
        ]);

        $request->session()->put('state', $provider->getState());

        return $response;
    }
}
