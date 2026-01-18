<?php

declare(strict_types=1);

namespace Statum\Sdk\DTO;

class Organization
{
    public function __construct(
        public readonly string $name,
        public readonly OrganizationDetails $details,
        /** @var ServiceAccount[] */
        public readonly array $accounts
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            details: OrganizationDetails::fromArray($data['details'] ?? []),
            accounts: array_map(
                fn(array $account) => ServiceAccount::fromArray($account),
                $data['accounts'] ?? []
            )
        );
    }
}
