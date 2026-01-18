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
     * @throws \InvalidArgumentException
     * @throws \Statum\Sdk\Exceptions\ApiException
     */
    public function sendAirtime(string $phoneNumber, string $amount): ApiResponse
    {
        if (!preg_match('/^\+?\d{10,15}$/', $phoneNumber)) {
            throw new \InvalidArgumentException('Invalid phone number format. Must be in international format (e.g., +254712345678 or 254712345678).');
        }

        $amountFloat = is_numeric($amount) ? (float) $amount : -1;
        if ($amountFloat < 5 || $amountFloat > 10000) {
            throw new \InvalidArgumentException('Amount must be between 5 and 10,000 KES.');
        }

        $response = $this->httpClient->request('POST', 'airtime', [
            'json' => [
                'phone_number' => $phoneNumber,
                'amount' => $amount,
            ],
        ]);

        return ApiResponse::fromArray($response);
    }
}
