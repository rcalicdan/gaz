<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Sessions\Batch\OpenAndSend;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Batch\OpenAndSend\Concerns\HasToBody;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MaxRule;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MinRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FormCode;

final class OpenAndSendXmlRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    /**
     * @var array<int, string> $faktury
     */
    public readonly array $faktury;

    /**
     * @param array<int, string> $faktury
     */
    public function __construct(
        public readonly FormCode $formCode,
        array $faktury,
        public readonly Optional | bool $offlineMode = new Optional(),
    ) {
        Validator::validate([
            'faktury' => $faktury,
        ], [
            'faktury' => [
                new MinRule(1),
                new MaxRule(10000)
            ]
        ]);

        $this->faktury = $faktury;
    }
}
