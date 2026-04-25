<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Testdata;

use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Attachment\AttachmentResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Context\ContextResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Limits\LimitsResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Permissions\PermissionsResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Person\PersonResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\RateLimits\RateLimitsResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Subject\SubjectResourceInterface;

interface TestdataResourceInterface
{
    public function subject(): SubjectResourceInterface;

    public function person(): PersonResourceInterface;

    public function limits(): LimitsResourceInterface;

    public function rateLimits(): RateLimitsResourceInterface;

    public function attachment(): AttachmentResourceInterface;

    public function context(): ContextResourceInterface;

    public function permissions(): PermissionsResourceInterface;
}
