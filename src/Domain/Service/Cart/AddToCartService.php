<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain\Service\Cart;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\DataAccess\Infrastructure\RedisConnector;
use Raketa\BackendTestTask\DataAccess\Repository\CustomerRepository;
use Raketa\BackendTestTask\DataAccess\Repository\ProductRepository;
use Raketa\BackendTestTask\Domain\Model\Cart;
use Raketa\BackendTestTask\Domain\Model\CartItem;
use Raketa\BackendTestTask\Domain\Service\Authorization\AuthorizationService;
use Throwable;

class AddToCartService extends GetCartService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        AuthorizationService $authorizationService,
        RedisConnector $connector,
        CustomerRepository $customerRepository,
        LoggerInterface $logger,
    ) {
        parent::__construct(
            $authorizationService,
            $connector,
            $customerRepository,
            $logger,
        );
    }

    public function addToCart(string $productUuid, int $quantity): Cart
    {
        try {
            $cart = $this->getCart();
            $product = $this->productRepository->getByUuid($productUuid);

            $newCartItem = new CartItem($product->getUuid(), $product->getPrice(), $quantity);

            //добавляем новую стоимость к уже существующей
            $totalPrice = $cart->getTotalPrice();
            $totalPrice += $newCartItem->getPrice() * $quantity;
            $cart->setTotalPrice($totalPrice);

            $cart->addItem($newCartItem);

            $this->connector->setCart($cart);

            // если позволяет производительность, лучше возвращать так, чтобы всегда было актуальное состояние из
            // внешнего сервиса.
            return $this->connector->getCart($cart->getCustomer()->getId());
        } catch (Throwable $exception) {
            $this->logger->error('Error when add to cart. Exception: '. $exception->getMessage(), ['exception' => $exception]);
            throw $exception;
        }
    }
}