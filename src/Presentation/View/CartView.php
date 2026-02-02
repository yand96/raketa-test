<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Presentation\View;

use Raketa\BackendTestTask\Domain\Model\Cart;
use Raketa\BackendTestTask\Domain\Model\Product;
use Raketa\BackendTestTask\Domain\Service\Product\ProductService;

readonly class CartView
{
    public function __construct(
        private ProductService $productService
    ) {
    }

    public function toArray(Cart $cart): array
    {
        $data = [
            'customer' => [
                'id' => $cart->getCustomer()->getId(),
                'name' => implode(' ', [
                    $cart->getCustomer()->getLastName(),
                    $cart->getCustomer()->getFirstName(),
                    $cart->getCustomer()->getMiddleName(),
                ]),
                'email' => $cart->getCustomer()->getEmail(),
            ],
            'payment_method' => $cart->getPaymentMethod(),
        ];

        $data['items'] = [];

        $uuids = array_map(
            fn (Product $product) => $product->getUuid(),
            $cart->getItems()
        );

        $products = $this->productService->getProductsByUuids($uuids);

        foreach ($cart->getItems() as $cartItem) {
            foreach ($products as $product) {
                if ($cartItem->getProductUuid() === $product->getUuid()) {
                    break;
                }
            }
            $data['items'][] = [
                'price' => $cartItem->getPrice(),
                'quantity' => $cartItem->getQuantity(),
                'product' => [
                    'id' => $product->getId(),
                    'uuid' => $product->getUuid(),
                    'name' => $product->getName(),
                    'thumbnail' => $product->getThumbnail(),
                    'price' => $product->getPrice(),
                ],
            ];
        }

        $data['total'] = $cart->getTotalPrice();

        return $data;
    }
}
