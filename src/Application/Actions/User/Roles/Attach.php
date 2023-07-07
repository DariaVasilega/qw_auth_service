<?php

declare(strict_types=1);

namespace App\Application\Actions\User\Roles;

class Attach extends \App\Application\Actions\User\Roles\Action
{
    protected const RESPONSE = 'action.relations.attach.success';

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     */
    protected function processRelations(array $rolesCodes): void
    {
        $this->initUser()->roles()->syncWithoutDetaching($rolesCodes);
    }
}
