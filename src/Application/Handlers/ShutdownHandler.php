<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\ResponseEmitter\ResponseEmitter;
use Illuminate\Translation\Translator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;

class ShutdownHandler
{
    /**
     * @var Request $request
     */
    private Request $request;

    /**
     * @var HttpErrorHandler $errorHandler
     */
    private HttpErrorHandler $errorHandler;

    /**
     * @var bool $displayErrorDetails
     */
    private bool $displayErrorDetails;

    /**
     * @var Translator $translator
     */
    private Translator $translator;

    /**
     * @param Request $request
     * @param HttpErrorHandler $errorHandler
     * @param bool $displayErrorDetails
     * @param Translator $translator
     */
    public function __construct(
        Request $request,
        HttpErrorHandler $errorHandler,
        bool $displayErrorDetails,
        Translator $translator
    ) {
        $this->request = $request;
        $this->errorHandler = $errorHandler;
        $this->displayErrorDetails = $displayErrorDetails;
        $this->translator = $translator;
    }

    public function __invoke()
    {
        $error = error_get_last();
        if ($error) {
            $errorFile = $error['file'];
            $errorLine = $error['line'];
            $errorMessage = $error['message'];
            $errorType = $error['type'];
            $message = 'http.shutdown';

            if ($this->displayErrorDetails) {
                switch ($errorType) {
                    case E_USER_ERROR:
                        $message = "FATAL ERROR: {$errorMessage}. ";
                        $message .= " on line {$errorLine} in file {$errorFile}.";
                        break;

                    case E_USER_WARNING:
                        $message = "WARNING: {$errorMessage}";
                        break;

                    case E_USER_NOTICE:
                        $message = "NOTICE: {$errorMessage}";
                        break;

                    default:
                        $message = "ERROR: {$errorMessage}";
                        $message .= " on line {$errorLine} in file {$errorFile}.";
                        break;
                }
            }

            $exception = new HttpInternalServerErrorException($this->request, $this->translator->get($message));
            $response = $this->errorHandler->__invoke(
                $this->request,
                $exception,
                $this->displayErrorDetails,
                false,
                false,
            );

            $responseEmitter = new ResponseEmitter();
            $responseEmitter->emit($response);
        }
    }
}
