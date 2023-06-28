<?php

declare(strict_types=1);

namespace App\Infrastructure;

final class SearchResult implements
    \App\Application\SearchResultInterface,
    \Illuminate\Contracts\Support\Arrayable
{
    private const SELECTION_KEY = 'items';

    /**
     * @var array $items
     */
    private array $items;

    /**
     * @var int $total full records qty in storage
     */
    private int $total;

    /**
     * @var \App\Application\SearchResultPageInterface $page
     */
    private \App\Application\SearchResultPageInterface $page;

    /**
     * @var \App\Application\SearchCriteriaInterface|null $searchCriteria
     */
    private ?\App\Application\SearchCriteriaInterface $searchCriteria;

    /**
     * @var string|null $selectionKey
     */
    private ?string $selectionKey;

    /**
     * @param array $items
     * @param int $total full records qty in storage
     * @param \App\Application\SearchResultPageInterface $page
     * @param \App\Application\SearchCriteriaInterface|null $searchCriteria
     * @param string|null $selectionKey
     */
    public function __construct(
        array $items,
        int $total,
        \App\Application\SearchResultPageInterface $page,
        \App\Application\SearchCriteriaInterface $searchCriteria = null,
        string $selectionKey = null
    ) {
        $this->items = $items;
        $this->total = $total;
        $this->page = $page;
        $this->searchCriteria = $searchCriteria;
        $this->selectionKey = $selectionKey;
    }

    /**
     * @inheritDoc
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @inheritDoc
     */
    public function getPage(): \App\Application\SearchResultPageInterface
    {
        return $this->page;
    }

    /**
     * @inheritDoc
     */
    public function getSearchCriteria(): ?\App\Application\SearchCriteriaInterface
    {
        return $this->searchCriteria;
    }

    /**
     * @inheritDoc
     */
    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPage(\App\Application\SearchResultPageInterface $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSearchCriteria(?\App\Application\SearchCriteriaInterface $searchCriteria): self
    {
        $this->searchCriteria = $searchCriteria;

        return $this;
    }

    /**
     * @return int
     */
    public function getItemsCount(): int
    {
        return count($this->items);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            $this->selectionKey ?? self::SELECTION_KEY => $this->items,
            'total' => $this->total,
            'page' => $this->page instanceof \Illuminate\Contracts\Support\Arrayable
                ? $this->page->toArray()
                : $this->page,
        ];
    }
}
