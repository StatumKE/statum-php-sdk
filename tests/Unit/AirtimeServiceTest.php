<?php

declare(strict_types=1);

namespace Statum\Sdk\Tests\Unit;

use Statum\Sdk\DTO\ApiResponse;
use Statum\Sdk\Exceptions\AuthenticationException;
use Statum\Sdk\Exceptions\NetworkException;
use Statum\Sdk\Http\HttpClient;
use Statum\Sdk\Services\AirtimeService;
use Statum\Sdk\Tests\TestCase;

class AirtimeServiceTest extends TestCase
{
    public function test_send_airtime_successfully(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with('POST', '/airtime', $this->arrayHasKey('json'))
            ->willReturn([
                'status_code' => 200,
                'description' => 'Operation successful.',
                'request_id' => 'test-request-id'
            ]);

        $service = new AirtimeService($httpClient);
        $response = $service->sendAirtime('+254700000000', '100');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertEquals(200, $response->statusCode);
        $this->assertEquals('Operation successful.', $response->description);
        $this->assertEquals('test-request-id', $response->requestId);
    }

    public function test_send_airtime_throws_exception_on_invalid_phone(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $service = new AirtimeService($httpClient);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid phone number format');
        $service->sendAirtime('123', '100');
    }

    public function test_send_airtime_throws_exception_on_invalid_amount(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $service = new AirtimeService($httpClient);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount must be between 5 and 10,000 KES');
        $service->sendAirtime('+254700000000', '3');
    }

    public function test_send_airtime_throws_authentication_exception(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient->method('request')->willThrowException(new AuthenticationException('Invalid credentials.', 401));

        $service = new AirtimeService($httpClient);

        $this->expectException(AuthenticationException::class);
        $service->sendAirtime('+254700000000', '100');
    }

    public function test_send_airtime_throws_network_exception(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient->method('request')->willThrowException(new NetworkException('Connection timeout.', 0));

        $service = new AirtimeService($httpClient);

        $this->expectException(NetworkException::class);
        $service->sendAirtime('+254700000000', '100');
    }
}
