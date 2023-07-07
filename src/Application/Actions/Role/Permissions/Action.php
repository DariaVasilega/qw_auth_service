<?php

declare(strict_types=1);

namespace App\Application\Actions\Role\Permissions;

use Psr\Http\Message\ResponseInterface as Response;

abstract class Action extends \App\Application\Actions\Role\Action
{
    protected const PERMISSIONS_CODES = 'codes';

    protected const RESPONSE = '';

    /**
     * @inheritDoc
     * @throws \JsonException
     * @throws \Exception
     */
    protected function action(): Response
    {
        $formData = $this->getFormData();
        $permissionsCodes = $formData[static::PERMISSIONS_CODES] ?? null;

        $this->validate($permissionsCodes);

        try {
            $this->processRelations($permissionsCodes);
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
    protected function initRole(): \App\Domain\Role
    {
        return $this->roleRepository->get($this->resolveArg('code'));
    }

    /**
     * @param mixed $permissionsCodes
     * @return bool
     */
    protected function validate(mixed $permissionsCodes): bool
    {
        if (!$permissionsCodes || !is_array($permissionsCodes) || !reset($permissionsCodes)) {
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
     * @param array $permissionsCodes
     * @return void
     */
    abstract protected function processRelations(array $permissionsCodes): void;
}
