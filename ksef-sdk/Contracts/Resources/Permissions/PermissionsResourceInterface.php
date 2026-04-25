<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Permissions;

use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Attachments\AttachmentsResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Authorizations\AuthorizationsResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Common\CommonResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Entities\EntitiesResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\EuEntities\EuEntitiesResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Indirect\IndirectResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Operations\OperationsResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Persons\PersonsResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Query\QueryResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Subunits\SubunitsResourceInterface;

interface PermissionsResourceInterface
{
    public function common(): CommonResourceInterface;

    public function persons(): PersonsResourceInterface;

    public function entities(): EntitiesResourceInterface;

    public function authorizations(): AuthorizationsResourceInterface;

    public function indirect(): IndirectResourceInterface;

    public function subunits(): SubunitsResourceInterface;

    public function euEntities(): EuEntitiesResourceInterface;

    public function operations(): OperationsResourceInterface;

    public function query(): QueryResourceInterface;

    public function attachments(): AttachmentsResourceInterface;
}
