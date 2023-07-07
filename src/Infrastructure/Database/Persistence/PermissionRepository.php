<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Persistence;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PermissionRepository extends \App\Infrastructure\Database\Persistence\AbstractRepository implements
    \App\Domain\PermissionRepositoryInterface
{
    protected const SELECTION_KEY = 'permissions';

    /**
     * @inheritDoc
     */
    public function save(\App\Domain\Permission $permission): bool
    {
        try {
            return $permission->save();
        } catch (\Exception $exception) {
            throw new \App\Domain\DomainException\DomainRecordNotSavedException(
                'repository.error.not_saved',
                (int) $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function get(string $permissionCode): \App\Domain\Permission
    {
        try {
            /** @var \App\Domain\Permission $permission */
            $permission = \App\Domain\Permission::query()->findOrFail($permissionCode);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            throw new \App\Domain\DomainException\DomainRecordNotFoundException(
                'repository.error.not_found',
                (int) $exception->getCode(),
                $exception
            );
        }

        return $permission;
    }

    /**
     * @inheritDoc
     */
    public function delete(\App\Domain\Permission $permission): bool
    {
        try {
            $permission->delete();
        } catch (\LogicException $exception) {
            throw new \App\Domain\DomainException\DomainRecordNotRemovedException(
                'repository.error.not_removed',
                (int) $exception->getCode(),
                $exception
            );
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteByCode(string $permissionCode): bool
    {
        $this->delete($this->get($permissionCode));

        return true;
    }
}
