<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Exceptions;

use Rcalicdan\KSEFClient\Contracts\ContextInterface;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class ExceptionHandler implements ExceptionHandlerInterface
{
    public function __construct(
        private readonly ?LoggerInterface $logger = null
    ) {
    }

    public function handle(Throwable $throwable): Throwable
    {
        if ($this->logger instanceof LoggerInterface) {
            $message = $throwable->getCode() > 0
                ? "{$throwable->getCode()} {$throwable->getMessage()}"
                : $throwable->getMessage();

            $context['exception'] = $throwable;

            if ($throwable instanceof ContextInterface) {
                $context['context'] = $throwable->context;
            }

            $this->logger->error($message, $context);
        }

        return $throwable;
    }
}
