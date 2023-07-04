<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use Psr\Http\Message\ResponseInterface as Response;

class Create extends \App\Application\Actions\Role\Store
{
    /**
     * @inheritDoc
     */
    protected function init(array $roleData): \App\Domain\Role
    {
        /** @var \App\Domain\Role $role */
        $role = \App\Domain\Role::query()->make($roleData);

        return $role;
    }

    /**
     * @inheritDoc
     */
    protected function sendResponse(\App\Domain\Role $role): Response
    {
        return $this->respondWithData([
            'message' => $this->translator->get('action.create.success'),
            'role' => [
                'code' => $role->code,
            ],
        ]);
    }
}
