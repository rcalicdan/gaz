<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts;

use DOMDocument;

interface DomSerializableInterface
{
    public function toDom(): DOMDocument;
}
