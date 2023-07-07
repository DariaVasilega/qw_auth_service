<?php

declare(strict_types=1);

namespace App\Application\Actions\Role\Permissions;

class Sync extends \App\Application\Actions\Role\Permissions\Action
{
    protected const RESPONSE = 'action.relations.sync.success';

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     */
    protected function processRelations(array $permissionsCodes): void
    {
        $this->initRole()->permissions()->sync($permissionsCodes);
    }
}
