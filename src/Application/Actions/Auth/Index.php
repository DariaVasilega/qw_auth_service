<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use Psr\Http\Message\ResponseInterface as Response;

class Index extends \App\Application\Actions\Auth\Action
{
    /**
     * @inheritDoc
     * @throws \JsonException
     * @throws \App\Service\AuthenticationException
     */
    protected function action(): Response
    {
        $authorization = $this->request->getHeaderLine('authorization');

        if (!$authorization || !str_starts_with($authorization, 'Bearer')) {
            throw new \App\Service\AuthenticationException();
        }

        $token = substr($authorization, 7);

        $this->authorizationService->permissions($token);

        return $this->respondWithData(['permissions' => $this->authorizationService->permissions($token)]);
    }
}
