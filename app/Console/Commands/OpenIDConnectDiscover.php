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

    protected $signature = 'oidc:discover';

    protected $description = 'Download third party auth verify keys';

    public function handle()
    {
        file_put_contents(App::configPath('openid_connect.php'), sprintf(self::TEMPLATE, var_export([
            'line' => $this->discoverLine(),
        ], true)));

        return 0;
    }

    private function discoverLine(): array
    {
        $uri = 'https://access.line.me';

        $provider = (new Issuer($uri))->discover();
        $this->output->writeln('Download Line keys OK');

        return $provider->toArray();
    }
}
