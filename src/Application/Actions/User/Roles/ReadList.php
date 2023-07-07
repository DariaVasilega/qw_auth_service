<?php

declare(strict_types=1);

namespace App\Application\Actions\User\Roles;

use App\Application\Actions\User\Action as UserAction;
use Psr\Http\Message\ResponseInterface as Response;

class ReadList extends UserAction
{
    /**
     * @inheritDoc
     * @throws \JsonException
     */
    protected function action(): Response
    {
        try {
            $user = $this->userRepository->get((int) $this->resolveArg('id'));
        } catch (\App\Domain\DomainException\DomainRecordNotFoundException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $this->respondWithData([
            'roles' => $user->roles()->get(),
        ]);
    }
}
