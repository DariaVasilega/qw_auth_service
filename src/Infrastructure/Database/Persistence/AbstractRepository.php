<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Persistence;

abstract class AbstractRepository implements \App\Domain\RepositoryInterface
{
    protected const SELECTION_KEY = 'items';

    /**
     * @var \App\Infrastructure\Database\Query\PaginatorConverter $paginatorConverter
     */
    protected \App\Infrastructure\Database\Query\PaginatorConverter $paginatorConverter;

    /**
     * @param \App\Infrastructure\Database\Query\PaginatorConverter $paginatorConverter
     */
    public function __construct(
        \App\Infrastructure\Database\Query\PaginatorConverter $paginatorConverter
    ) {
        $this->paginatorConverter = $paginatorConverter;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \App\Application\SearchCriteriaInterface $searchCriteria
    ): \App\Application\SearchResultInterface {
        try {
            /** @phpstan-ignore-next-line */
            $paginator = $searchCriteria->build()->paginate();
        } catch (\Unlu\Laravel\Api\Exceptions\UnknownColumnException | \InvalidArgumentException $exception) {
            throw new \App\Domain\DomainException\DomainException(
                $exception->getMessage(),
                (int) $exception->getCode(),
                $exception
            );
        }

        return $this->paginatorConverter->convertToSearchResult($paginator, static::SELECTION_KEY);
    }
}
