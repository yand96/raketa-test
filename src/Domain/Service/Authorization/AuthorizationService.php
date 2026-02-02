<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain\Service\Authorization;

class AuthorizationService
{
    public function getAuthCustomerId(): int
    {
        // считаем что сервис реализован ранее, поэтому тут просто мок
        return 1;
    }
}