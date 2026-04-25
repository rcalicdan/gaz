<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Testdata\Subject;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Subject\SubjectResourceInterface;
use Rcalicdan\KSEFClient\Requests\Testdata\Subject\Create\CreateHandler;
use Rcalicdan\KSEFClient\Requests\Testdata\Subject\Create\CreateRequest;
use Rcalicdan\KSEFClient\Requests\Testdata\Subject\Remove\RemoveHandler;
use Rcalicdan\KSEFClient\Requests\Testdata\Subject\Remove\RemoveRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class SubjectResource extends AbstractResource implements SubjectResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function create(CreateRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof CreateRequest === false) {
                $request = CreateRequest::from($request, $this->valinorCache);
            }

            return (new CreateHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function remove(RemoveRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof RemoveRequest === false) {
                $request = RemoveRequest::from($request, $this->valinorCache);
            }

            return (new RemoveHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
