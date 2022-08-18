<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use OpenIDConnect\Issuer;
use OpenIDConnect\Metadata\ProviderMetadata;

/**
 * 下載 OpenIDConnect 相關資訊
 */
class OpenIDConnectDiscover extends Command
{
    private const TEMPLATE = <<<EOF
<?php

return %s;

EOF;

    protected $signature = 'oidc:discover';

    protected $description = 'Download third party auth verify keys';

    public function handle(Issuer $issuer): int
    {
        file_put_contents(App::configPath('openid_connect.php'), sprintf(self::TEMPLATE, var_export([
            'google' => $this->downloadGoogleConfig($issuer),
            'line' => $this->downloadLineConfig($issuer),
            'office365' => $this->downloadOffice365Config($issuer),
            'sign_in_with_apple' => $this->downloadSignInWithAppleConfig($issuer),
        ], true)));

        $this->output->writeln('Download keys OK');

        return 0;
    }

    private function downloadLineConfig(Issuer $issuer): array
    {
        return $this->combine(
            $issuer->discover('https://access.line.me/.well-known/openid-configuration')
        );
    }

    private function downloadSignInWithAppleConfig(Issuer $issuer): array
    {
        return $this->combine(
            $issuer->discover('https://appleid.apple.com/.well-known/openid-configuration')
        );
    }

    private function downloadGoogleConfig(Issuer $issuer): array
    {
        return $this->combine(
            $issuer->discover('https://accounts.google.com/.well-known/openid-configuration')
        );
    }

    private function downloadOffice365Config(Issuer $issuer): array
    {
        return $this->combine(
            $issuer->discover('https://login.microsoftonline.com/common/.well-known/openid-configuration')
        );
    }

    private function combine(ProviderMetadata $provider): array
    {
        return [
            'configuration' => $provider->toArray(),
            'jwk_set' => $provider->jwkSet()->toArray(),
        ];
    }
}
