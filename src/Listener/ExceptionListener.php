<?php

declare(strict_types=1);

namespace App\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (
            $event->getThrowable() instanceof UnprocessableEntityHttpException
            && $event->getThrowable()->getPrevious() instanceof ValidationFailedException
        ) {
            $data = [];

            foreach ($event->getThrowable()->getPrevious()->getViolations() as $violation) {
                $data[] = [
                    'message' => $violation->getMessage(),
                    'property' => $violation->getPropertyPath(),
                ];
            }

            $event->setResponse(new JsonResponse($data));
        } else {
            $event->setResponse(new JsonResponse(['message' => $event->getThrowable()->getMessage()]));
        }
    }
}
