<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Presentation\Controller;

abstract readonly class AbstractController
{
    final protected function send(mixed $data, int $code = 200): JsonResponse
    {
        $response = new JsonResponse();

        $response->getBody()->write(
            json_encode(
                $data,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus($code);
    }

    final protected function sendErrorResponse(): JsonResponse
    {
        $response = new JsonResponse();

        $response->getBody()->write(
            json_encode(
                'Internal Server Error',
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(500);
    }
}
