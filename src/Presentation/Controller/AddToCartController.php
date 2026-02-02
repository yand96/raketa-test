<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Presentation\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\Service\Cart\AddToCartService;
use Raketa\BackendTestTask\Presentation\View\CartView;
use Throwable;

final readonly class AddToCartController extends AbstractController
{
    public function __construct(
        private AddToCartService $addToCartService,
        private CartView $cartView,
    ) {
    }

    public function add(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        //из условия задачи считаем, что параметры есть и верные
        try {
            $cart = $this->addToCartService->addToCart($rawRequest['productUuid'], $rawRequest['quantity']);
        } catch (Throwable $exception) {
            return $this->sendErrorResponse();
        }

        return $this->send(
            [
                'status' => 'success',
                'cart' => $this->cartView->toArray($cart)
            ]
        );
    }
}
