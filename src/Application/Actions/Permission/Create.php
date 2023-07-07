<?php

declare(strict_types=1);

namespace App\Application\Actions\Permission;

use Psr\Http\Message\ResponseInterface as Response;

class Create extends \App\Application\Actions\Permission\Store
{
    /**
     * @inheritDoc
     */
    protected function init(array $permissionData): \App\Domain\Permission
    {
        /** @var \App\Domain\Permission $permission */
        $permission = \App\Domain\Permission::query()->make($permissionData);

        return $permission;
    }

    /**
     * @inheritDoc
     */
    protected function sendResponse(\App\Domain\Permission $permission): Response
    {
        return $this->respondWithData([
            'message' => $this->translator->get('action.create.success'),
            'permission' => [
                'code' => $permission->code,
            ],
        ]);
    }
}
