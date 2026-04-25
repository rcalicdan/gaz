<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects;

use SensitiveParameter;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Rcalicdan\KSEFClient\Validator\Rules\File\ExistsRule;
use Rcalicdan\KSEFClient\Validator\Rules\File\ExtensionsRule;
use Rcalicdan\KSEFClient\Validator\Validator;

final class CertificatePath extends AbstractValueObject
{
    public readonly string $path;

    public function __construct(
        string $path,
        #[SensitiveParameter] public readonly ?string $passphrase = null
    ) {
        Validator::validate($path, [
            new ExistsRule(),
            new ExtensionsRule(['p12']),
        ]);

        $this->path = $path;
    }

    public static function from(string $path, #[SensitiveParameter] ?string $passphrase = null): self
    {
        return new self($path, $passphrase);
    }
}
