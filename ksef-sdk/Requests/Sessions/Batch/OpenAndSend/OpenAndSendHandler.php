<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Sessions\Batch\OpenAndSend;

use Rcalicdan\KSEFClient\Actions\EncryptDocument\EncryptDocumentAction;
use Rcalicdan\KSEFClient\Actions\EncryptDocument\EncryptDocumentHandler;
use Rcalicdan\KSEFClient\Actions\SplitDocumentIntoParts\SplitDocumentIntoPartsAction;
use Rcalicdan\KSEFClient\Actions\SplitDocumentIntoParts\SplitDocumentIntoPartsHandler;
use Rcalicdan\KSEFClient\Actions\ZipDocuments\ZipDocumentsAction;
use Rcalicdan\KSEFClient\Actions\ZipDocuments\ZipDocumentsHandler;
use Rcalicdan\KSEFClient\Contracts\ConfigInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\DTOs\Config;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\Faktura;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Faktura as FakturaRR;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;
use Rcalicdan\KSEFClient\Validator\Rules\Xml\SchemaRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Rcalicdan\KSEFClient\ValueObjects\EncryptionKey;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;
use Rcalicdan\KSEFClient\ValueObjects\SchemaPath;
use RuntimeException;

final class OpenAndSendHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly EncryptDocumentHandler $encryptDocument,
        private readonly ZipDocumentsHandler $zipDocuments,
        private readonly SplitDocumentIntoPartsHandler $splitDocumentIntoParts,
        private readonly Config $config
    ) {
    }

    public function handle(OpenAndSendRequest | OpenAndSendXmlRequest | OpenAndSendZipRequest $request): OpenAndSendResponse
    {
        if ($this->config->encryptionKey instanceof EncryptionKey === false) {
            throw new RuntimeException('Encryption key is required to send invoice.');
        }

        if ($this->config->encryptedKey instanceof EncryptedKey === false) {
            throw new RuntimeException('Encrypted key is required to open session.');
        }

        $documents = match (true) {
            $request instanceof OpenAndSendRequest => array_map(
                fn (Faktura | FakturaRR $faktura): string => $faktura->toXml(),
                $request->faktury
            ),
            default => $request->faktury,
        };

        if (is_array($documents) && $this->config->validateXml) {
            foreach ($documents as $document) {
                Validator::validate($document, [
                    new SchemaRule(SchemaPath::from($request->formCode->getSchemaPath()))
                ]);
            }
        }

        $zipDocument = is_array($documents)
            ? $this->zipDocuments->handle(new ZipDocumentsAction($documents))
            : $documents;

        $fileSize = strlen($zipDocument);

        if ($fileSize > ConfigInterface::BATCH_MAX_FILE_SIZE) {
            throw new RuntimeException('File size is too big.');
        }

        $parts = $this->splitDocumentIntoParts->handle(new SplitDocumentIntoPartsAction(
            document: $zipDocument,
            partSize: ConfigInterface::BATCH_MAX_PART_SIZE
        ));

        $encryptedParts = [];

        foreach ($parts as $part) {
            $encryptedParts[] = $this->encryptDocument->handle(new EncryptDocumentAction(
                encryptionKey: $this->config->encryptionKey,
                document: $part
            ));
        }

        $openResponse = $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from('sessions/batch'),
            body: [
                ...$request->toBody(),
                'batchFile' => [
                    'fileSize' => $fileSize,
                    'fileHash' => base64_encode(hash('sha256', $zipDocument, true)),
                    'fileParts' => array_map(fn (int $index, string $encryptedPart): array => [
                        'ordinalNumber' => $index + 1,
                        'fileSize' => strlen($encryptedPart),
                        'fileHash' => base64_encode(hash('sha256', $encryptedPart, true)),
                    ], array_keys($encryptedParts), $encryptedParts),
                ],
                'encryption' => [
                    'encryptedSymmetricKey' => $this->config->encryptedKey->key,
                    'initializationVector' => $this->config->encryptedKey->iv
                ]
            ]
        ));

        /** @var object{referenceNumber: string, partUploadRequests: array<int, object{ordinalNumber: int, method: string, url: string, headers: array<string, string>}>} */
        $openResponseToObject = $openResponse->object();

        $partUploadResponses = $this->client
            ->withoutAccessToken()
            ->sendAsyncRequest(array_map(fn (object $partUploadRequest): Request => new Request(
                method: Method::from($partUploadRequest->method),
                uri: Uri::from($partUploadRequest->url),
                headers: (array) $partUploadRequest->headers,
                body: $encryptedParts[$partUploadRequest->ordinalNumber - 1],
            ), $openResponseToObject->partUploadRequests));

        return new OpenAndSendResponse($openResponse, $partUploadResponses);
    }
}
