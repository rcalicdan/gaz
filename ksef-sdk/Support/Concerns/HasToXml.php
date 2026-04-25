<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Support\Concerns;

use RuntimeException;
use Rcalicdan\KSEFClient\Contracts\XmlSerializableInterface;

/**
 * @mixin XmlSerializableInterface
 */
trait HasToXml
{
    public function toXml(): string
    {
        return $this->toDom()->saveXML() ?: throw new RuntimeException('Unable to serialize to XML');
    }
}
