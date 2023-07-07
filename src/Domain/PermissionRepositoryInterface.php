<?php

declare(strict_types=1);

namespace App\Domain;

interface PermissionRepositoryInterface extends \App\Domain\RepositoryInterface
{
    /**
     * @param \App\Domain\Permission $permission
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     */
    public function save(\App\Domain\Permission $permission): bool;

    /**
     * @param string $permissionCode
     * @return \App\Domain\Permission
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     */
    public function get(string $permissionCode): \App\Domain\Permission;

    /**
     * @param \App\Domain\Permission $permission
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function delete(\App\Domain\Permission $permission): bool;

    /**
     * @param string $permissionCode
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function deleteByCode(string $permissionCode): bool;
}
