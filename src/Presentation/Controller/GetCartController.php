<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Presentation\Controller;

use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\Service\Cart\GetCartService;
use Raketa\BackendTestTask\Presentation\View\CartView;
use Throwable;

readonly class GetCartController extends AbstractController
{
    public function __construct(
        public CartView $cartView,
        private GetCartService $getCartService,
    ) {
    }

    public function get(): ResponseInterface
    {
        try {
            $cart = $this->getCartService->getCart();
        } catch (Throwable $exception) {
            return $this->sendErrorResponse();
        }

        return $this->send(
            $this->cartView->toArray($cart)
        );
    }
}
