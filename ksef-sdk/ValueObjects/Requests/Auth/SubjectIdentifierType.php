<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Auth;

enum SubjectIdentifierType: string
{
    case CertificateSubject = 'certificateSubject';

    case CertificateFingerprint = 'certificateFingerprint';
}
