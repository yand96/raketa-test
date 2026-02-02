<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\DataAccess\Repository;

use Doctrine\DBAL\Connection;
use Exception;
use Raketa\BackendTestTask\Domain\Model\Product;

class ProductRepository
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function getByUuid(string $uuid): Product
    {
        $row = $this->connection->fetchOne(
            "SELECT * FROM products WHERE uuid = :uuid",
            [
                'uuid' => $uuid
            ]
        );

        if (empty($row)) {
            throw new Exception('Product not found with uuid ' . $uuid);
        }

        return $this->make($row);
    }

    /**
     * @param string[] $uuids
     * @return Product[]
     * @throws \Doctrine\DBAL\Exception
     */
    public function getByUuids(array $uuids): array
    {
        $rows = $this->connection->fetchAllAssociative(
            "SELECT * FROM products WHERE uuid in (:uuids)",
            ['uuids' => $uuids],
            ['uuids' => Connection::PARAM_STR_ARRAY],
        );

        return array_map(
            static fn (array $row): Product => $this->make($row),
            $rows
        );
    }

    /**
     * @param string $category
     * @return Product[]
     * @throws \Doctrine\DBAL\Exception
     */
    public function getByCategory(string $category): array
    {
        return array_map(
            static fn (array $row): Product => $this->make($row),
            $this->connection->fetchAllAssociative(
                "SELECT * FROM products WHERE is_active = 1 AND category = :category",
                [
                    'category' => $category
                ]
            )
        );
    }

    public function make(array $row): Product
    {
        return new Product(
            $row['id'],
            $row['uuid'],
            $row['is_active'],
            $row['category'],
            $row['name'],
            $row['description'] ?? '',
            $row['thumbnail'] ?? '',
            $row['price'],
        );
    }
}
