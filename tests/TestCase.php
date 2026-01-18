<?php

declare(strict_types=1);

namespace Statum\Sdk\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Statum\Sdk\StatumClient;

abstract class TestCase extends BaseTestCase
{
    protected function getClient(string $key = 'test_key', string $secret = 'test_secret'): StatumClient
    {
        return StatumClient::create($key, $secret);
    }
}
