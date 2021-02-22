<?php

namespace App\Http\Controllers\SignInWithApple;

use App\OpenIDConnect\Client\Manager as OpenIDConnect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenIDConnect\Client as OpenIDConnectClient;
use OpenIDConnect\Exceptions\OpenIDProviderException;
use OpenIDConnect\Token\TokenSet;
use RuntimeException;

class Callback
{
    public function __invoke(Request $request, OpenIDConnect $manager)
    {
        $session = $request->session();

        /** @var OpenIDConnectClient $provider */
        $provider = $manager->driver('SignInWithApple');

        try {
            /** @var TokenSet $tokenSet */
            $tokenSet = $provider->handleCallback($request->all(), [
                'state' => $session->get('state'),
                'redirect_uri' => config('services.sign_in_with_apple.redirect_uri'),
            ]);
        } catch (OpenIDProviderException $e) {
            Log::error('Token endpoint return some error when perform SignInWithApple');

            throw new RuntimeException('SignInWithApple return error', 0, $e);
        }

        dump($tokenSet->jsonSerialize());

        $idToken = $tokenSet->idTokenClaims();

        dump($idToken->all());

        $session->flush();
    }
}
