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

        $this->save($user->fill($userData));

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
     * Validate user data before save
     *
     * @param array $userData
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     * @throws \JsonException
     */
    protected function validate(array $userData): bool
    {
        $allValidationRules = $this->validationRule->getRules($userData['id'] ?? 0);
        $suitableRules = array_intersect_key($allValidationRules, $userData);

        $validator = $this->validatorFactory->make(
            $userData,
            $suitableRules,
            $this->validationRule->getMessages()
        );

        if ($validator->fails()) {
            throw new \App\Domain\DomainException\DomainRecordNotSavedException(
                json_encode($validator->getMessageBag(), JSON_THROW_ON_ERROR)
            );
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function sendResponse(\App\Domain\User $user): Response
    {
        return $this->respondWithData(['message' => $this->translator->get('action.update.success')]);
    }
}
