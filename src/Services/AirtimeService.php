<?php

declare(strict_types=1);

namespace Statum\Sdk\Services;

use Statum\Sdk\DTO\ApiResponse;
use Statum\Sdk\Http\HttpClient;

class AirtimeService
{
    public function __construct(private readonly HttpClient $httpClient)
    {
    }

    /**
     * Send airtime to a phone number.
     *
     * @param string $phoneNumber Recipient phone number in international format.
     * @param string $amount Amount in KES.
     * @return ApiResponse
     * @throws \Statum\Sdk\Exceptions\ApiException
     */
    public function sendAirtime(string $phoneNumber, string $amount): ApiResponse
    {
        $response = $this->httpClient->request('POST', '/airtime', [
            'json' => [
                'phone_number' => $phoneNumber,
                'amount' => $amount,
            ],
        ]);

        return ApiResponse::fromArray($response);
    }
}
