<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * @OA\Post(
 *   path="/login",
 *   tags={
 *     "Authorization"
 *   },
 *   description="Retrieve authorization token",
 *   @OA\RequestBody(
 *     description="Retrieving authorization token requires email and password",
 *     @OA\JsonContent(
 *       example={
 *         "email": "email@example.com",
 *         "password": "s0me_paSSw0rd",
 *       },
 *       required={
 *         "email",
 *         "password",
 *       },
 *       @OA\Property(
 *         property="email",
 *         type="string",
 *       ),
 *       @OA\Property(
 *         property="password",
 *         type="string",
 *       ),
 *     ),
 *     required=true
 *   ),
 *   @OA\Response(
 *     response="200",
 *     description="Request has been processed successfully, authorization token was generated",
 *     content={
 *       "application/json": {
 *         "example": {
 *           "statusCode": 200,
 *           "data": {
 *             "token": "340c59b6b82f98916d0b75ec9a7c6f6ea3256245b9e5cb3d600e38fced7bef4"
 *           },
 *         },
 *       },
 *     },
 *   ),
 *   @OA\Response(
 *     response="401",
 *     description="Request was failed, because email or password was empty",
 *     content={
 *       "application/json": {
 *         "example": {
 *           "statusCode": 401,
 *           "error": {
 *             "type": "UNAUTHENTICATED",
 *             "description": "The email and the password fields must not be empty."
 *           },
 *         },
 *       },
 *     },
 *   ),
 *   @OA\Response(
 *     response="401 ",
 *     description="Request was failed, because password is incorrect",
 *     content={
 *       "application/json": {
 *         "example": {
 *           "statusCode": 401,
 *           "error": {
 *             "type": "UNAUTHENTICATED",
 *             "description": "Incorrect password."
 *           },
 *         },
 *       },
 *     },
 *   ),
 *   @OA\Response(
 *     response="404 ",
 *     description="Request was failed, because user with this email was not found",
 *     content={
 *       "application/json": {
 *         "example": {
 *           "statusCode": 404,
 *           "error": {
 *             "type": "RESOURCE_NOT_FOUND",
 *             "description": "The requested entity was not found."
 *           },
 *         },
 *       },
 *     },
 *   ),
 * ),
 */
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
