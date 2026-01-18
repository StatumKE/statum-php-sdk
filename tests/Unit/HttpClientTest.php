<?php

declare(strict_types=1);

namespace Statum\Sdk\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use Statum\Sdk\Config\StatumConfig;
use Statum\Sdk\Exceptions\ApiException;
use Statum\Sdk\Exceptions\AuthenticationException;
use Statum\Sdk\Exceptions\AuthorizationException;
use Statum\Sdk\Exceptions\NetworkException;
use Statum\Sdk\Exceptions\ValidationException;
use Statum\Sdk\Http\HttpClient;
use Statum\Sdk\Tests\TestCase;

class HttpClientTest extends TestCase
{
    public function test_request_success(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['foo' => 'bar']))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $config = new StatumConfig('key', 'secret');
        $httpClient = new HttpClient($config, $client);

        $response = $httpClient->request('GET', 'test');
        $this->assertEquals(['foo' => 'bar'], $response);
    }

    public function test_request_throws_authentication_exception(): void
    {
        $mock = new MockHandler([
            new Response(401, [], json_encode(['error' => 'Unauthorized']))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $config = new StatumConfig('key', 'secret');
        $httpClient = new HttpClient($config, $client);

        $this->expectException(AuthenticationException::class);
        $httpClient->request('GET', 'test');
    }

    public function test_request_throws_authorization_exception(): void
    {
        $mock = new MockHandler([
            new Response(403, [], json_encode(['error' => 'Forbidden']))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $config = new StatumConfig('key', 'secret');
        $httpClient = new HttpClient($config, $client);

        $this->expectException(AuthorizationException::class);
        $httpClient->request('GET', 'test');
    }

    public function test_request_throws_network_exception(): void
    {
        $mock = new MockHandler([
            new ConnectException('Error communicating with server', new Request('GET', 'test'))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $config = new StatumConfig('key', 'secret');
        $httpClient = new HttpClient($config, $client);

        $this->expectException(NetworkException::class);
        $httpClient->request('GET', 'test');
    }

    public function test_request_throws_api_exception_on_malformed_json(): void
    {
        $mock = new MockHandler([
            new Response(200, [], 'not json')
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $config = new StatumConfig('key', 'secret');
        $httpClient = new HttpClient($config, $client);

        $this->expectException(ApiException::class);
        $httpClient->request('GET', 'test');
    }

    public function test_request_throws_validation_exception_on_422(): void
    {
        $responseBody = json_encode([
            'status_code' => 422,
            'description' => 'Validation failed.',
            'validation_errors' => [
                'phone_number' => ['The phone number must be between 10 and 12 digits.']
            ],
            'request_id' => 'test-request-id-422'
        ]);

        $mock = new MockHandler([
            new Response(422, [], $responseBody)
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $config = new StatumConfig('key', 'secret');
        $httpClient = new HttpClient($config, $client);

        try {
            $httpClient->request('POST', 'sms');
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationException $e) {
            $this->assertEquals(422, $e->getCode());
            $this->assertEquals('Validation failed.', $e->getMessage());
            $this->assertEquals('test-request-id-422', $e->getRequestId());
            $this->assertArrayHasKey('phone_number', $e->getValidationErrors());
            $this->assertContains('The phone number must be between 10 and 12 digits.', $e->getValidationErrors()['phone_number']);
        }
    }
}
