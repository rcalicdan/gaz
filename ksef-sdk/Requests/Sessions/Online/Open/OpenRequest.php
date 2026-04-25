<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Sessions\Online\Open;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FormCode;

final class OpenRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public readonly FormCode $formCode,
    ) {
    }

    public function toBody(): array
    {
        return [
            'formCode' => [
                'systemCode' => $this->formCode->value,
                'schemaVersion' => $this->formCode->getSchemaVersion(),
                'value' => $this->formCode->getValue(),
            ]
        ];
    }
}
