<?php

declare(strict_types=1);

namespace Statum\Sdk\Services;

use Statum\Sdk\DTO\ApiResponse;
use Statum\Sdk\Http\HttpClient;

class SmsService
{
    public function __construct(private readonly HttpClient $httpClient)
    {
    }

    /**
     * Send an SMS message.
     *
     * @param string $phoneNumber Recipient phone number.
     * @param string $senderId Your approved Sender ID.
     * @param string $message The message content.
     * @return ApiResponse
     * @throws \Statum\Sdk\Exceptions\ApiException
     */
    public function sendSms(string $phoneNumber, string $senderId, string $message): ApiResponse
    {
        $response = $this->httpClient->request('POST', '/sms', [
            'json' => [
                'phone_number' => $phoneNumber,
                'sender_id' => $senderId,
                'message' => $message,
            ],
        ]);

        return ApiResponse::fromArray($response);
    }
}
