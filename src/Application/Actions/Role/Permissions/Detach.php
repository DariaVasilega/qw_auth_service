<?php

declare(strict_types=1);

namespace App\Application\Actions\Role\Permissions;

class Detach extends \App\Application\Actions\Role\Permissions\Action
{
    protected const RESPONSE = 'action.relations.detach.success';

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     */
    protected function processRelations(array $permissionsCodes): void
    {
        $this->initRole()->permissions()->detach(reset($permissionsCodes) === '*' ? null : $permissionsCodes);
    }
}
