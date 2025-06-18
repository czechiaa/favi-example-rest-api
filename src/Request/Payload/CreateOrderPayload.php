<?php

declare(strict_types=1);

namespace App\Request\Payload;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateOrderPayload extends AbstractOrderPayload
{
    /**
     * @param array<int, Order\ProductPayload> $products
     */
    public function __construct(
        string $partnerId,
        string $orderId,
        string $expectedDeliveryDate,
        #[Assert\Type(
            type: 'float',
            message: 'Price must be a numeric value'
        )]
        private float $price = 0.0,
        #[Assert\Count(min: 1)]
        private array $products = [],
    ) {
        parent::__construct($partnerId, $orderId, $expectedDeliveryDate);
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return array<int, Order\ProductPayload>
     */
    public function getProducts(): array
    {
        return $this->products;
    }
}
