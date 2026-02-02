<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain\Service\Cart;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\DataAccess\Infrastructure\RedisConnector;
use Raketa\BackendTestTask\DataAccess\Repository\CustomerRepository;
use Raketa\BackendTestTask\Domain\Model\Cart;
use Raketa\BackendTestTask\Domain\Model\Enum\PaymentMethod;
use Raketa\BackendTestTask\Domain\Service\Authorization\AuthorizationService;

class GetCartService
{
    public function __construct(
        protected AuthorizationService $authorizationService,
        protected RedisConnector $connector,
        protected CustomerRepository $customerRepository,
        protected LoggerInterface $logger,
    ) {
    }

    public function getCart(): Cart
    {
        try {
            $customerId = $this->authorizationService->getAuthCustomerId();

            if ($this->connector->hasCart($customerId)) {
                return $this->connector->getCart($customerId);
            }

            $cart = new Cart(
                $this->customerRepository->getById($customerId),
                PaymentMethod::ONLINE->value,
                [],
                0
            );

            $this->connector->setCart($cart);

            // если позволяет производительность, лучше возвращать так, чтобы всегда было актуальное состояние из
            // внешнего сервиса.
            return $this->connector->getCart($customerId);
        } catch (\Throwable $exception) {
            $this->logger->error('Error when get cart. Error: '.$exception->getMessage(), ['exception' => $exception]);
            throw $exception;
        }
    }
}