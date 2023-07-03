<?php

declare(strict_types=1);

namespace App\Domain;

interface UserRepositoryInterface extends \App\Domain\RepositoryInterface
{
    /**
     * @param \App\Domain\User $user
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     */
    public function save(\App\Domain\User $user): bool;

    /**
     * @param int $userId
     * @return \App\Domain\User
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     */
    public function get(int $userId): \App\Domain\User;

    /**
     * @param string $email
     * @return \App\Domain\User
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     */
    public function getByEmail(string $email): \App\Domain\User;

    /**
     * @param \App\Domain\User $user
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function delete(\App\Domain\User $user): bool;

    /**
     * @param int $userId
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function deleteById(int $userId): bool;
}
