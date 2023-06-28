<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

abstract class Action extends \App\Application\Actions\Action
{
    /**
     * @var \App\Domain\UserRepositoryInterface $userRepository
     */
    protected \App\Domain\UserRepositoryInterface $userRepository;

    /**
     * @param \App\Infrastructure\Filesystem\Log\UserActionLogger $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Domain\UserRepositoryInterface $userRepository
     */
    public function __construct(
        \App\Infrastructure\Filesystem\Log\UserActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\UserRepositoryInterface $userRepository
    ) {
        parent::__construct($logger, $translator);

        $this->userRepository = $userRepository;
    }
}
