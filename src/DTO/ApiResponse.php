<?php

declare(strict_types=1);

namespace Statum\Sdk\DTO;

class ApiResponse
{
    public function __construct(
        public readonly int $statusCode,
        public readonly string $description,
        public readonly string $requestId
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            statusCode: (int) ($data['status_code'] ?? 0),
            description: $data['description'] ?? '',
            requestId: $data['request_id'] ?? ''
        );
    }
}
