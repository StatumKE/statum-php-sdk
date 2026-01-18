<?php

declare(strict_types=1);

namespace Statum\Sdk\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Statum\Sdk\Config\StatumConfig;
use Statum\Sdk\DTO\ApiResponse;
use Statum\Sdk\Http\HttpClient;
use Statum\Sdk\Services\AirtimeService;
use Statum\Sdk\Tests\TestCase;

class AirtimeServiceTest extends TestCase
{
    public function test_send_airtime_successfully(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'status_code' => 200,
                'description' => 'Operation successful.',
                'request_id' => 'test-request-id'
            ]))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $config = new StatumConfig('key', 'secret');

        // We need to inject the mock client into HttpClient.
        // For testing, we'll use a reflection or just mock the HttpClient.
        // Given the constraints, I'll use a more direct approach by extending HttpClient or using a factory.
        // Actually, I'll mock HttpClient for service tests, and test HttpClient separately.

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
}
