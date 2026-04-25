<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Validator\Rules\Xml;

use DOMDocument;
use Rcalicdan\KSEFClient\Exceptions\XmlValidationException;
use Rcalicdan\KSEFClient\Validator\Rules\AbstractRule;
use Rcalicdan\KSEFClient\ValueObjects\SchemaPath;

final class SchemaRule extends AbstractRule
{
    public function __construct(private readonly SchemaPath $schemaPath)
    {
    }

    public function handle(string $value, ?string $attribute = null): void
    {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->loadXML($value);

        $isValid = $dom->schemaValidate($this->schemaPath->value);

        if ( ! $isValid) {
            $errors = libxml_get_errors();

            libxml_clear_errors();

            $message = $this->getMessage('The value is not valid with xsd: %s.', $attribute);
            $values = array_filter([$this->schemaPath->value, $attribute]);

            throw new XmlValidationException(
                message: sprintf($message, ...$values),
                context: [
                    'message' => $message,
                    'values' => $values,
                    'document' => $value,
                    'errors' => $errors
                ]
            );
        }

        libxml_clear_errors();
    }
}
