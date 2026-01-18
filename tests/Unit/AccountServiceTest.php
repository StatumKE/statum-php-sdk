<?php

declare(strict_types=1);

namespace Statum\Sdk\Tests\Unit;

use Statum\Sdk\DTO\AccountDetailsResponse;
use Statum\Sdk\Http\HttpClient;
use Statum\Sdk\Services\AccountService;
use Statum\Sdk\Tests\TestCase;

class AccountServiceTest extends TestCase
{
    public function test_get_account_details_successfully(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'account-details')
            ->willReturn([
                'status_code' => 200,
                'description' => 'Operation successful.',
                'request_id' => 'uuid',
                'organization' => [
                    'name' => 'Statum Test',
                    'details' => [
                        'available_balance' => 695.15,
                        'location' => 'Nairobi - Westlands',
                        'website' => 'www.statum.co.ke',
                        'office_email' => 'admin@statum.co.ke',
                        'office_mobile' => '+254722199199',
                        'mpesa_account_top_up_code' => 'B9E573'
                    ],
                    'accounts' => [
                        [
                            'account' => 'Statum',
                            'service_name' => 'sms'
                        ]
                    ]
                ]
            ]);

        $service = new AccountService($httpClient);
        $response = $service->getAccountDetails();

        $this->assertInstanceOf(AccountDetailsResponse::class, $response);
        $this->assertEquals(200, $response->statusCode);
        $this->assertEquals('Statum Test', $response->organization->name);
        $this->assertEquals(695.15, $response->organization->details->availableBalance);
        $this->assertCount(1, $response->organization->accounts);
        $this->assertEquals('sms', $response->organization->accounts[0]->serviceName);
    }
}
