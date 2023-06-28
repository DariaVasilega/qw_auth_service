<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class ReadList extends Show
{
    /**
     * @var \App\Factory\SearchCriteriaFactory $criteriaFactory
     */
    private \App\Factory\SearchCriteriaFactory $criteriaFactory;

    public function __construct(
        \App\Infrastructure\Filesystem\Log\UserActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\UserRepositoryInterface $userRepository,
        \App\Factory\SearchCriteriaFactory $criteriaFactory
    ) {
        parent::__construct(
            $logger,
            $translator,
            $userRepository
        );

        $this->criteriaFactory = $criteriaFactory;
    }

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainException
     * @throws \JsonException
     * @throws \Exception
     */
    protected function action(): Response
    {
        try {
            $searchResult = $this->userRepository->getList(
                $this->criteriaFactory->create([
                    'model' => \App\Domain\User::query()->make(),
                    'request' => \Illuminate\Http\Request::capture()
                ])
            );
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $this->respondWithData(
            $searchResult->setItems(
                array_map([$this, 'prepare'], $searchResult->getItems())
            )
        );
    }
}
