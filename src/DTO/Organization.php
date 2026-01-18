<?php

declare(strict_types=1);

namespace Statum\Sdk\DTO;

readonly class Organization
{
    /**
     * @param ServiceAccount[] $accounts
     */
    public function __construct(
        public string $name,
        public OrganizationDetails $details,
        public array $accounts
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
