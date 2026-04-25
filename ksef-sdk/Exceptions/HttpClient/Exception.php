<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Exceptions\HttpClient;

use Rcalicdan\KSEFClient\Exceptions\AbstractException;

/**
 * Base exception for HTTP errors returned by the KSeF API (4xx, 5xx).
 * 
 * @property-read object{
 *     exception?: object{
 *         exceptionDetailList: array<int, object{exceptionCode: int, exceptionDescription: string}>,
 *         referenceNumber?: string
 *     },
 *     status?: object{
 *         code: int,
 *         description: string,
 *         details?: array<int, string>
 *     }
 * }|null $context Contains the parsed JSON error body from KSeF.
 */
class Exception extends AbstractException
{
}