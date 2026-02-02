<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Presentation\View;

use Raketa\BackendTestTask\Domain\Model\Product;

readonly class ProductsView
{
    public function __construct(
    ) {
    }

    /**
     * @param Product[] $products
     * @return array
     */
    public function toArray(array $products): array
    {
        return array_map(
            fn (Product $product) => [
                'id' => $product->getId(),
                'uuid' => $product->getUuid(),
                'category' => $product->getCategory(),
                'description' => $product->getDescription(),
                'thumbnail' => $product->getThumbnail(),
                'price' => $product->getPrice(),
            ],
            $products
        );
    }
}
