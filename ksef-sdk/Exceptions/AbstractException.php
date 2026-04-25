<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Exceptions;

use Exception;
use Rcalicdan\KSEFClient\Contracts\ArrayableInterface;
use Rcalicdan\KSEFClient\Contracts\ContextInterface;
use Rcalicdan\KSEFClient\Support\Arr;
use Rcalicdan\KSEFClient\ValueObjects\Support\KeyType;
use Throwable;

abstract class AbstractException extends Exception implements ArrayableInterface, ContextInterface
{
    /**
     * @param object|array<string, mixed>|null $context
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
        public readonly object|array|null $context = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function toArray(KeyType $keyType = KeyType::Camel, array $only = []): array
    {
        /** @var array<string, mixed> */
        return Arr::normalize([
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'context' => $this->context,
        ], $keyType, $only);
    }
}
