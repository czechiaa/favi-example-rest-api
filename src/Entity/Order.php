<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Order\Product;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table('`order`')]
#[ORM\UniqueConstraint(columns: ['partner_id', 'order_id'])]
#[ORM\Index(name: 'partnerId', columns: ['partner_id'])]
#[ORM\Index(name: 'orderId', columns: ['order_id'])]
#[UniqueEntity(
    fields: ['partnerId', 'orderId'],
    message: 'The order for this client already exists',
    errorPath: 'orderId'
)]
class Order
{
    use IdTrait;
    use PriceTrait;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank(allowNull: null)]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'Partner ID must be at least {{ limit }} characters long',
        maxMessage: 'Partner ID cannot be longer than {{ limit }} characters',
    )]
    private string $partnerId;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank(allowNull: false)]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'Order ID must be at least {{ limit }} characters long',
        maxMessage: 'Order ID cannot be longer than {{ limit }} characters',
    )]
    private string $orderId;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $expectedDeliveryDate;

    #[ORM\OneToMany(
        targetEntity: Product::class,
        mappedBy: 'order',
        cascade: ['persist', 'remove'],
        fetch: 'EXTRA_LAZY',
        orphanRemoval: true
    )]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getPartnerId(): string
    {
        return $this->partnerId;
    }

    public function setPartnerId(string $partnerId): void
    {
        $this->partnerId = $partnerId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getExpectedDeliveryDate(): \DateTimeImmutable
    {
        return $this->expectedDeliveryDate;
    }

    public function setExpectedDeliveryDate(\DateTimeImmutable $expectedDeliveryDate): void
    {
        $this->expectedDeliveryDate = $expectedDeliveryDate;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): void
    {
        $product->setOrder($this);
        $this->products->add($product);
    }

    public function setProducts(Collection $products): void
    {
        $this->products = $products;
    }
}
