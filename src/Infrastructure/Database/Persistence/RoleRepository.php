<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Persistence;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RoleRepository extends \App\Infrastructure\Database\Persistence\AbstractRepository implements
    \App\Domain\RoleRepositoryInterface
{
    protected const SELECTION_KEY = 'roles';

    /**
     * @inheritDoc
     */
    public function save(\App\Domain\Role $role): bool
    {
        try {
            return $role->save();
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
    public function get(string $roleCode): \App\Domain\Role
    {
        try {
            /** @var \App\Domain\Role $role */
            $role = \App\Domain\Role::query()->findOrFail($roleCode);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            throw new \App\Domain\DomainException\DomainRecordNotFoundException(
                'repository.error.not_found',
                (int) $exception->getCode(),
                $exception
            );
        }

        return $role;
    }

    /**
     * @inheritDoc
     */
    public function delete(\App\Domain\Role $role): bool
    {
        try {
            $role->delete();
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
    public function deleteByCode(string $roleCode): bool
    {
        $this->delete($this->get($roleCode));

        return true;
    }
}
