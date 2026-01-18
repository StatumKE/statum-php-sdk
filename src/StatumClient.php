<?php

declare(strict_types=1);

namespace Statum\Sdk;

use Statum\Sdk\Config\StatumConfig;
use Statum\Sdk\Http\HttpClient;
use Statum\Sdk\Services\AccountService;
use Statum\Sdk\Services\AirtimeService;
use Statum\Sdk\Services\SmsService;

class StatumClient
{
    private HttpClient $httpClient;
    private ?AirtimeService $airtime = null;
    private ?SmsService $sms = null;
    private ?AccountService $account = null;

    public function __construct(private readonly StatumConfig $config)
    {
        $this->httpClient = new HttpClient($this->config);
    }

    /**
     * Static factory method to create a client with credentials.
     */
    public static function create(string $consumerKey, string $consumerSecret, ?string $baseUrl = null): self
    {
        $config = new StatumConfig($consumerKey, $consumerSecret, $baseUrl ?? 'https://api.statum.co.ke/api/v2');
        return new self($config);
    }

    public function airtime(): AirtimeService
    {
        if ($this->airtime === null) {
            $this->airtime = new AirtimeService($this->httpClient);
        }

        return $this->airtime;
    }

    public function sms(): SmsService
    {
        if ($this->sms === null) {
            $this->sms = new SmsService($this->httpClient);
        }

        return $this->sms;
    }

    public function account(): AccountService
    {
        if ($this->account === null) {
            $this->account = new AccountService($this->httpClient);
        }

        return $this->account;
    }
}
