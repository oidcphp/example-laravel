<?php

namespace App\OpenIDConnect\Client;

use Illuminate\Support\Manager as BaseManager;

class Manager extends BaseManager
{
    use Concerns\Line;
    use Concerns\SignInWithApple;

    public function getDefaultDriver()
    {
        throw new \LogicException('No default driver');
    }
}
