<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

class ValidationFailedExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof ValidationFailedException) {
            return;
        }

        $errors = [];

        foreach ($exception->getViolations() as $violation) {
            $errors[] = $violation->getMessage();
        }

        $response = new JsonResponse([
            'result' => 'error',
            'errors' => $errors
        ]);

        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        $event->setResponse($response);
    }
}