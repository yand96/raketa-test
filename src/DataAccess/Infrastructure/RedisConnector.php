<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\DataAccess\Infrastructure;

use Raketa\BackendTestTask\Domain\Model\Cart;
use Redis;
use RedisException;

class RedisConnector
{
    private const CART_TTL = 24 * 60 * 60;

    private const CART_KEY = 'cart:';

    private static ?Redis $redis;

    /**
     * @throws ConnectorException
     */
    public function getCart(int $customerId): Cart
    {
        try {
            $value = $this->getConnection()->get($this->getCartPrefixedKey($customerId));

            return $value === false
                ? throw new ConnectorException('Empty cart customerId '. $customerId)
                : unserialize($value);
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    /**
     * @throws ConnectorException
     */
    public function setCart(Cart $value): void
    {
        try {
            $this->getConnection()->setex(
                $this->getCartPrefixedKey($value->getCustomer()->getId()),
                self::CART_TTL,
                serialize($value)
            );
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    public function hasCart(int $customerId): bool
    {
        return $this->getConnection()->exists($this->getCartPrefixedKey($customerId));
    }

    private function getCartPrefixedKey(int $customerId): string
    {
        return self::CART_KEY . $customerId;
    }

    private function getConnection(): Redis
    {
        if (!self::$redis) {
            $redis = new Redis();

            try {
                $redis->connect(
                    getenv('REDIS_HOST'),
                    getenv('REDIS_PORT'),
                );

                if ($redis->auth(getenv('REDIS_PASSWORD')) !== true) {
                    throw new ConnectorException('Authentication Redis failed');
                }

                if ($redis->select(getenv('REDIS_DB_INDEX')) !== true) {
                    throw new ConnectorException('Database Redis failed');
                }
            } catch (RedisException $e) {
                throw new ConnectorException('Connector error', $e->getCode(), $e);
            }

            self::$redis = $redis;
        }

        return self::$redis;
    }
}
