<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain\Model;

final class Cart
{
    public function __construct(
        readonly private Customer $customer,
        readonly private string $paymentMethod,
        /**
         * @var CartItem[]
         */
        private array $items,
        private float $totalPrice
    ) {
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        $this->items[] = $item;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }
}
