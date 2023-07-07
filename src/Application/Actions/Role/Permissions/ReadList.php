<?php

declare(strict_types=1);

namespace App\Application\Actions\Role\Permissions;

use App\Application\Actions\Role\Action as RoleAction;
use Psr\Http\Message\ResponseInterface as Response;

class ReadList extends RoleAction
{
    /**
     * @inheritDoc
     * @throws \JsonException
     */
    protected function action(): Response
    {
        try {
            $role = $this->roleRepository->get($this->resolveArg('code'));
        } catch (\App\Domain\DomainException\DomainRecordNotFoundException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $this->respondWithData([
            'permissions' => $role->permissions()->get(),
        ]);
    }
}
