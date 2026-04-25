<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Sessions\Online\Send;

use Rcalicdan\KSEFClient\Actions\EncryptDocument\EncryptDocumentAction;
use Rcalicdan\KSEFClient\Actions\EncryptDocument\EncryptDocumentHandler;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\Config;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;
use Rcalicdan\KSEFClient\Validator\Rules\Xml\SchemaRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Rcalicdan\KSEFClient\ValueObjects\EncryptionKey;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;
use Rcalicdan\KSEFClient\ValueObjects\SchemaPath;
use RuntimeException;

final class SendHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly EncryptDocumentHandler $encryptDocument,
        private readonly Config $config
    ) {
    }

    public function handle(SendRequest | SendXmlRequest $request): ResponseInterface
    {
        if ($this->config->encryptionKey instanceof EncryptionKey === false) {
            throw new RuntimeException('Encryption key is required to send invoice.');
        }

        $xml = $request->toXml();

        if ($this->config->validateXml) {
            Validator::validate($xml, [
                new SchemaRule(SchemaPath::from($request->formCode->getSchemaPath()))
            ]);
        }

        $encryptedXml = $this->encryptDocument->handle(new EncryptDocumentAction(
            encryptionKey: $this->config->encryptionKey,
            document: $xml
        ));

        return $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from(
                sprintf('sessions/online/%s/invoices', $request->referenceNumber->value)
            ),
            body: [
                ...$request->toBody(),
                'invoiceHash' => base64_encode(hash('sha256', $xml, true)),
                'invoiceSize' => strlen($xml),
                'encryptedInvoiceHash' => base64_encode(hash('sha256', $encryptedXml, true)),
                'encryptedInvoiceSize' => strlen($encryptedXml),
                'encryptedInvoiceContent' => base64_encode($encryptedXml),
            ]
        ));
    }
}
