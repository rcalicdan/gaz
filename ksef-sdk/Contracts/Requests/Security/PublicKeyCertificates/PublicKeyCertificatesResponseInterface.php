<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Requests\Security\PublicKeyCertificates;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Security\PublicKeyCertificates\PublicKeyCertificateUsage;

interface PublicKeyCertificatesResponseInterface extends ResponseInterface
{
    public function getFirstByPublicKeyCertificateUsage(PublicKeyCertificateUsage $type): ?string;
}
