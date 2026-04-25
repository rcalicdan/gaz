<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts;

interface XmlSerializableInterface
{
    public function toXml(): string;
}
