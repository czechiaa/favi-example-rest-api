<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function json_encode;

class ApiControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    private function getUri(): string
    {
        return $this->getContainer()->get('router')->generate('order_create');
    }

    public function testInvalidRoute(): void
    {
        $this->client->request(Request::METHOD_GET, 'any_random_route');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testStoreEmptyOrder(): void
    {
        $content = [];

        $this->client->request(
            Request::METHOD_POST,
            $this->getUri(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($content)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdateEmptyOrder(): void
    {
        $content = [];

        $this->client->request(
            Request::METHOD_PUT,
            $this->getUri(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($content)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testStoreValidOrder(): void
    {
        $content = [
            'orderId' => 'random001',
            'partnerId' => 'partner001',
            'expectedDeliveryDate' => '2025-06-01 12:34:56',
            'price' => 12.34,
            'products' => [
                ['id' => 123, 'name' => 'product123', 'price' => 123.45, 'quantity' => 1],
                ['id' => 456, 'name' => 'product456', 'price' => 678.90, 'quantity' => 2]
            ]
        ];

        $this->client->request(
            Request::METHOD_POST,
            $this->getUri(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($content)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testUpdateValidOrder(): void
    {
        $content = [
            'orderId' => 'random001',
            'partnerId' => 'partner002',
            'expectedDeliveryDate' => '2025-06-02 12:34:56',
            'price' => 23.45,
            'products' => [
                ['id' => 789, 'name' => 'product123', 'price' => 22.36, 'quantity' => 2]
            ]
        ];

        $this->client->request(
            Request::METHOD_POST,
            $this->getUri(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($content)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $content = [
            'orderId' => 'random001',
            'partnerId' => 'partner002',
            'expectedDeliveryDate' => '2025-06-03 12:34:56'
        ];

        $this->client->request(
            Request::METHOD_PUT,
            $this->getUri(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($content)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testStoreInvalidPriceOfOrder(): void
    {
        $content = [
            'orderId' => 'random001',
            'partnerId' => 'partner003',
            'expectedDeliveryDate' => '2025-06-14 12:34:56',
            'price' => 'abc',
            'products' => [
                ['id' => 123, 'name' => 'product123', 'price' => 123.45, 'quantity' => 1],
                ['id' => 456, 'name' => 'product456', 'price' => 678.90, 'quantity' => 2]
            ]
        ];

        $this->client->request(
            Request::METHOD_POST,
            $this->getUri(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($content)
        );

        $this->assertResponseIsUnprocessable();
    }

    public function testStoreInvalidPartnerIdOrder(): void
    {
        $content = [
            'orderId' => 'random001',
            'partnerId' => null,
            'expectedDeliveryDate' => '2025-06-14 12:34:56',
            'price' => 'abc',
            'products' => [
                ['id' => 123, 'name' => 'product123', 'price' => 123.45, 'quantity' => 1],
                ['id' => 456, 'name' => 'product456', 'price' => 678.90, 'quantity' => 2]
            ]
        ];

        $this->client->request(
            Request::METHOD_POST,
            $this->getUri(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($content)
        );

        $this->assertResponseIsUnprocessable();
    }

    public function testStoreInvalidOrderIdOrder(): void
    {
        $content = [
            'orderId' => null,
            'partnerId' => 'partner005',
            'expectedDeliveryDate' => '2025-06-14 12:34:56',
            'price' => 'abc',
            'products' => [
                ['id' => 123, 'name' => 'product123', 'price' => 123.45, 'quantity' => 1],
                ['id' => 456, 'name' => 'product456', 'price' => 678.90, 'quantity' => 2]
            ]
        ];

        $this->client->request(
            Request::METHOD_POST,
            $this->getUri(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($content)
        );

        $this->assertResponseIsUnprocessable();
    }

    public function testStoreOrderWithNoProducts(): void
    {
        $content = [
            'orderId' => 'order001',
            'partnerId' => 'partner004',
            'expectedDeliveryDate' => '2025-06-14 12:34:56',
            'price' => 7.09,
            'products' => []
        ];

        $this->client->request(
            Request::METHOD_POST,
            $this->getUri(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($content)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
