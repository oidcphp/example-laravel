<?php

namespace App\Http\Controllers;

use App\OpenIDConnect\Client\Manager as OpenIDConnect;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use OpenIDConnect\Client as OpenIDConnectClient;
use OpenIDConnect\Exceptions\OpenIDProviderException;
use OpenIDConnect\Token\TokenSet;
use RuntimeException;

class LineController extends BaseController
{
    public function login(Request $request, OpenIDConnect $manager)
    {
        /** @var OpenIDConnectClient $line */
        $line = $manager->driver('Line');

        $authorizationUrl = $line->getAuthorizationUri([
            'response_type' => 'code',
            'scope' => 'openid profile',
            'redirect_uri' => config('services.line.redirect_uri'),
        ]);

        $request->session()->put('state', $line->getState());

        return redirect()->away($authorizationUrl);
    }

    public function callback(Request $request, OpenIDConnect $manager)
    {
        $session = $request->session();

        /** @var OpenIDConnectClient $openIDConnectDriver */
        $openIDConnectDriver = $manager->driver('Line');

        try {
            /** @var TokenSet $tokenSet */
            $tokenSet = $openIDConnectDriver->handleOpenIDConnectCallback($request->all(), [
                'state' => $session->get('state'),
                'redirect_uri' => config('services.line.redirect_uri'),
            ]);
        } catch (OpenIDProviderException $e) {
            Log::error('Token endpoint return some error when perform Line');

            throw new RuntimeException('Line return error', 0, $e);
        }

        dump($tokenSet->jsonSerialize());

        $idToken = $tokenSet->idTokenClaims();

        dump($idToken->all());

        $session->flush();
    }
}
