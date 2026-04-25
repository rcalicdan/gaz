<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Support;

use Rcalicdan\KSEFClient\Contracts\ArrayableInterface;
use Rcalicdan\KSEFClient\Contracts\FromArrayInterface;
use Rcalicdan\KSEFClient\Contracts\WithInterface;
use Rcalicdan\KSEFClient\Support\Concerns\HasFromArray;
use Rcalicdan\KSEFClient\Support\Concerns\HasToArray;
use Rcalicdan\KSEFClient\Support\Concerns\HasWith;

abstract class AbstractDTO implements FromArrayInterface, ArrayableInterface, WithInterface
{
    use HasFromArray;
    use HasToArray;
    use HasWith;
}
