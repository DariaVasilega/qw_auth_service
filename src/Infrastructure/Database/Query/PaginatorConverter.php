<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Query;

class PaginatorConverter
{
    /**
     * @var \App\Factory\SearchResultFactory $resultFactory
     */
    private \App\Factory\SearchResultFactory $resultFactory;

    /**
     * @var \App\Factory\SearchResultPageFactory $resultPageFactory
     */
    private \App\Factory\SearchResultPageFactory $resultPageFactory;

    /**
     * @param \App\Factory\SearchResultFactory $resultFactory
     * @param \App\Factory\SearchResultPageFactory $resultPageFactory
     */
    public function __construct(
        \App\Factory\SearchResultFactory $resultFactory,
        \App\Factory\SearchResultPageFactory $resultPageFactory
    ) {
        $this->resultFactory = $resultFactory;
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @param \Unlu\Laravel\Api\Paginator $paginator
     * @param string|null $selectionKey
     * @return \App\Application\SearchResultInterface
     */
    public function convertToSearchResult(
        \Unlu\Laravel\Api\Paginator $paginator,
        string $selectionKey = null
    ): \App\Application\SearchResultInterface {
        return $this->resultFactory->create([
            'items' => $paginator->all(),
            'total' => $paginator->total(),
            'page' => $this->resultPageFactory->create([
                'current' => $paginator->currentPage(),
                'count' => $paginator->lastPage(),
                'limit' => $paginator->perPage(),
            ]),
            'selectionKey' => $selectionKey
        ]);
    }
}
