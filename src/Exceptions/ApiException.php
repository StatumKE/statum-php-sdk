<?php

declare(strict_types=1);

namespace Statum\Sdk\Exceptions;

use Exception;

class ApiException extends Exception
{
    protected ?string $responseBody;

    public function __construct(
        string $message,
        int $statusCode = 0,
        ?string $responseBody = null,
        ?Exception $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
        $this->responseBody = $responseBody;
    }

    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }
}
