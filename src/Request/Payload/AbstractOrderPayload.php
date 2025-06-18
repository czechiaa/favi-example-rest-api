<?php

declare(strict_types=1);

namespace App\Request\Payload;

use Symfony\Component\Validator\Constraints as Assert;

abstract readonly class AbstractOrderPayload
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        private string $partnerId,
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        private string $orderId,
        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        private string $expectedDeliveryDate,
    ) {
    }

    public function getPartnerId(): string
    {
        return $this->partnerId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @throws \Exception
     */
    public function getExpectedDeliveryDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->expectedDeliveryDate);
    }
}
