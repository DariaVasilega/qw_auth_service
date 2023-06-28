<?php

declare(strict_types=1);

namespace App\Application;

interface SearchResultPageInterface
{
    /**
     * @return int
     */
    public function getCurrent(): int;

    /**
     * @return int
     */
    public function getCount(): int;

    /**
     * @return int
     */
    public function getLimit(): int;

    /**
     * @param int $current
     * @return $this
     */
    public function setCurrent(int $current): self;

    /**
     * @param int $count
     * @return $this
     */
    public function setCount(int $count): self;

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit(int $limit): self;
}
