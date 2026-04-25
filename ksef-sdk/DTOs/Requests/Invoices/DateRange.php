<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Invoices;

use DateTimeImmutable;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\Validator\Rules\Date\MaxRangeRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices\DateRangeFrom;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices\DateRangeTo;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices\DateType;

final class DateRange extends AbstractDTO
{
    public readonly DateRangeFrom $from;

    public readonly Optional | DateRangeTo $to;

    public function __construct(
        public readonly DateType $dateType,
        DateRangeFrom $from,
        Optional | DateRangeTo $to = new Optional(),
        public readonly Optional | bool $restrictToPermanentStorageHwmDate = new Optional(),
    ) {
        Validator::validate([
            'from' => $from->value
        ], [
            'from' => [
                new MaxRangeRule(
                    $to instanceof DateRangeTo ? $to->value : new DateTimeImmutable(
                        'now',
                        $from->value->getTimezone()
                    ),
                    3
                )
            ],
        ]);

        $this->from = $from;
        $this->to = $to;
    }
}
