<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\EntityAlreadyExistsException;
use App\Exception\ValidatorException;
use App\Request\Payload\CreateOrderPayload;
use App\Request\Payload\UpdateOrderPayload;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiController extends AbstractController
{
    public function __construct(
        private readonly OrderService $service,
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * @throws \Exception
     */
    #[Route('', name: 'order_create', methods: [Request::METHOD_POST])]
    public function createOrder(
        #[MapRequestPayload] CreateOrderPayload $payload,
    ): JsonResponse {
        try {
            $this->service->create($payload);

            return new JsonResponse(status: Response::HTTP_CREATED);
        } catch (EntityAlreadyExistsException $exception) {
            $data = ['message' => $this->translator->trans($exception->getMessage(), [], 'api')];
            $status = $exception->getStatusCode();
        } catch (ValidatorException $exception) {
            $data = [];

            foreach ($exception->getConstraintViolationList() as $violation) {
                $data[] = [
                    'message' => $violation->getMessage(),
                    'property' => $violation->getPropertyPath(),
                ];
            }
            $status = $exception->getCode();
        }

        return new JsonResponse($data, $status);
    }

    /**
     * @throws \Exception
     */
    #[Route('', name: 'order_update', methods: [Request::METHOD_PUT])]
    public function updateOrder(
        #[MapRequestPayload] UpdateOrderPayload $payload,
    ): JsonResponse {
        try {
            $this->service->update($payload);

            return new JsonResponse();
        } catch (EntityAlreadyExistsException $exception) {
            $data = ['message' => $this->translator->trans($exception->getMessage(), [], 'api')];
            $status = $exception->getStatusCode();
        } catch (ValidatorException $exception) {
            $data = [];

            foreach ($exception->getConstraintViolationList() as $violation) {
                $data[] = [
                    'message' => $violation->getMessage(),
                    'property' => $violation->getPropertyPath(),
                ];
            }
            $status = $exception->getCode();
        }

        return new JsonResponse($data, $status);
    }
}
