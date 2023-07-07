<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Service\Password as PasswordService;
use Psr\Http\Message\ResponseInterface as Response;

abstract class Store extends Action
{
    /**
     * @var \App\Domain\User\Validation\Rule $validationRule
     */
    protected \App\Domain\User\Validation\Rule $validationRule;

    /**
     * @var \Illuminate\Validation\Factory $validatorFactory
     */
    protected \Illuminate\Validation\Factory $validatorFactory;

    /**
     * @var PasswordService $passwordService
     */
    protected PasswordService $passwordService;

    /**
     * @param \App\Infrastructure\Filesystem\Log\UserActionLogger $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Domain\UserRepositoryInterface $userRepository
     * @param \App\Domain\User\Validation\Rule $validationRule
     * @param \Illuminate\Validation\Factory $validatorFactory
     */
    public function __construct(
        \App\Infrastructure\Filesystem\Log\UserActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\UserRepositoryInterface $userRepository,
        \App\Domain\User\Validation\Rule $validationRule,
        \Illuminate\Validation\Factory $validatorFactory,
        PasswordService $passwordService
    ) {
        parent::__construct($logger, $translator, $userRepository);

        $this->validationRule = $validationRule;
        $this->validatorFactory = $validatorFactory;
        $this->passwordService = $passwordService;
    }

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     * @throws \App\Domain\DomainException\DomainException
     * @throws \JsonException
     */
    protected function action(): Response
    {
        $userData = $this->getFormData();

        $this->validate($userData);
        $this->prepare($userData);
        $user = $this->init($userData);
        $this->save($user);

        return $this->sendResponse($user);
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
        $validator = $this->validatorFactory->make(
            $userData,
            $this->validationRule->getRules($userData['id'] ?? 0),
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
     * Prepare user data before save
     *
     * @param array $userData
     * @return void
     */
    protected function prepare(array &$userData): void
    {
        if (!isset($userData['password'])) {
            return;
        }

        $userData['password'] = $this->passwordService->hash($userData['password']);
    }

    /**
     * Save user
     *
     * @throws \App\Domain\DomainException\DomainException
     */
    protected function save(\App\Domain\User $user): void
    {
        try {
            $this->userRepository->save($user);
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }
    }

    /**
     * Init user
     *
     * @param array $userData
     * @return \App\Domain\User
     */
    abstract protected function init(array $userData): \App\Domain\User;

    /**
     * Send response
     *
     * @param \App\Domain\User $user
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \JsonException
     */
    abstract protected function sendResponse(\App\Domain\User $user): Response;
}
