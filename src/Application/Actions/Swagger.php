<?php

declare(strict_types=1);

namespace App\Application\Actions;

use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * @phpcs:disable Generic.Files.LineLength.TooLong
 *
 * @OA\Info(
 *   title="Authorization Microservice API",
 *   summary="Authorization microservice for qualification work ecosystem",
 *   description="This API provides endpoints for managing users, roles and permissions. Authorization is also served by this API.",
 *   @OA\Contact(
 *     name="Daria Makarenko, Developer",
 *     email="dariavasilega@gmail.com",
 *   ),
 *   version="1.0",
 * )
 */
class Swagger extends \App\Application\Actions\Action
{
    private const ROUTES_CONFIG = 'swagger.yaml';

    /**
     * @var \League\Flysystem\PathPrefixer $pathNormalizer
     */
    private \League\Flysystem\PathPrefixer $pathNormalizer;

    /**
     * @var \League\Flysystem\Filesystem $filesystem
     */
    private \League\Flysystem\Filesystem $filesystem;

    /**
     * @var \Slim\Views\PhpRenderer $renderer
     */
    private \Slim\Views\PhpRenderer $renderer;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \League\Flysystem\Filesystem $filesystem
     * @param \Slim\Views\PhpRenderer $renderer
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Illuminate\Translation\Translator $translator,
        \League\Flysystem\PathPrefixer $pathNormalizer,
        \League\Flysystem\Filesystem $filesystem,
        \Slim\Views\PhpRenderer $renderer,
    ) {
        parent::__construct($logger, $translator);

        $this->pathNormalizer = $pathNormalizer;
        $this->filesystem = $filesystem;
        $this->renderer = $renderer;
    }

    /**
     * @inheritDoc
     * @throws \Throwable
     */
    protected function action(): Response
    {
        $controllersPath = $this->pathNormalizer->prefixPath(
            'src' . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR . 'Actions'
        );

        $routesData = (string) \OpenApi\Generator::scan([$controllersPath])?->toYaml();
        $this->filesystem->write('docker' . DIRECTORY_SEPARATOR . self::ROUTES_CONFIG, $routesData);

        $uri = $this->request->getUri();

        return $this->renderer->render(
            $this->response,
            'swagger.phtml',
            [
                'swaggerConfigPath' => sprintf(
                    '%s://%s/%s',
                    $uri->getScheme(),
                    $uri->getHost(),
                    self::ROUTES_CONFIG
                ),
            ]
        );
    }
}
