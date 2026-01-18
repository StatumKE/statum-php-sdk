<?php

declare(strict_types=1);

namespace Statum\Sdk\DTO;

readonly class AccountDetailsResponse
{
    public function __construct(
        public int $statusCode,
        public string $description,
        public string $requestId,
        public Organization $organization
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            statusCode: (int) ($data['status_code'] ?? 0),
            description: $data['description'] ?? '',
            requestId: $data['request_id'] ?? '',
            organization: Organization::fromArray($data['organization'] ?? [])
        );
    }
}
