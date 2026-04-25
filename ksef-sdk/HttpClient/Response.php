<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\HttpClient;

use JsonException;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Factories\ExceptionFactory;
use Rcalicdan\KSEFClient\Support\Arr;
use Rcalicdan\KSEFClient\Support\Str;
use Rcalicdan\KSEFClient\ValueObjects\Support\KeyType;
use Psr\Http\Message\ResponseInterface as BaseResponseInterface;

final class Response implements ResponseInterface
{
    private readonly string $contents;

    private readonly int $statusCode;

    public function __construct(
        public readonly BaseResponseInterface $baseResponse,
    ) {
        $this->contents = $baseResponse->getBody()->getContents();
        $this->statusCode = $baseResponse->getStatusCode();
    }

    public function throwExceptionIfError(): void
    {
        if ($this->statusCode < 400) {
            return;
        }

        try {
            $exceptionResponse = $this->contents === '' ? null : $this->object();
        } catch (JsonException) {
            $exceptionResponse = null;
        }

        /** @var object{exception: object{exceptionDetailList: array<int, object{exceptionCode: int, exceptionDescription: string}>}}|null $exceptionResponse */
        $exception = ExceptionFactory::make($this->statusCode, $exceptionResponse);

        throw $exception;
    }

    public function status(): int
    {
        return $this->statusCode;
    }

    public function header(string $name): ?string
    {
        if ( ! $this->baseResponse->hasHeader($name)) {
            return null;
        }

        return $this->baseResponse->getHeaderLine($name);
    }

    public function headers(): array
    {
        /** @var array<string, array<int, string>> */
        return $this->baseResponse->getHeaders();
    }

    public function body(): string
    {
        return $this->contents;
    }

    public function object(): object | array
    {
        /** @var object|array<string, mixed> */
        return json_decode($this->contents, flags: JSON_THROW_ON_ERROR);
    }

    public function json(): array
    {
        /** @var array<string, mixed> */
        return json_decode($this->contents, true, flags: JSON_THROW_ON_ERROR);
    }

    public function toArray(KeyType $keyType = KeyType::Camel, array $only = []): array
    {
        /** @var array<string, mixed> */
        return Arr::normalize([
            'statusCode' => $this->statusCode,
            'contents' => Str::isBinary($this->contents) ? '[binary data]' : $this->contents,
        ], keyType: $keyType, only: $only);
    }
}
