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
    $app->group('/user', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
        $router->post('', \App\Application\Actions\User\Create::class);
        $router->get('s', \App\Application\Actions\User\ReadList::class);
        $router->group('/{id:[0-9]+}', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
            $router->get('', \App\Application\Actions\User\Read::class);
            $router->put('', \App\Application\Actions\User\Update::class);
            $router->delete('', \App\Application\Actions\User\Delete::class);

            // User Roles CRUD
            $router->group('/roles', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
                $router->post('', \App\Application\Actions\User\Roles\Attach::class);
                $router->get('', \App\Application\Actions\User\Roles\ReadList::class);
                $router->put('', \App\Application\Actions\User\Roles\Sync::class);
                $router->delete('', \App\Application\Actions\User\Roles\Detach::class);
            });
        });
    });

    // Role CRUD
    $app->group('/role', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
        $router->post('', \App\Application\Actions\Role\Create::class);
        $router->get('s', \App\Application\Actions\Role\ReadList::class);
        $router->group('/{code:[A-z0-9_-]+}', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
            $router->get('', \App\Application\Actions\Role\Read::class);
            $router->put('', \App\Application\Actions\Role\Update::class);
            $router->delete('', \App\Application\Actions\Role\Delete::class);
        });
    });
};
