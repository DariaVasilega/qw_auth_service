<?php

declare(strict_types=1);

namespace App\Infrastructure;

final class SearchResultPage implements
    \App\Application\SearchResultPageInterface,
    \Illuminate\Contracts\Support\Arrayable
{
    /**
     * @var int $current
     */
    private int $current;

    /**
     * @var int $count
     */
    private int $count;

    /**
     * @var int $limit
     */
    private int $limit;

    /**
     * @param int $current
     * @param int $count
     * @param int $limit
     */
    public function __construct(
        int $current,
        int $count,
        int $limit
    ) {
        $this->current = $current;
        $this->count = $count;
        $this->limit = $limit;
    }

    /**
     * @inheritDoc
     */
    public function getCurrent(): int
    {
        return $this->current;
    }

    /**
     * @inheritDoc
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @inheritDoc
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @inheritDoc
     */
    public function setCurrent(int $current): self
    {
        $this->current = $current;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'current' => $this->current,
            'count' => $this->count,
            'limit' => $this->limit,
        ];
    }
}
