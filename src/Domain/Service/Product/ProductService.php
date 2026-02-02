<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain\Service\Product;

use Raketa\BackendTestTask\DataAccess\Repository\ProductRepository;
use Raketa\BackendTestTask\Domain\Model\Product;

readonly class ProductService
{
    public function __construct(
        private ProductRepository $repository
    ) {
    }

    /**
     * @param string[] $uuids
     * @return Product[]
     */
    public function getProductsByUuids(array $uuids): array
    {
        return $this->repository->getByUuids($uuids);
    }
}