<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Exception\EntityAlreadyExistsException;
use App\Exception\EntityNotFoundException;
use App\Exception\ValidatorException;
use App\Repository\OrderRepository;
use App\Request\Payload\AbstractOrderPayload;
use App\Request\Payload\CreateOrderPayload;
use App\Request\Payload\UpdateOrderPayload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class OrderService
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderRepository $repository,
        private ValidatorInterface $validator,
    ) {
    }

    /**
     * @throws EntityAlreadyExistsException
     * @throws ValidatorException
     * @throws \Exception
     */
    public function create(CreateOrderPayload $payload): void
    {
        $entity = $this->tryGetEntity($payload);

        if ($entity instanceof Order) {
            throw new EntityAlreadyExistsException();
        }

        $entity = self::createEntity($payload);

        $errors = $this->validator->validate($entity);

        if ($errors->count()) {
            throw ValidatorException::create($errors);
        }

        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * @throws \Exception
     */
    private static function createEntity(CreateOrderPayload $payload): Order
    {
        $entity = new Order();
        $entity->setPartnerId($payload->getPartnerId());
        $entity->setOrderId($payload->getOrderId());
        $entity->setPrice($payload->getPrice());
        $entity->setExpectedDeliveryDate($payload->getExpectedDeliveryDate());
        self::setProducts($payload->getProducts(), $entity);

        return $entity;
    }

    /**
     * @param array<int, \App\Request\Payload\Order\ProductPayload> $products
     */
    private static function setProducts(array $products, Order $order): void
    {
        foreach ($products as $row) {
            $order->addProduct($product = new Order\Product());

            $product->setName($row->getName());
            $product->setQuantity($row->getQuantity());
            $product->setPrice($row->getPrice());
        }
    }

    /**
     * @throws EntityNotFoundException
     * @throws ValidatorException
     * @throws \Exception
     */
    public function update(UpdateOrderPayload $payload): void
    {
        $entity = $this->tryGetEntity($payload);

        if (!$entity instanceof Order) {
            throw new EntityNotFoundException();
        }

        $entity->setExpectedDeliveryDate($payload->getExpectedDeliveryDate());

        $errors = $this->validator->validate($entity);

        if ($errors->count()) {
            throw ValidatorException::create($errors);
        }

        $this->em->flush();
    }

    private function tryGetEntity(AbstractOrderPayload $payload): ?Order
    {
        return $this->repository->findOneBy([
            'partnerId' => $payload->getPartnerId(),
            'orderId' => $payload->getOrderId(),
        ]);
    }
}
