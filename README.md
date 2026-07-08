# Statum PHP SDK (SMS, Airtime, & Accounts)

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue.svg)](https://packagist.org/packages/statum/statum-php-sdk)
[![Latest Stable Version](https://img.shields.io/packagist/v/statum/statum-php-sdk.svg)](https://packagist.org/packages/statum/statum-php-sdk)
[![License](https://img.shields.io/github/license/StatumKE/statum-php-sdk.svg)](https://github.com/StatumKE/statum-php-sdk/blob/master/LICENSE)

Official PHP SDK for Statum APIs. Built for secure, production-grade enterprise usage with strict typing, immutable DTOs, and framework-agnostic Guzzle HTTP integrations. Easily send SMS alerts, automate airtime disbursements, and query real-time account balances.

---

## Table of Contents

- [Features](#features)
- [Getting Started](#getting-started)
- [Installation](#installation)
- [Quick Start in 2 Minutes](#quick-start-in-2-minutes)
  - [1. Plain PHP Setup](#1-plain-php-setup)
  - [2. Laravel Setup](#2-laravel-setup)
- [Core Integration Examples](#core-integration-examples)
  - [Account Details](#account-details)
  - [Sending SMS](#sending-sms)
  - [Disbursing Airtime](#disbursing-airtime)
- [Error & Exception Handling](#error--exception-handling)
- [API JSON Payload Specifications](#api-json-payload-specifications)
- [Integration Guidelines & Gotchas](#integration-guidelines--gotchas)
- [Running Tests](#running-tests)
- [License](#license)

---

## Features

- **Type-Safe Constructors**: Parameter validation happens locally inside the SDK before outgoing HTTP calls.
- **Service-Oriented Design**: Clean division between SMS, Airtime, and Account APIs.
- **Framework Agnostic**: Works out of the box in plain scripts, Symfony, or Laravel.
- **Extensive Exception Handling**: Maps specific HTTP status codes (e.g. 401, 402, 422) to concrete PHP exception classes.
- **Strict Typing**: Full compatibility with PHP 8.1+ strict mode.

---

## Getting Started

1. **Sign up for a Statum account**: [app.statum.co.ke](https://app.statum.co.ke)
2. **Get your API credentials**: Retrieve your Consumer Key and Consumer Secret from the [Statum Dashboard](https://app.statum.co.ke/user).
3. **Read the full API documentation**: [docs.statum.co.ke](https://docs.statum.co.ke)
4. **Install the SDK**: Follow the [Installation](#installation) guidelines below.

---

## Installation

Install the package via Composer:

```bash
composer require statum/statum-php-sdk
```

---

## Quick Start in 2 Minutes

Ensure your credentials are saved in your `.env` or system environment:

```env
STATUM_CONSUMER_KEY=your-consumer-key
STATUM_CONSUMER_SECRET=your-consumer-secret
```

### 1. Plain PHP Setup

```php
use Statum\Sdk\StatumClient;

$client = StatumClient::create(
    consumerKey: $_ENV['STATUM_CONSUMER_KEY'],
    consumerSecret: $_ENV['STATUM_CONSUMER_SECRET']
);
```

### 2. Laravel Setup

The SDK supports package auto-discovery in Laravel. To configure it, publish the configuration file:

```bash
php artisan vendor:publish --tag=statum-config
```

This will create a `config/statum.php` file. You can configure your credentials via your `.env` file:

```env
STATUM_CONSUMER_KEY=your-consumer-key
STATUM_CONSUMER_SECRET=your-consumer-secret
STATUM_BASE_URL=https://api.statum.co.ke/api/v2
STATUM_TIMEOUT=30.0
```

Now type-hint `StatumClient` in any controller or job to inject the initialized client:

```php
use Statum\Sdk\StatumClient;

class SmsController extends Controller
{
    public function __construct(private readonly StatumClient $client) {}

    public function send() {
        // Ready to make type-safe calls!
    }
}
```

---

## Core Integration Examples

### Account Details

Fetch organization profile, available balances, and service configuration:

```php
$response = $client->account()->getAccountDetails();

echo "Status Code: " . $response->statusCode . "\n";
echo "Organization: " . $response->organization->name . "\n";
echo "Available Balance: KES " . $response->organization->details->availableBalance . "\n";

// List registered services and account codes
foreach ($response->organization->accounts as $account) {
    echo "Service: " . $account->serviceName . " | Code: " . $account->account . "\n";
}
```

### Sending SMS

Send transactional or promotional SMS alerts to a recipient phone number:

```php
$response = $client->sms()->sendSms(
    phoneNumber: '254721553678', // Recipient phone number in international format
    senderId: 'STATUM',     // Your approved Sender ID
    message: 'Hello! This is a secure notification from Statum SDK.'
);

echo "Status Code: " . $response->statusCode . "\n";
echo "Description: " . $response->description . "\n";
echo "Request ID: " . $response->requestId . "\n";
```

### Disbursing Airtime

Disburse airtime rewards or incentives (supports amounts from KES 5 to KES 10,000):

```php
$response = $client->airtime()->sendAirtime(
    phoneNumber: '254721553678',
    amount: '100' // Amount must be passed as a string representation
);

echo "Status Code: " . $response->statusCode . "\n";
echo "Description: " . $response->description . "\n";
echo "Request ID: " . $response->requestId . "\n";
```

---

## Error & Exception Handling

The SDK maps HTTP responses to concrete exception classes that inherit from `Statum\Sdk\Exceptions\ApiException`.

```php
use Statum\Sdk\Exceptions\AuthenticationException;
use Statum\Sdk\Exceptions\ValidationException;
use Statum\Sdk\Exceptions\NetworkException;
use Statum\Sdk\Exceptions\ApiException;

try {
    $response = $client->sms()->sendSms('2547XXXXXXXX', 'SENDERID', 'Message');
} catch (AuthenticationException $e) {
    // Credentials failed validation (HTTP 401)
    echo "Auth Failure: Check Consumer Key and Secret.";
} catch (ValidationException $e) {
    // API-side validation parameters failed (HTTP 422)
    echo "Request ID: " . $e->getRequestId() . "\n";
    foreach ($e->getValidationErrors() as $field => $errors) {
        echo "Field '$field' errors: " . implode(', ', $errors) . "\n";
    }
} catch (NetworkException $e) {
    // DNS, timeouts, or connection failures
    echo "Connection error: " . $e->getMessage();
} catch (ApiException $e) {
    // General API errors (e.g. 402 Insufficient Funds, 500 Server Error)
    echo "HTTP Status Code: " . $e->getCode() . "\n";
    echo "Error Body: " . $e->getResponseBody() . "\n";
}
```

---

## API JSON Payload Specifications

Here are the wire-level JSON schemas transmitted and returned by the APIs under the hood:

### 1. SMS API
* **Endpoint**: `POST /sms`
* **Headers**: `Authorization: Basic <base64(key:secret)>`

<details>
<summary><b>JSON Request</b></summary>

```json
{
  "phone_number": "254721553678",
  "sender_id": "STATUM",
  "message": "Hello from Statum SDK!"
}
```
</details>

<details>
<summary><b>JSON Response (Success - 200)</b></summary>

```json
{
  "status_code": 200,
  "description": "Operation successful.",
  "request_id": "d173a8b3-0f3a-463f-8a03-29826b9a2d78"
}
```
</details>

---

### 2. Airtime API
* **Endpoint**: `POST /airtime`

<details>
<summary><b>JSON Request</b></summary>

```json
{
  "phone_number": "254721553678",
  "amount": "100"
}
```
</details>

<details>
<summary><b>JSON Response (Success - 200)</b></summary>

```json
{
  "status_code": 200,
  "description": "Operation successful.",
  "request_id": "6e0213d5-6df9-47bf-ba2d-9b9470d96854"
}
```
</details>

---

### 3. Account Details API
* **Endpoint**: `GET /account-details`

<details>
<summary><b>JSON Response (Success - 200)</b></summary>

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
</details>

---

## Integration Guidelines & Gotchas

1. **Sender ID Approval**: SMS requests will throw an HTTP 422 `ValidationException` if the `senderId` is not registered and approved under your Statum account profile.
2. **Phone Number Formatting**: Ensure phone numbers are passed in the international format (with or without `+` prefix), e.g. `254721553678` or `+254721553678`.
3. **Airtime Limits**: Airtime amounts must be passed as **strings** (e.g. `'100'`) and must fall strictly within the KES 5 to KES 10,000 range per transaction.

---

## Running Tests

Ensure PHPUnit is configured and run:

```bash
composer install
composer test
```

---

## License

This project is licensed under the MIT License. See [LICENSE](LICENSE) for details.
