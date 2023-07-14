<?php

declare(strict_types=1);

namespace App\Service;

class AuthenticationException extends \Exception
{
    /**
     * @inheritDoc
     */
    protected $message = 'http.error.401';
}
