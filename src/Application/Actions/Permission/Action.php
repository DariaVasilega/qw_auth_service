<?php

declare(strict_types=1);

namespace App\Application\Actions\Permission;

abstract class Action extends \App\Application\Actions\Action
{
    /**
     * @var \App\Domain\PermissionRepositoryInterface $permissionRepository
     */
    protected \App\Domain\PermissionRepositoryInterface $permissionRepository;

    /**
     * @param \App\Infrastructure\Filesystem\Log\PermissionActionLogger $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Domain\PermissionRepositoryInterface $permissionRepository
     */
    public function __construct(
        \App\Infrastructure\Filesystem\Log\PermissionActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\PermissionRepositoryInterface $permissionRepository
    ) {
        parent::__construct($logger, $translator);

        $this->permissionRepository = $permissionRepository;
    }
}
