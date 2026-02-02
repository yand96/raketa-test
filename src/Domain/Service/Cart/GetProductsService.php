<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain\Service\Cart;

use Raketa\BackendTestTask\DataAccess\Repository\ProductRepository;
use Raketa\BackendTestTask\Domain\Model\Product;

class GetProductsService
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    /**
     * @param string $category
     * @return Product[]
     */
    public function getProductsByCategory(string $category): array
    {
        return $this->productRepository->getByCategory($category);
    }
}