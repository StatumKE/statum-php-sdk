<?php

declare(strict_types=1);

namespace Statum\Sdk\DTO;

class OrganizationDetails
{
    public function __construct(
        public readonly float $availableBalance,
        public readonly string $location,
        public readonly string $website,
        public readonly string $officeEmail,
        public readonly string $officeMobile,
        public readonly string $mpesaAccountTopUpCode
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            availableBalance: (float) ($data['available_balance'] ?? 0.0),
            location: $data['location'] ?? '',
            website: $data['website'] ?? '',
            officeEmail: $data['office_email'] ?? '',
            officeMobile: $data['office_mobile'] ?? '',
            mpesaAccountTopUpCode: $data['mpesa_account_top_up_code'] ?? ''
        );
    }
}
