<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Testdata\Context;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Context\ContextResourceInterface;
use Rcalicdan\KSEFClient\Requests\Testdata\Context\Block\BlockHandler;
use Rcalicdan\KSEFClient\Requests\Testdata\Context\Block\BlockRequest;
use Rcalicdan\KSEFClient\Requests\Testdata\Context\Unblock\UnblockHandler;
use Rcalicdan\KSEFClient\Requests\Testdata\Context\Unblock\UnblockRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class ContextResource extends AbstractResource implements ContextResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function block(BlockRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof BlockRequest === false) {
                $request = BlockRequest::from($request, $this->valinorCache);
            }

            return (new BlockHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function unblock(UnblockRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof UnblockRequest === false) {
                $request = UnblockRequest::from($request, $this->valinorCache);
            }

            return (new UnblockHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
