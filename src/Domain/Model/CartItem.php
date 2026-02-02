<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain\Model;

final readonly class CartItem
{
    public function __construct(
        public string $productUuid,
        public float $price,
        public int $quantity,
    ) {
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
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
