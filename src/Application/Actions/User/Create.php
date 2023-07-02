<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class Create extends Store
{
    /**
     * @inheritDoc
     */
    protected function init(array $userData): \App\Domain\User
    {
        /** @var \App\Domain\User $user */
        $user = \App\Domain\User::query()->make($userData);

        return $user;
    }

    /**
     * @inheritDoc
     */
    protected function sendResponse(\App\Domain\User $user): Response
    {
        return $this->respondWithData([
            'message' => $this->translator->get('action.create.success'),
            'user' => [
                'id' => $user->id,
            ],
        ]);
    }
}
