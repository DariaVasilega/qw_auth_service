<?php

declare(strict_types=1);

namespace App\Console\Admin;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class Create extends \Symfony\Component\Console\Command\Command
{
    private const ADMIN_ROLE_CODE = 'admin';

    /**
     * @var \App\Service\Password $passwordService
     */
    private \App\Service\Password $passwordService;

    /**
     * @var \App\Domain\RoleRepositoryInterface $roleRepository
     */
    private \App\Domain\RoleRepositoryInterface $roleRepository;

    /**
     * @var \App\Domain\UserRepositoryInterface $userRepository
     */
    private \App\Domain\UserRepositoryInterface $userRepository;

    /**
     * @var \App\Domain\User\Validation\Rule $usrValidationRule
     */
    private \App\Domain\User\Validation\Rule $usrValidationRule;

    /**
     * @var \Illuminate\Validation\Factory $usrValidatorFactory
     */
    private \Illuminate\Validation\Factory $usrValidatorFactory;

    /**
     * @param \App\Service\Password $passwordService
     * @param \App\Domain\RoleRepositoryInterface $roleRepository
     * @param \App\Domain\UserRepositoryInterface $userRepository
     * @param \App\Domain\User\Validation\Rule $usrValidationRule
     * @param \Illuminate\Validation\Factory $usrValidatorFactory
     * @param string|null $name
     */
    public function __construct(
        \App\Service\Password $passwordService,
        \App\Domain\RoleRepositoryInterface $roleRepository,
        \App\Domain\UserRepositoryInterface $userRepository,
        \App\Domain\User\Validation\Rule $usrValidationRule,
        \Illuminate\Validation\Factory $usrValidatorFactory,
        string $name = null
    ) {
        parent::__construct($name);

        $this->passwordService = $passwordService;
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
        $this->usrValidationRule = $usrValidationRule;
        $this->usrValidatorFactory = $usrValidatorFactory;
    }

    /**
     * @inheritDoc
     */
    public function configure(): void
    {
        $this->setName('admin:create');
        $this->setDescription('Create administrator');
        $this->addOption(
            'email',
            'e',
            \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'Email'
        );
        $this->addOption(
            'password',
            'p',
            \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'Password'
        );
    }

    /**
     * @inheritDoc
     * @throws \JsonException
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ): int {
        $email = $input->getOption('email');
        $password = $input->getOption('password');

        if (!$email || !$password) {
            throw new \Symfony\Component\Console\Exception\MissingInputException(
                'The command execution requires --email and --password options indication'
            );
        }

        $userData = [
            'email' => $email,
            'password' => $password,
        ];

        $validator = $this->usrValidatorFactory->make(
            $userData,
            $this->usrValidationRule->getRules(),
            $this->usrValidationRule->getMessages()
        );

        if ($validator->fails()) {
            throw new \Symfony\Component\Console\Exception\InvalidOptionException(
                json_encode($validator->getMessageBag(), JSON_THROW_ON_ERROR)
            );
        }

        try {
            $this->roleRepository->get(self::ADMIN_ROLE_CODE);
        } catch (\App\Domain\DomainException\DomainRecordNotFoundException $exception) {
            /** @var \App\Domain\Role $adminRole */
            $adminRole = \App\Domain\Role::query()->make([
                'code' => self::ADMIN_ROLE_CODE,
                'label' => 'Admin',
            ]);

            try {
                $this->roleRepository->save($adminRole);
            } catch (\App\Domain\DomainException\DomainRecordNotSavedException $exception) {
                throw new \Symfony\Component\Console\Exception\RuntimeException($exception->getMessage());
            }
        }

        $userData['password'] = $this->passwordService->hash($userData['password']);

        /** @var \App\Domain\User $user */
        $user = \App\Domain\User::query()->make($userData);

        try {
            $this->userRepository->save($user);
        } catch (\App\Domain\DomainException\DomainRecordNotSavedException $exception) {
            throw new \Symfony\Component\Console\Exception\RuntimeException($exception->getMessage());
        }

        $user->roles()->attach([self::ADMIN_ROLE_CODE]);

        $output->writeln(sprintf(
            '<info>%s "%s" %s</info> %s',
            'The administrator with email',
            $email,
            'has been created successfully',
            PHP_EOL
        ));

        return self::SUCCESS;
    }
}
