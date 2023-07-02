<?php

declare(strict_types=1);

namespace App\Domain;

interface UserRepositoryInterface
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
     * @return \App\Application\SearchResultInterface
     * @throws \App\Domain\DomainException\DomainException
     * @throws \Exception
     */
    public function getList(
        \App\Application\SearchCriteriaInterface $searchCriteria
    ): \App\Application\SearchResultInterface;

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
