<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\IdTrait;
use App\Entity\Order;
use App\Entity\PriceTrait;
use App\Repository\Order\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table('order_product')]
#[ORM\Index(fields: ['name'], flags: ['fulltext'])]
class Product
{
    use IdTrait;
    use PriceTrait;

    #[ORM\ManyToOne(targetEntity: Order::class, fetch: 'EXTRA_LAZY', inversedBy: 'products')]
    private Order $order;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'Name must be at least {{ limit }} characters long',
        maxMessage: 'Name cannot be longer than {{ limit }} characters',
    )]
    private string $name;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\GreaterThan(value: 0)]
    private int $quantity;

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
