<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Exceptions;

use LibXMLError;

/**
 * Thrown when the generated XML Invoice or payload does not match the official KSeF XSD schemas.
 * 
 * @property-read array{
 *     message: string,
 *     values: array<int, mixed>,
 *     document: string,
 *     errors: array<int, LibXMLError>
 * } $context Detailed context of the validation failure, including the raw XML document and the specific LibXML errors.
 * 
 * @example
 * catch (XmlValidationException $e) {
 *     foreach ($e->context['errors'] as $error) {
 *         echo "Line {$error->line}: {$error->message}\n";
 *     }
 * }
 */
final class XmlValidationException extends RuleValidationException
{
}