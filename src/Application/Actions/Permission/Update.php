<?php

declare(strict_types=1);

namespace App\Application\Actions\Permission;

use Psr\Http\Message\ResponseInterface as Response;

class Update extends \App\Application\Actions\Permission\Store
{
    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $permissionData = $this->getFormData();
        $permission = $this->init($permissionData);

        $this->validate($permissionData, true);

        $this->save($permission->fill($permissionData));

        return $this->sendResponse($permission);
    }

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainException
     */
    protected function init(array $permissionData): \App\Domain\Permission
    {
        try {
            $permission = $this->permissionRepository->get($this->resolveArg('code'));
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $permission;
    }

    /**
     * Validate user data before save
     *
     * @param array $permissionData
     * @param bool $ignoreCode
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     * @throws \JsonException
     */
    protected function validate(array $permissionData, bool $ignoreCode = false): bool
    {
        try {
            $permissionCode = $this->resolveArg('code');
        } catch (\Slim\Exception\HttpBadRequestException $exception) {
            $permissionCode = $permissionData['code'];
        }

        $allValidationRules = $this->validationRule->getRules($permissionCode, $ignoreCode);
        $suitableRules = array_intersect_key($allValidationRules, $permissionData);

        $validator = $this->validatorFactory->make(
            $permissionData,
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
    protected function sendResponse(\App\Domain\Permission $permission): Response
    {
        return $this->respondWithData(['message' => $this->translator->get('action.update.success')]);
    }
}
