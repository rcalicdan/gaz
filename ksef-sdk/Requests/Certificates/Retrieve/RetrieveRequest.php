<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Certificates\Retrieve;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Concerns\HasToBody;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MaxRule;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MinRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Rcalicdan\KSEFClient\ValueObjects\CertificateSerialNumber;

final class RetrieveRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    /**
     * @var array<int, CertificateSerialNumber> $certificateSerialNumbers
     */
    public readonly array $certificateSerialNumbers;

    /**
     * @param array<int, CertificateSerialNumber> $certificateSerialNumbers
     */
    public function __construct(array $certificateSerialNumbers)
    {
        Validator::validate([
            'certificateSerialNumbers' => $certificateSerialNumbers
        ], [
            'certificateSerialNumbers' => [
                new MinRule(1),
                new MaxRule(10)
            ]
        ]);

        $this->certificateSerialNumbers = $certificateSerialNumbers;
    }
}
