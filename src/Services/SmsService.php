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
     * @throws \InvalidArgumentException
     * @throws \Statum\Sdk\Exceptions\ApiException
     */
    public function sendSms(string $phoneNumber, string $senderId, string $message): ApiResponse
    {
        if (!preg_match('/^\+?\d{10,15}$/', $phoneNumber)) {
            throw new \InvalidArgumentException('Invalid phone number format. Must be in international format (e.g., +254712345678 or 254712345678).');
        }

        if (empty(trim($senderId))) {
            throw new \InvalidArgumentException('Sender ID cannot be empty.');
        }

        if (empty(trim($message))) {
            throw new \InvalidArgumentException('Message cannot be empty.');
        }

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
