<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Security;

use Rcalicdan\KSEFClient\Contracts\Requests\Security\PublicKeyCertificates\PublicKeyCertificatesResponseInterface;

interface SecurityResourceInterface
{
    public function publicKeyCertificates(): PublicKeyCertificatesResponseInterface;
}
