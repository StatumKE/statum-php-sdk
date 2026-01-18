<?php

declare(strict_types=1);

namespace Statum\Sdk\Services;

use Statum\Sdk\DTO\AccountDetailsResponse;
use Statum\Sdk\Http\HttpClient;

class AccountService
{
    public function __construct(private readonly HttpClient $httpClient)
    {
    }

    /**
     * Get account and organization details.
     *
     * @return AccountDetailsResponse
     * @throws \Statum\Sdk\Exceptions\ApiException
     */
    public function getAccountDetails(): AccountDetailsResponse
    {
        $response = $this->httpClient->request('GET', 'account-details');

        return AccountDetailsResponse::fromArray($response);
    }
}
