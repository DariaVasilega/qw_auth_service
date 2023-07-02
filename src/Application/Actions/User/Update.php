<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class Update extends Store
{
    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $userData = $this->getFormData();
        $user = $this->init($userData);
        $userData['id'] = $user->id;

        $this->validate($userData);
        $this->prepare($userData);

        array_walk($userData, static fn (mixed $value, string $attribute) => $user->{$attribute} = $value);

        $this->save($user);

        return $this->sendResponse($user);
    }

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainException
     */
    protected function init(array $userData): \App\Domain\User
    {
        try {
            $user = $this->userRepository->get((int) $this->resolveArg('id'));
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    protected function sendResponse(\App\Domain\User $user): Response
    {
        return $this->respondWithData(['message' => $this->translator->get('action.update.success')]);
    }
}
