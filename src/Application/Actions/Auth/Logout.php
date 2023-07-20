<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;

// phpcs:disable Generic.Files.LineLength.TooLong

/**
 * @OA\Post(
 *   path="/logout",
 *   tags={
 *     "Authorization"
 *   },
 *   description="Remove authorization token from database",
 *   security={
 *     {
 *       "token": {}
 *     }
 *   },
 *   @OA\Response(
 *     response="200",
 *     description="Request has been processed successfully, authorization token was removed",
 *     content={
 *       "application/json": {
 *         "example": {
 *           "statusCode": 200,
 *           "data": {
 *             "message": "You were successfully logout."
 *           },
 *         },
 *       },
 *     },
 *   ),
 *   @OA\Response(
 *     response="401",
 *     description="Request was failed, because Authorization header or Bearer token was not provided",
 *     content={
 *       "application/json": {
 *         "example": {
 *           "statusCode": 401,
 *           "error": {
 *             "type": "UNAUTHENTICATED",
 *             "description": "The Authorization header is required. You can retrieve the required authorization token after the successful request to the https://auth.ms/login."
 *           },
 *         },
 *       },
 *     },
 *   ),
 * ),
 */
class Logout extends \App\Application\Actions\Auth\Action
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

        $this->authorizationService->logout($token);

        return $this->respondWithData(['message' => $this->translator->get('auth.logout.success')]);
    }
}
