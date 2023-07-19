<?php

declare(strict_types=1);

return function (\Slim\App $app) {
    // Swagger
    $app->get('/swagger', \App\Application\Actions\Swagger::class);

    // Auth Actions
    $app->post('/login', \App\Application\Actions\Auth\Login::class);
    $app->post('/logout', \App\Application\Actions\Auth\Logout::class);
    $app->get('/auth', \App\Application\Actions\Auth\Index::class);

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

            // Role Permissions CRUD
            $router->group('/permissions', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
                $router->post('', \App\Application\Actions\Role\Permissions\Attach::class);
                $router->get('', \App\Application\Actions\Role\Permissions\ReadList::class);
                $router->put('', \App\Application\Actions\Role\Permissions\Sync::class);
                $router->delete('', \App\Application\Actions\Role\Permissions\Detach::class);
            });
        });
    });

    // Permission CRUD
    $app->group('/permission', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
        $router->post('', \App\Application\Actions\Permission\Create::class);
        $router->get('s', \App\Application\Actions\Permission\ReadList::class);
        $router->group('/{code:[A-z0-9_-]+}', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
            $router->get('', \App\Application\Actions\Permission\Read::class);
            $router->put('', \App\Application\Actions\Permission\Update::class);
            $router->delete('', \App\Application\Actions\Permission\Delete::class);
        });
    });
};
