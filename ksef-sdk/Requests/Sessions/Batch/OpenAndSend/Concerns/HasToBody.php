<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Sessions\Batch\OpenAndSend\Concerns;

use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FormCode;

/**
 * @property-read FormCode $formCode
 */
trait HasToBody
{
    public function toBody(): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->toArray(only: ['offlineMode']);

        return [
            ...$data,
            'formCode' => [
                'systemCode' => $this->formCode->value,
                'schemaVersion' => $this->formCode->getSchemaVersion(),
                'value' => $this->formCode->getValue(),
            ]
        ];
    }
}
