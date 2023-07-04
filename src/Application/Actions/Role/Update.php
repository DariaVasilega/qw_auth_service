<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use Psr\Http\Message\ResponseInterface as Response;

class Update extends \App\Application\Actions\Role\Store
{
    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $roleData = $this->getFormData();
        $role = $this->init($roleData);

        $this->validate($roleData, true);

        $this->save($role->fill($roleData));

        return $this->sendResponse($role);
    }

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainException
     */
    protected function init(array $roleData): \App\Domain\Role
    {
        try {
            $role = $this->roleRepository->get($this->resolveArg('code'));
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $role;
    }

    /**
     * Validate user data before save
     *
     * @param array $roleData
     * @param bool $ignoreCode
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     * @throws \JsonException
     */
    protected function validate(array $roleData, bool $ignoreCode = false): bool
    {
        try {
            $roleCode = $this->resolveArg('code');
        } catch (\Slim\Exception\HttpBadRequestException $exception) {
            $roleCode = $roleData['code'];
        }

        $allValidationRules = $this->validationRule->getRules($roleCode, $ignoreCode);
        $suitableRules = array_intersect_key($allValidationRules, $roleData);

        $validator = $this->validatorFactory->make(
            $roleData,
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
    protected function sendResponse(\App\Domain\Role $role): Response
    {
        return $this->respondWithData(['message' => $this->translator->get('action.update.success')]);
    }
}
