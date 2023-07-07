<?php

declare(strict_types=1);

namespace App\Application\Actions\Permission;

use Psr\Http\Message\ResponseInterface as Response;

class Read extends \App\Application\Actions\Permission\Action
{
    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainException
     * @throws \JsonException
     */
    protected function action(): Response
    {
        try {
            $permission = $this->permissionRepository->get($this->resolveArg('code'));
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $this->respondWithData(['permission' => $permission]);
    }
}
