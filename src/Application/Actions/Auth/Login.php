<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use Psr\Http\Message\ResponseInterface as Response;

class Login extends \App\Application\Actions\Auth\Action
{
    /**
     * @inheritDoc
     * @throws \JsonException
     * @throws \App\Service\AuthenticationException
     */
    protected function action(): Response
    {
        $credentials = $this->getFormData();
        $email = $credentials['email'] ?? null;
        $password = $credentials['password'] ?? null;

        $token = $this->authorizationService->login($email, $password);

        return $this->respondWithData(['token' => $token]);
    }
}
