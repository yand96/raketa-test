<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Presentation\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\Service\Cart\GetProductsService;
use Raketa\BackendTestTask\Presentation\View\ProductsView;

readonly class GetProductsController extends AbstractController
{
    public function __construct(
        private GetProductsService $getProductsService,
        private ProductsView $productsVew
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        //из условия задачи считаем, что параметры есть и верные
        $rawRequest = json_decode($request->getBody()->getContents(), true);

        return $this->send(
            $this->productsVew->toArray(
                $this->getProductsService->getProductsByCategory($rawRequest['category'])
            )
        );
    }
}
