<?php

declare(strict_types=1);

namespace App\Request\Payload\Order;

readonly class ProductPayload
{
    public function __construct(
        private int $id,
        private string $name,
        private float $price,
        private int $quantity,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
