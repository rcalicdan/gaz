<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Factories;

use GuzzleHttp\ClientInterface as GuzzleHttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ClientInterface;
use Rcalicdan\KSEFClient\HttpClient\Adapters\DefaultHttpAdapter;
use Rcalicdan\KSEFClient\HttpClient\Adapters\GuzzleHttpAdapter;
use Psr\Http\Client\ClientInterface as BaseClientInterface;

final class ClientFactory extends AbstractFactory
{
    public static function make(BaseClientInterface $baseClient): ClientInterface
    {
        $clientAdapter = match (true) {
            $baseClient instanceof GuzzleHttpClientInterface => GuzzleHttpAdapter::class,
            default => DefaultHttpAdapter::class
        };

        //@phpstan-ignore-next-line
        return new $clientAdapter($baseClient);
    }
}
