<?php

declare(strict_types=1);

namespace App\Domain;

interface RepositoryInterface
{
    /**
     * @return \App\Application\SearchResultInterface
     * @throws \App\Domain\DomainException\DomainException
     * @throws \Exception
     */
    public function getList(
        \App\Application\SearchCriteriaInterface $searchCriteria
    ): \App\Application\SearchResultInterface;
}
