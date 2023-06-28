<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (\Slim\App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    // User CRUD
    $app->get('/users', \App\Application\Actions\User\ReadList::class);
    $app->post('/user', \App\Application\Actions\User\Create::class);
    $app->get('/user/{id:[0-9]+}', \App\Application\Actions\User\Read::class);
    $app->put('/user/{id:[0-9]+}', \App\Application\Actions\User\Update::class);
    $app->delete('/user/{id:[0-9]+}', \App\Application\Actions\User\Delete::class);
};
