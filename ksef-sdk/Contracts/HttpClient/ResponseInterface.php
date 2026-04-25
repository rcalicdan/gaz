<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\HttpClient;

use Rcalicdan\KSEFClient\Contracts\ArrayableInterface;
use Psr\Http\Message\ResponseInterface as BaseResponseInterface;

/**
 * @property-read BaseResponseInterface $baseResponse
 * @api
 */
interface ResponseInterface extends ArrayableInterface
{
    /**
     * Throw an exception if the response status code is >= 400.
     *
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function throwExceptionIfError(): void;

    /**
     * Returns the HTTP status code of the response.
     */
    public function status(): int;

    /**
     * Returns the value of the specified header.
     */
    public function header(string $name): ?string;

    /**
     * Returns all headers.
     *
     * @return array<string, array<int, string>>
     */
    public function headers(): array;

    /**
     * Decodes the JSON response body into an array.
     *
     * @return array<string, mixed>
     */
    public function json(): array;

    /**
     * Decodes the JSON response body into an object.
     * 
     * @return object|array<string, mixed>
     * 
     * @example
     * /** @var object{referenceNumber: string} $data *\/
     * $data = $client->sessions()->online()->open($req)->object();
     */
    public function object(): object | array;

    /**
     * Returns the raw, unparsed response body.
     */
    public function body(): string;
}