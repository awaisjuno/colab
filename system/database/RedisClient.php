<?php

namespace System\Database;

class RedisClient
{
    /**
     * @var \Redis
     */
    private $redis;

    /**
     * RedisClient constructor.
     *
     * @param array $config
     * @throws \RuntimeException
     */
    public function __construct(array $config)
    {
        try {
            $this->redis = new \Redis();  // PHP's Redis extension class
            $this->redis->connect(
                $config['host'],
                $config['port'],
                $config['timeout'] ?? 2.5
            );

            if (isset($config['database'])) {
                $this->redis->select($config['database']);
            }
        } catch (\RedisException $e) {
            throw new \RuntimeException('Redis connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Get the Redis client.
     *
     * @return \Redis
     */
    public function getClient(): \Redis
    {
        return $this->redis;
    }
}
