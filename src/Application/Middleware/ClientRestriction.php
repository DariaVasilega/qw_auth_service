<?php

declare(strict_types=1);

namespace App\Application\Middleware;

class ClientRestriction implements \Psr\Http\Server\MiddlewareInterface
{
    /**
     * @var \App\Application\Settings\SettingsInterface $settings
     */
    private \App\Application\Settings\SettingsInterface $settings;

    /**
     * @param \App\Application\Settings\SettingsInterface $settings
     */
    public function __construct(
        \App\Application\Settings\SettingsInterface $settings
    ) {
        $this->settings = $settings;
    }

    /**
     * @inheritDoc
     */
    public function process(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Server\RequestHandlerInterface $handler
    ): \Psr\Http\Message\ResponseInterface {
        $serverParams = $request->getServerParams();
        $client = str_replace(['https://', 'http://'], '', $serverParams['HTTP_REFERER'] ?? '');
        $client = rtrim($client, '/');

        if (!in_array($client, $this->settings->get('allowedClients'), true)) {
            throw new \Slim\Exception\HttpForbiddenException($request, 'http.error.403');
        }

        return $handler->handle($request);
    }
}
