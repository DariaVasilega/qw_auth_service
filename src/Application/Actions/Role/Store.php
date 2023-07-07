<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use Psr\Http\Message\ResponseInterface as Response;

abstract class Store extends \App\Application\Actions\Role\Action
{
    /**
     * @var \App\Domain\Role\Validation\Rule $validationRule
     */
    protected \App\Domain\Role\Validation\Rule $validationRule;

    /**
     * @var \Illuminate\Validation\Factory $validatorFactory
     */
    protected \Illuminate\Validation\Factory $validatorFactory;

    /**
     * @param \App\Infrastructure\Filesystem\Log\RoleActionLogger $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Domain\RoleRepositoryInterface $roleRepository
     * @param \App\Domain\Role\Validation\Rule $validationRule
     * @param \Illuminate\Validation\Factory $validatorFactory
     */
    public function __construct(
        \App\Infrastructure\Filesystem\Log\RoleActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\RoleRepositoryInterface $roleRepository,
        \App\Domain\Role\Validation\Rule $validationRule,
        \Illuminate\Validation\Factory $validatorFactory
    ) {
        parent::__construct($logger, $translator, $roleRepository);

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
        $roleData = $this->getFormData();

        $this->validate($roleData);
        $role = $this->init($roleData);
        $this->save($role);

        return $this->sendResponse($role);
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

        $validator = $this->validatorFactory->make(
            $roleData,
            $this->validationRule->getRules($roleCode, $ignoreCode),
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
    protected function save(\App\Domain\Role $role): void
    {
        try {
            $this->roleRepository->save($role);
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }
    }

    /**
     * Init user
     *
     * @param array $roleData
     * @return \App\Domain\Role
     */
    abstract protected function init(array $roleData): \App\Domain\Role;

    /**
     * Send response
     *
     * @param \App\Domain\Role $role
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \JsonException
     */
    abstract protected function sendResponse(\App\Domain\Role $role): Response;
}
