<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\OpenIDConnect\Client\Manager as OpenIDConnect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use OpenIDConnect\Client as OpenIDConnectClient;
use OpenIDConnect\Token\TokenSet;
use RuntimeException;

class LineController extends BaseController
{
    public function login(Request $request, OpenIDConnect $manager)
    {
        /** @var OpenIDConnectClient $line */
        $line = $manager->driver('Line');

        $authorizationUrl = $line->getAuthorizationUrl([
            'response_type' => 'code',
            'scope' => 'openid profile',
        ]);

        $request->session()->put('line.state', $line->getState());

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
                'state' => $session->get('line.state'),
            ]);
        } catch (IdentityProviderException $e) {
            Log::error('Token endpoint return some error when perform Line', [
                'response' => $e->getResponseBody(),
            ]);

            throw new RuntimeException('Line return error', 0, $e);
        }

        Log::debug('Token response content from Line', $tokenSet->jsonSerialize());

        $idToken = $tokenSet->idToken();

        Log::debug('Claims in verified ID token', $idToken->all());

        dump($tokenSet->jsonSerialize());

        dump($idToken->all());

        $session->flush();
    }
}
