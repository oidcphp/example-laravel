<?php

namespace App\OpenIDConnect\Client;

use Illuminate\Support\Manager as BaseManager;

class Manager extends BaseManager
{
    use Concerns\Line;
    use Concerns\SignInWithApple;
    use Concerns\Office365;

    public function getDefaultDriver()
    {
        throw new \LogicException('No default driver');
    }
}
