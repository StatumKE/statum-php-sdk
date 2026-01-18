<?php

declare(strict_types=1);

namespace Statum\Sdk\Config;

class StatumConfig
{
    private const DEFAULT_BASE_URL = 'https://api.statum.co.ke/api/v2';

    public function __construct(
        private readonly string $consumerKey,
        private readonly string $consumerSecret,
        private readonly string $baseUrl = self::DEFAULT_BASE_URL
    ) {
    }

    public function getConsumerKey(): string
    {
        return $this->consumerKey;
    }

    public function getConsumerSecret(): string
    {
        return $this->consumerSecret;
    }

    public function getBaseUrl(): string
    {
        return rtrim($this->baseUrl, '/');
    }

    public function getAuthHeader(): string
    {
        return 'Basic ' . base64_encode($this->consumerKey . ':' . $this->consumerSecret);
    }
}
