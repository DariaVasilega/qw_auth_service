<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;

// phpcs:disable Generic.Files.LineLength.TooLong

/**
 * @OA\Get(
 *   path="/auth",
 *   tags={
 *     "Authorization"
 *   },
 *   description="Get parmissions list",
 *   security={
 *     {
 *       "token": {}
 *     }
 *   },
 *   @OA\Response(
 *     response="200",
 *     description="Request has been processed successfully, you got a permissions list",
 *     content={
 *       "application/json": {
 *         "example": {
 *           "statusCode": 200,
 *           "data": {
 *             "permissions": {
 *               "entity1_action1",
 *               "entity1_action2",
 *               "entity1_action3",
 *               "entity1_action4",
 *               "entity2_action1",
 *               "entity2_action2",
 *               "entity2_action3",
 *               "entity2_action4"
 *             }
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
 *   @OA\Response(
 *     response="401 ",
 *     description="Request was failed, because the authorization token is related to no one user",
 *     content={
 *       "application/json": {
 *         "example": {
 *           "statusCode": 401,
 *           "error": {
 *             "type": "UNAUTHENTICATED",
 *             "description": "The system can not identify you."
 *           },
 *         },
 *       },
 *     },
 *   ),
 *   @OA\Response(
 *     response="401  ",
 *     description="Request was failed, because the authorization token is expired",
 *     content={
 *       "application/json": {
 *         "example": {
 *           "statusCode": 401,
 *           "error": {
 *             "type": "UNAUTHENTICATED",
 *             "description": "Your authorization token is expired."
 *           },
 *         },
 *       },
 *     },
 *   ),
 * ),
 */
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
