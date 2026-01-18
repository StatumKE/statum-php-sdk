# Statum PHP SDK

[![Tests](https://github.com/statum/statum-php-sdk/actions/workflows/tests.yml/badge.svg)](https://github.com/statum/statum-php-sdk/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/statum/statum-php-sdk/v/stable)](https://packagist.org/packages/statum/statum-php-sdk)
[![License](https://poser.pugx.org/statum/statum-php-sdk/license)](https://packagist.org/packages/statum/statum-php-sdk)

Official PHP SDK for Statum APIs (SMS, Airtime, Account). Built for enterprise usage with strict typing and immutable DTOs.

## Installation

You can install the package via composer:

```bash
composer require statum/statum-php-sdk
```

## Authentication

The SDK uses HTTP Basic Authentication. You need your `consumerKey` and `consumerSecret` from the Statum Dashboard.

```php
use Statum\Sdk\StatumClient;

$client = StatumClient::create(
    consumerKey: 'your_consumer_key',
    consumerSecret: 'your_consumer_secret'
);
```

## Usage

### Airtime

Send airtime to a phone number (KES).

```php
$response = $client->airtime()->sendAirtime(
    phoneNumber: '+254712345678',
    amount: '100.00'
);

echo "Request ID: " . $response->requestId;
echo "Status: " . $response->description;
```

### SMS

Send an SMS message using an approved Sender ID.

```php
$response = $client->sms()->sendSms(
    phoneNumber: '+254712345678',
    senderId: 'STATUM',
    message: 'Hello from Statum SDK!'
);

echo "Request ID: " . $response->requestId;
```

### Account Details

Fetch organization and balance details.

```php
$response = $client->account()->getAccountDetails();

echo "Organization: " . $response->organization->name;
echo "Available Balance: " . $response->organization->details->availableBalance;
```

## Error Handling

All errors throw a subclass of `Statum\Sdk\Exceptions\ApiException`.

```php
use Statum\Sdk\Exceptions\AuthenticationException;
use Statum\Sdk\Exceptions\ApiException;

try {
    $client->airtime()->sendAirtime('+254712345678', '10.00');
} catch (AuthenticationException $e) {
    // Handle invalid credentials
} catch (ApiException $e) {
    // Handle general API errors
    echo "HTTP Status: " . $e->getCode();
    echo "Body: " . $e->getResponseBody();
}
```

## Laravel Integration

You can easily bind the client in your `AppServiceProvider`.

```php
public function register()
{
    $this->app->singleton(StatumClient::class, function ($app) {
        return StatumClient::create(
            config('services.statum.key'),
            config('services.statum.secret')
        );
    });
}
```

## Security

Please review [SECURITY.md](SECURITY.md) for vulnerability reporting. Credentials should never be hardcoded; use environment variables.

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
