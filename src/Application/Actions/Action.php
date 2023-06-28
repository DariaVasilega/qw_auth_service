<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\DomainException\DomainException;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Illuminate\Translation\Translator;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class Action
{
    /**
     * @var LoggerInterface $logger
     */
    protected LoggerInterface $logger;

    /**
     * @var Translator $translator
     */
    protected Translator $translator;

    /**
     * @var Request $request
     */
    protected Request $request;

    /**
     * @var Response $response
     */
    protected Response $response;

    /**
     * @var array $args
     */
    protected array $args;

    /**
     * @param LoggerInterface $logger
     * @param Translator $translator
     */
    public function __construct(
        LoggerInterface $logger,
        Translator $translator
    ) {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @throws HttpNotFoundException
     * @throws HttpBadRequestException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            return $this->action();
        } catch (DomainRecordNotFoundException $exception) {
            throw new HttpNotFoundException($this->request, $exception->getMessage());
        } catch (DomainException $exception) {
            throw new HttpBadRequestException($this->request, $exception->getMessage());
        }
    }

    /**
     * @throws DomainException
     * @throws HttpBadRequestException
     */
    abstract protected function action(): Response;

    /**
     * @return array|object
     */
    protected function getFormData(): object|array
    {
        return $this->request->getParsedBody() ?? [];
    }

    /**
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveArg(string $name): mixed
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * @param object|array|null $data
     * @throws JsonException
     */
    protected function respondWithData(object|array $data = null, int $statusCode = 200): Response
    {
        $payload = new ActionPayload($statusCode, $data);

        return $this->respond($payload);
    }

    /**
     * @throws JsonException
     */
    protected function respond(ActionPayload $payload): Response
    {
        $json = json_encode($payload, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);

        return $this->response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus($payload->getStatusCode());
    }
}
