<?php

namespace App\Http\Controllers\Office365;

use App\OpenIDConnect\Client\Manager as OpenIDConnect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenIDConnect\Client as OpenIDConnectClient;
use OpenIDConnect\Exceptions\OpenIDProviderException;
use RuntimeException;

class Callback
{
    public function __invoke(Request $request, OpenIDConnect $manager)
    {
        $session = $request->session();

        /** @var OpenIDConnectClient $provider */
        $provider = $manager->driver('office365');

        try {
            $tokenSet = $provider->handleCallback($request->all(), [
                'state' => $session->get('state'),
                'redirect_uri' => config('services.office365.redirect_uri'),
            ]);
        } catch (OpenIDProviderException $e) {
            Log::error('Token endpoint return some error when perform Office 365');

            throw new RuntimeException('Office 365 return error', 0, $e);
        }

        dump($tokenSet->jsonSerialize());

        $idToken = $tokenSet->idTokenClaims();

        dump($idToken->all());

        $session->flush();
    }
}
