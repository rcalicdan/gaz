<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Limits\Subject;

use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Limits\Subject\Certificate\CertificateResourceInterface;

interface SubjectResourceInterface
{
    public function certificate(): CertificateResourceInterface;
}
