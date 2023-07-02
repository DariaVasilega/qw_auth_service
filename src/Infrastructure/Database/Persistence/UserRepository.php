<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Persistence;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UserRepository implements \App\Domain\UserRepositoryInterface
{
    /**
     * @var \App\Infrastructure\Database\Query\PaginatorConverter $paginatorConverter
     */
    private \App\Infrastructure\Database\Query\PaginatorConverter $paginatorConverter;

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
    public function save(\App\Domain\User $user): bool
    {
        try {
            return $user->save();
        } catch (\Exception $exception) {
            $resetIncrement = \App\Domain\User::query()->max('id') - 1;

            $fixIncrementQuery = <<<SQL
ALTER TABLE `{$user->getTable()}` AUTO_INCREMENT=$resetIncrement;
SQL;

            \App\Domain\User::query()->getConnection()->statement($fixIncrementQuery);

            throw new \App\Domain\DomainException\DomainRecordNotSavedException(
                'repository.error.not_saved',
                (int) $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function get(int $userId): \App\Domain\User
    {
        try {
            /** @var \App\Domain\User $user */
            $user = \App\Domain\User::query()->findOrFail($userId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            throw new \App\Domain\DomainException\DomainRecordNotFoundException(
                'repository.error.not_found',
                (int) $exception->getCode(),
                $exception
            );
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function getByEmail(string $email): \App\Domain\User
    {
        try {
            /** @var \App\Domain\User|null $user */
            $user = \App\Domain\User::query()->where('email', $email)->get()->firstOrFail();
        } catch (\Illuminate\Support\ItemNotFoundException $exception) {
            throw new \App\Domain\DomainException\DomainRecordNotFoundException(
                'repository.error.not_found',
                (int) $exception->getCode(),
                $exception
            );
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \App\Application\SearchCriteriaInterface $searchCriteria
    ): \App\Application\SearchResultInterface {
        try {
            $paginator = $searchCriteria->build()->paginate();
        } catch (\Unlu\Laravel\Api\Exceptions\UnknownColumnException | \InvalidArgumentException $exception) {
            throw new \App\Domain\DomainException\DomainException(
                $exception->getMessage(),
                (int) $exception->getCode(),
                $exception
            );
        }

        return $this->paginatorConverter->convertToSearchResult($paginator, 'users');
    }

    /**
     * @inheritDoc
     */
    public function delete(\App\Domain\User $user): bool
    {
        try {
            $user->delete();
        } catch (\LogicException $exception) {
            throw new \App\Domain\DomainException\DomainRecordNotRemovedException(
                'repository.error.not_removed',
                (int) $exception->getCode(),
                $exception
            );
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $userId): bool
    {
        $this->delete($this->get($userId));

        return true;
    }
}
