<?php

declare(strict_types=1);

namespace App\Domain;

interface RoleRepositoryInterface extends \App\Domain\RepositoryInterface
{
    /**
     * @param \App\Domain\Role $role
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     */
    public function save(\App\Domain\Role $role): bool;

    /**
     * @param string $roleCode
     * @return \App\Domain\Role
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     */
    public function get(string $roleCode): \App\Domain\Role;

    /**
     * @param \App\Domain\Role $role
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function delete(\App\Domain\Role $role): bool;

    /**
     * @param string $roleCode
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function deleteByCode(string $roleCode): bool;
}
