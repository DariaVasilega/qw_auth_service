<?php

declare(strict_types=1);

namespace App\Application;

interface SearchResultInterface
{
    /**
     * @return array
     */
    public function getItems(): array;

    /**
     * @return int
     */
    public function getTotal(): int;

    /**
     * @return \App\Application\SearchResultPageInterface
     */
    public function getPage(): \App\Application\SearchResultPageInterface;

    /**
     * @return \App\Application\SearchCriteriaInterface|null
     */
    public function getSearchCriteria(): ?\App\Application\SearchCriteriaInterface;

    /**
     * @param array $items
     * @return $this
     */
    public function setItems(array $items): self;

    /**
     * @param int $total
     * @return $this
     */
    public function setTotal(int $total): self;

    /**
     * @param \App\Application\SearchResultPageInterface $page
     * @return $this
     */
    public function setPage(\App\Application\SearchResultPageInterface $page): self;

    /**
     * @param \App\Application\SearchCriteriaInterface|null $searchCriteria
     * @return $this
     */
    public function setSearchCriteria(?\App\Application\SearchCriteriaInterface $searchCriteria): self;
}
