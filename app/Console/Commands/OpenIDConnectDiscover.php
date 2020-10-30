<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use OpenIDConnect\Issuer;

/**
 * 下載 OpenIDConnect 相關資訊
 */
class OpenIDConnectDiscover extends Command
{
    private const TEMPLATE = <<<EOF
<?php

return %s;

EOF;

    protected $signature = 'oidc:discover:line';

    protected $description = 'Download third party auth verify keys';

    public function handle(Issuer $issuer)
    {
        $uri = 'https://access.line.me/.well-known/openid-configuration';

        $provider = $issuer->discover($uri);

        file_put_contents(App::configPath('openid_connect.php'), sprintf(self::TEMPLATE, var_export([
            'line' => [
                'configuration' => $provider->toArray(),
                'jwk_set' => $provider->jwkSet()->toArray(),
            ],
        ], true)));

        $this->output->writeln('Download Line keys OK');

        return 0;
    }
}
