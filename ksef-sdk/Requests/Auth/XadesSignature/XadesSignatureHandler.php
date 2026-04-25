<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Auth\XadesSignature;

use Rcalicdan\KSEFClient\Actions\SignDocument\SignDocumentAction;
use Rcalicdan\KSEFClient\Actions\SignDocument\SignDocumentHandler;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\Config;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;
use Rcalicdan\KSEFClient\Support\Utility;
use Rcalicdan\KSEFClient\Validator\Rules\Xml\SchemaRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;
use Rcalicdan\KSEFClient\ValueObjects\SchemaPath;

final class XadesSignatureHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly SignDocumentHandler $signDocument,
        private readonly Config $config
    ) {
    }

    public function handle(XadesSignatureRequest | XadesSignatureXmlRequest $request): ResponseInterface
    {
        $signedXml = $request->toXml();

        if ($request instanceof XadesSignatureRequest) {
            if ($this->config->validateXml) {
                Validator::validate($signedXml, [
                    new SchemaRule(SchemaPath::from(Utility::basePath('resources/xsd/authv2.xsd')))
                ]);
            }

            $signedXml = $this->signDocument->handle(
                new SignDocumentAction(
                    certificate: $request->certificate,
                    document: $request->toXml(),
                )
            );
        }

        return $this->client
            ->withoutAccessToken()
            ->sendRequest(new Request(
                method: Method::Post,
                uri: Uri::from('auth/xades-signature'),
                headers: [
                    'Content-Type' => 'application/xml',
                    'Accept' => 'application/json',
                    ...$request->toHeaders()
                ],
                parameters: $request->toParameters(),
                body: $signedXml
            ));
    }
}
