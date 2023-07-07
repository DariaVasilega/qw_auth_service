<?php

declare(strict_types=1);

namespace App\Application\Actions\User\Roles;

use Psr\Http\Message\ResponseInterface as Response;

abstract class Action extends \App\Application\Actions\User\Action
{
    protected const ROLES_CODES = 'codes';

    protected const RESPONSE = '';

    /**
     * @inheritDoc
     * @throws \JsonException
     * @throws \Exception
     */
    protected function action(): Response
    {
        $formData = $this->getFormData();
        $rolesCodes = $formData[static::ROLES_CODES] ?? null;

        $this->validate($rolesCodes);

        try {
            $this->processRelations($rolesCodes);
        } catch (\Exception $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $this->respondWithData([
            'message' => $this->translator->get(static::RESPONSE),
        ]);
    }

    /**
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     */
    protected function initUser(): \App\Domain\User
    {
        return $this->userRepository->get((int) $this->resolveArg('id'));
    }

    /**
     * @param mixed $rolesCodes
     * @return bool
     */
    protected function validate(mixed $rolesCodes): bool
    {
        if (!$rolesCodes || !is_array($rolesCodes) || !reset($rolesCodes)) {
            throw new \Slim\Exception\HttpBadRequestException(
                $this->request,
                $this->translator->get(
                    'validation.request.parameter.type',
                    ['parameter' => 'codes', 'type' => 'array']
                )
            );
        }

        return true;
    }

    /**
     * @param array $rolesCodes
     * @return void
     */
    abstract protected function processRelations(array $rolesCodes): void;
}
