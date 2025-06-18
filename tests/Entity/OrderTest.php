<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Order;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    private static function createProduct(string $name, int $quantity, float $price): Order\Product
    {
        $product = new Order\Product();
        $product->setName($name);
        $product->setQuantity($quantity);
        $product->setPrice($price);

        return $product;
    }

    public function testCanGetAndSetData(): void
    {
        $order = new Order();
        $order->setPartnerId('partner123');
        $order->setOrderId('order123');
        $order->setPrice(1000.09);
        $order->setExpectedDeliveryDate(new \DateTimeImmutable('2025-03-01 12:34:56'));
        $order->addProduct(self::createProduct('Product 1', 1, 123.45));
        $order->addProduct(self::createProduct('Product 2', 2, 45.01));

        self::assertSame('partner123', $order->getPartnerId());
        self::assertSame('order123', $order->getOrderId());
        self::assertSame(1000.09, $order->getPrice());
        self::assertSame('2025-03-01 12:34:56', $order->getExpectedDeliveryDate()->format('Y-m-d H:i:s'));
        self::assertCount(2, $order->getProducts());
        self::assertInstanceOf(Order\Product::class, $order->getProducts()[0]);
        self::assertSame('Product 1', $order->getProducts()[0]->getName());
        self::assertSame(2, $order->getProducts()[1]->getQuantity());
        self::assertSame(45.01, $order->getProducts()[1]->getPrice());
    }
}
