<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

abstract class Action extends \App\Application\Actions\Action
{
    /**
     * @var \App\Service\Authorization $authorizationService
     */
    protected \App\Service\Authorization $authorizationService;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Service\Authorization $authorizationService
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Service\Authorization $authorizationService
    ) {
        parent::__construct($logger, $translator);

        $this->authorizationService = $authorizationService;
    }
}
