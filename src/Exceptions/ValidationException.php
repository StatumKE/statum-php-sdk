<?php

declare(strict_types=1);

namespace Statum\Sdk\Exceptions;

/**
 * Exception thrown when the API returns a 422 Validation Failed response.
 */
class ValidationException extends ApiException
{
    /**
     * @param array<string, array<string>> $validationErrors
     */
    public function __construct(
        string $message,
        int $statusCode,
        private readonly array $validationErrors,
        private readonly string $requestId,
        ?string $responseBody = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $responseBody, $previous);
    }

    /**
     * Get the validation errors from the API response.
     *
     * @return array<string, array<string>>
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    /**
     * Get the request ID from the API response.
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }

    /**
     * Get a flattened list of all error messages.
     *
     * @return array<string>
     */
    public function getAllMessages(): array
    {
        $messages = [];
        foreach ($this->validationErrors as $field => $errors) {
            foreach ($errors as $error) {
                $messages[] = "{$field}: {$error}";
            }
        }
        return $messages;
    }
}
