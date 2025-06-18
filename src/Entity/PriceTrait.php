<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait PriceTrait
{
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\Type(type: 'float', message: 'Price must be a numeric value')]
    private float $price;

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}
