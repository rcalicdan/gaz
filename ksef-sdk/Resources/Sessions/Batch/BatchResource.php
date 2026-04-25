<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Sessions\Batch;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Actions\EncryptDocument\EncryptDocumentHandler;
use Rcalicdan\KSEFClient\Actions\SplitDocumentIntoParts\SplitDocumentIntoPartsHandler;
use Rcalicdan\KSEFClient\Actions\ZipDocuments\ZipDocumentsHandler;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Sessions\Batch\BatchResourceInterface;
use Rcalicdan\KSEFClient\DTOs\Config;
use Rcalicdan\KSEFClient\Requests\Sessions\Batch\Close\CloseHandler;
use Rcalicdan\KSEFClient\Requests\Sessions\Batch\Close\CloseRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendHandler;
use Rcalicdan\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendResponse;
use Rcalicdan\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendXmlRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendZipRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Psr\Log\LoggerInterface;
use Throwable;

final class BatchResource extends AbstractResource implements BatchResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?LoggerInterface $logger = null,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function openAndSend(OpenAndSendRequest | OpenAndSendXmlRequest | OpenAndSendZipRequest | array $request): OpenAndSendResponse
    {
        try {
            if (is_array($request)) {
                $request = OpenAndSendRequest::from($request, $this->valinorCache);
            }

            return (new OpenAndSendHandler(
                client: $this->client,
                encryptDocument: new EncryptDocumentHandler($this->logger),
                zipDocuments: new ZipDocumentsHandler(),
                splitDocumentIntoParts: new SplitDocumentIntoPartsHandler(),
                config: $this->config
            ))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function close(CloseRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof CloseRequest === false) {
                $request = CloseRequest::from($request, $this->valinorCache);
            }

            return (new CloseHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
