<?php

declare(strict_types=1);

namespace App\Application\Actions\User\Roles;

class Sync extends \App\Application\Actions\User\Roles\Action
{
    protected const RESPONSE = 'action.relations.sync.success';

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     */
    protected function processRelations(array $rolesCodes): void
    {
        $this->initUser()->roles()->sync($rolesCodes);
    }
}
