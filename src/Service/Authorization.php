<?php

declare(strict_types=1);

namespace App\Service;

final class Authorization
{
    private const DEFAULT_TOKEN_LENGTH = 63;

    private const MAX_TOKEN_LENGTH = 256;

    private const DEFAULT_TOKEN_EXPIRATION = '+4 hours';

    /**
     * @var \App\Domain\UserRepositoryInterface $userRepository
     */
    private \App\Domain\UserRepositoryInterface $userRepository;

    /**
     * @var \App\Service\Password $passwordService
     */
    private \App\Service\Password $passwordService;

    /**
     * @var \App\Application\Settings\SettingsInterface $settings
     */
    private \App\Application\Settings\SettingsInterface $settings;

    /**
     * @var \Illuminate\Database\Eloquent\Builder $queryBuilder
     */
    private \Illuminate\Database\Eloquent\Builder $queryBuilder;

    /**
     * @param \App\Domain\UserRepositoryInterface $userRepository
     * @param \App\Service\Password $passwordService
     * @param \App\Application\Settings\SettingsInterface $settings
     */
    public function __construct(
        \App\Domain\UserRepositoryInterface $userRepository,
        \App\Service\Password $passwordService,
        \App\Application\Settings\SettingsInterface $settings
    ) {
        $this->settings = $settings;
        $this->passwordService = $passwordService;
        $this->userRepository = $userRepository;
        $this->queryBuilder = \App\Domain\Auth::query();
    }

    /**
     * Generate authorization token and store it in the database
     *
     * @param string|null $email
     * @param string|null $password
     * @return string
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     * @throws \App\Service\AuthenticationException
     */
    public function login(?string $email, ?string $password): string
    {
        $this->authenticate($email, $password);

        $token = $this->generateToken();
        $expiration = date('Y-m-d H:i:s', strtotime(
            $this->settings->get('auth')['token']['expiration'] ?? self::DEFAULT_TOKEN_EXPIRATION
        ));

        $this->queryBuilder
            ->newQuery()
            ->updateOrInsert(
                [
                    'email' => $email
                ],
                [
                    'token' => $token,
                    'expiration' => $expiration
                ]
            );

        return $token;
    }


    /**
     * Remove authorization token from the database
     *
     * @param string|null $token
     * @return bool
     */
    public function logout(?string $token): bool
    {
        $authQuery = $this->queryBuilder
            ->newQuery()
            ->where('token', $token);

        if ($authQuery->exists()) {
            $authQuery->delete();
        }

        return true;
    }

    /**
     * Return actions codes, which may be processed by the user
     *
     * @param string|null $token
     * @return array
     * @throws \App\Service\AuthenticationException
     */
    public function permissions(?string $token): array
    {
        $authQuery = $this->queryBuilder
            ->newQuery()
            ->where('token', $token);

        if (!$authQuery->exists()) {
            throw new \App\Service\AuthenticationException('auth.token.incorrect');
        }

        /** @var \App\Domain\Auth $authEntity */
        $authEntity = $authQuery->first();

        if (time() > strtotime($authEntity->expiration)) {
            throw new \App\Service\AuthenticationException('auth.token.expired');
        }

        $permissions = [];

        /**
         * @var \App\Domain\Role $role
         * @phpstan-ignore-next-line
         */
        foreach ($authEntity->user->roles as $role) {
            $rolePermissions = array_map(
                static fn (\App\Domain\Permission $permission): string => $permission->code,
                /** @phpstan-ignore-next-line */
                $role->permissions->all()
            );
            $permissions = [...$permissions, ...$rolePermissions];
        }

        return array_unique($permissions);
    }

    /**
     * Retrieve the user by email and compare the password
     *
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     * @throws \App\Service\AuthenticationException
     */
    private function authenticate(?string $email, ?string $password): bool
    {
        if (!$email || !$password) {
            throw new \App\Service\AuthenticationException('auth.credentials.empty');
        }

        $user = $this->userRepository->getByEmail($email);

        if (!$this->passwordService->compare($password, $user->password)) {
            throw new \App\Service\AuthenticationException('auth.password.incorrect');
        }

        return true;
    }

    /**
     * @return string
     */
    private function generateToken(): string
    {
        $tokenLength = $this->settings->get('auth')['token']['length'] ?? self::DEFAULT_TOKEN_LENGTH;
        $tokenLength = $tokenLength > self::MAX_TOKEN_LENGTH ? self::MAX_TOKEN_LENGTH : $tokenLength;

        $randomString = md5((string) mt_rand());
        $randomKey = md5((string) mt_rand());
        $hash = hash_hmac('sha256', $randomString, $randomKey);

        return mb_substr($hash, 0, $tokenLength);
    }
}
