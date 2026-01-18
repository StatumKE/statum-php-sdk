<?php

declare(strict_types=1);

namespace Statum\Sdk\DTO;

class ServiceAccount
{
    public function __construct(
        public readonly string $account,
        public readonly string $serviceName
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            account: $data['account'] ?? '',
            serviceName: $data['service_name'] ?? ''
        );
    }
}
