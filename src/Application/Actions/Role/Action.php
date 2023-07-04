<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

abstract class Action extends \App\Application\Actions\Action
{
    /**
     * @var \App\Domain\RoleRepositoryInterface $roleRepository
     */
    protected \App\Domain\RoleRepositoryInterface $roleRepository;

    /**
     * @param \App\Infrastructure\Filesystem\Log\RoleActionLogger $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Domain\RoleRepositoryInterface $roleRepository
     */
    public function __construct(
        \App\Infrastructure\Filesystem\Log\RoleActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\RoleRepositoryInterface $roleRepository
    ) {
        parent::__construct($logger, $translator);

        $this->roleRepository = $roleRepository;
    }
}
