<?php

declare(strict_types=1);

namespace Statum\Sdk\Tests\Unit;

use Statum\Sdk\DTO\ApiResponse;
use Statum\Sdk\Exceptions\ApiException;
use Statum\Sdk\Http\HttpClient;
use Statum\Sdk\Services\SmsService;
use Statum\Sdk\Tests\TestCase;

class SmsServiceTest extends TestCase
{
    public function test_send_sms_successfully(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with('POST', 'sms', $this->arrayHasKey('json'))
            ->willReturn([
                'status_code' => 200,
                'description' => 'Operation successful.',
                'request_id' => 'test-sms-id'
            ]);

        $service = new SmsService($httpClient);
        $response = $service->sendSms('+254700000000', 'SENDER', 'Message content');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertEquals(200, $response->statusCode);
        $this->assertEquals('test-sms-id', $response->requestId);
    }

    public function test_send_sms_throws_exception_on_invalid_phone(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $service = new SmsService($httpClient);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid phone number format');
        $service->sendSms('123', 'SENDER', 'Message');
    }

    public function test_send_sms_throws_exception_on_empty_sender(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $service = new SmsService($httpClient);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Sender ID cannot be empty');
        $service->sendSms('+254700000000', '', 'Message');
    }

    public function test_send_sms_throws_api_exception(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient->method('request')->willThrowException(new ApiException('Internal Error', 500));

        $service = new SmsService($httpClient);

        $this->expectException(ApiException::class);
        $service->sendSms('+254700000000', 'SENDER', 'Message');
    }
}
