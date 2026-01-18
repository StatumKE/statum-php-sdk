# Statum PHP SDK

[![Tests](https://github.com/statum/statum-php-sdk/actions/workflows/tests.yml/badge.svg)](https://github.com/statum/statum-php-sdk/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/statum/statum-php-sdk/v/stable)](https://packagist.org/packages/statum/statum-php-sdk)
[![License](https://poser.pugx.org/statum/statum-php-sdk/license)](https://packagist.org/packages/statum/statum-php-sdk)

Official PHP SDK for Statum APIs (SMS, Airtime, Account). Built for enterprise usage with strict typing and immutable DTOs.

## Requirements

- PHP 8.2 or higher
- Guzzle HTTP Client

## Installation

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

### Configuration Options

You can also customize the base URL and timeout:

```php
use Statum\Sdk\Config\StatumConfig;
use Statum\Sdk\StatumClient;

$config = new StatumConfig(
    consumerKey: 'your_key',
    consumerSecret: 'your_secret',
    baseUrl: 'https://api.statum.co.ke/api/v2',  // Optional
    timeout: 30.0  // Optional, in seconds
);

$client = new StatumClient($config);
```

## Usage

### Phone Number Formats

The SDK accepts phone numbers in these formats:
- `+254712345678` (with country code prefix)
- `254712345678` (without + prefix)

### Airtime

Send airtime to a phone number (KES 5 - 10,000).

```php
$response = $client->airtime()->sendAirtime(
    phoneNumber: '254712345678',
    amount: '100'
);

echo "Status Code: " . $response->statusCode;
echo "Description: " . $response->description;
echo "Request ID: " . $response->requestId;
```

### SMS

Send an SMS message using an approved Sender ID.

```php
$response = $client->sms()->sendSms(
    phoneNumber: '254712345678',
    senderId: 'STATUM',
    message: 'Hello from Statum SDK!'
);

echo "Status Code: " . $response->statusCode;
echo "Description: " . $response->description;
echo "Request ID: " . $response->requestId;
```

### Account Details

Fetch organization and balance details.

```php
$response = $client->account()->getAccountDetails();

echo "Status Code: " . $response->statusCode;
echo "Organization: " . $response->organization->name;
echo "Available Balance: KES " . $response->organization->details->availableBalance;
echo "Website: " . $response->organization->details->website;
echo "M-Pesa Top Up Code: " . $response->organization->details->mpesaAccountTopUpCode;

// List service accounts
foreach ($response->organization->accounts as $account) {
    echo $account->account . " (" . $account->serviceName . ")";
}
```

## API Response Format

All API responses follow a consistent JSON structure:

### Success Response (200)
```json
{
    "status_code": 200,
    "description": "Operation successful.",
    "request_id": "35235f08c981474abd388755ed43a427"
}
```

### Insufficient Funds (402)
```json
{
    "status_code": 402,
    "description": "Insufficient funds.",
    "request_id": "ddc8fadc-f065-4736-aa91-14a42e36c1fa"
}
```

### Validation Error (422)
```json
{
    "status_code": 422,
    "description": "Validation failed.",
    "validation_errors": {
        "phone_number": ["The phone number must be between 10 and 12 digits."]
    },
    "request_id": "207c5782-f2c6-4a5e-b893-bc7b74aea45f"
}
```

### Account Details Response (200)
```json
{
    "status_code": 200,
    "description": "Operation successful.",
    "request_id": "5a45bc7b-bf99-49ae-b089-9daf5f4adbb0",
    "organization": {
        "name": "Statum Test",
        "details": {
            "available_balance": 695.15,
            "location": "Nairobi - Westlands",
            "website": "www.statum.co.ke",
            "office_email": "admin@statum.co.ke",
            "office_mobile": "+254722199199",
            "mpesa_account_top_up_code": "B9E573"
        },
        "accounts": [
            { "account": "Statum", "service_name": "sms" },
            { "account": "CONNECT", "service_name": "sms" }
        ]
    }
}
```

## Error Handling

All errors throw a subclass of `Statum\Sdk\Exceptions\ApiException`.

```php
use Statum\Sdk\Exceptions\AuthenticationException;
use Statum\Sdk\Exceptions\ValidationException;
use Statum\Sdk\Exceptions\NetworkException;
use Statum\Sdk\Exceptions\ApiException;

try {
    $client->airtime()->sendAirtime('254712345678', '100');
} catch (AuthenticationException $e) {
    // Invalid credentials (401)
} catch (ValidationException $e) {
    // Validation errors from API (422)
    echo "Request ID: " . $e->getRequestId();
    foreach ($e->getValidationErrors() as $field => $errors) {
        echo "$field: " . implode(', ', $errors);
    }
} catch (NetworkException $e) {
    // Connection/timeout issues
} catch (ApiException $e) {
    // General API errors
    echo "HTTP Status: " . $e->getCode();
    echo "Body: " . $e->getResponseBody();
}
```

## Laravel Integration

Bind the client in your `AppServiceProvider`:

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
