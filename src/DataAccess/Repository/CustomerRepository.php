<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\DataAccess\Repository;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Domain\Model\Customer;
use Exception;

class CustomerRepository
{

    public function __construct(
        private Connection $connection
    ) {
    }

    public function getById(int $id): Customer
    {
        $row = $this->connection->fetchOne(
            "SELECT * FROM customers WHERE id = :id",
            [
                'id' => $id,
            ]
        );

        if (empty($row)) {
            throw new Exception('Customer not found with id ' . $id);
        }

        return $this->make($row);
    }

    public function make(array $row): Customer
    {
        return new Customer(
            $row['id'],
            $row['firstName'] ?? '',
            $row['lastName'] ?? '',
            $row['middleName'] ?? '',
            $row['email'] ?? '',
        );
    }
}
