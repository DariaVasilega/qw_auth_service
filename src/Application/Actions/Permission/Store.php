<?php

declare(strict_types=1);

namespace App\Application\Actions\Permission;

use Psr\Http\Message\ResponseInterface as Response;

abstract class Store extends \App\Application\Actions\Permission\Action
{
    /**
     * @var \App\Domain\Permission\Validation\Rule $validationRule
     */
    protected \App\Domain\Permission\Validation\Rule $validationRule;

    /**
     * @var \Illuminate\Validation\Factory $validatorFactory
     */
    protected \Illuminate\Validation\Factory $validatorFactory;

    /**
     * @param \App\Infrastructure\Filesystem\Log\PermissionActionLogger $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Domain\PermissionRepositoryInterface $permissionRepository
     * @param \App\Domain\Permission\Validation\Rule $validationRule
     * @param \Illuminate\Validation\Factory $validatorFactory
     */
    public function __construct(
        \App\Infrastructure\Filesystem\Log\PermissionActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\PermissionRepositoryInterface $permissionRepository,
        \App\Domain\Permission\Validation\Rule $validationRule,
        \Illuminate\Validation\Factory $validatorFactory
    ) {
        parent::__construct($logger, $translator, $permissionRepository);

        $this->validationRule = $validationRule;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     * @throws \App\Domain\DomainException\DomainException
     * @throws \JsonException
     */
    protected function action(): Response
    {
        $permissionData = $this->getFormData();

        $this->validate($permissionData);
        $permission = $this->init($permissionData);
        $this->save($permission);

        return $this->sendResponse($permission);
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

        $validator = $this->validatorFactory->make(
            $permissionData,
            $this->validationRule->getRules($permissionCode, $ignoreCode),
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
     * Save user
     *
     * @throws \App\Domain\DomainException\DomainException
     */
    protected function save(\App\Domain\Permission $permission): void
    {
        try {
            $this->permissionRepository->save($permission);
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }
    }

    /**
     * Init user
     *
     * @param array $permissionData
     * @return \App\Domain\Permission
     */
    abstract protected function init(array $permissionData): \App\Domain\Permission;

    /**
     * Send response
     *
     * @param \App\Domain\Permission $permission
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \JsonException
     */
    abstract protected function sendResponse(\App\Domain\Permission $permission): Response;
}
