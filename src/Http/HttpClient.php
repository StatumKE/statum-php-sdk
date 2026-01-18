<?php

declare(strict_types=1);

namespace Statum\Sdk\Http;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Statum\Sdk\Config\StatumConfig;
use Statum\Sdk\Exceptions\ApiException;
use Statum\Sdk\Exceptions\AuthenticationException;
use Statum\Sdk\Exceptions\AuthorizationException;
use Statum\Sdk\Exceptions\NetworkException;
use Statum\Sdk\Exceptions\ValidationException;

class HttpClient
{
    private ClientInterface $client;

    public function __construct(
        private readonly StatumConfig $config,
        ?ClientInterface $client = null
    ) {
        $this->client = $client ?? new Client([
            'base_uri' => $this->config->getBaseUrl(),
            'headers' => [
                'Authorization' => $this->config->getAuthHeader(),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout' => $this->config->getTimeout(),
        ]);
    }

    public function request(string $method, string $path, array $options = []): array
    {
        try {
            $response = $this->client->request($method, $path, $options);
            $body = (string) $response->getBody();

            return json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (ConnectException $e) {
            throw new NetworkException($e->getMessage(), 0, null, $e);
        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 0;
            $body = $e->getResponse() ? (string) $e->getResponse()->getBody() : null;

            if ($statusCode === 422 && $body !== null) {
                $data = json_decode($body, true) ?? [];
                throw new ValidationException(
                    $data['description'] ?? 'Validation failed.',
                    $statusCode,
                    $data['validation_errors'] ?? [],
                    $data['request_id'] ?? '',
                    $body,
                    $e
                );
            }

            throw match ($statusCode) {
                401 => new AuthenticationException('Invalid credentials.', $statusCode, $body, $e),
                403 => new AuthorizationException('Access denied.', $statusCode, $body, $e),
                default => new ApiException($e->getMessage(), $statusCode, $body, $e),
            };
        } catch (\Throwable $e) {
            throw new ApiException($e->getMessage(), 0, null, $e);
        }
    }
}
