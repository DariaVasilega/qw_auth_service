<?php

declare(strict_types=1);

namespace App\Application\Actions\Role\Permissions;

class Attach extends \App\Application\Actions\Role\Permissions\Action
{
    protected const RESPONSE = 'action.relations.attach.success';

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     */
    protected function processRelations(array $permissionsCodes): void
    {
        $this->initRole()->permissions()->syncWithoutDetaching($permissionsCodes);
    }
}
