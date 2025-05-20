<?php

namespace System\Database;

class RedisClient
{
    /**
     * @var \Redis
     */
    private static $instance;

    /**
     * Prevent direct instantiation
     */
    private function __construct() {}

    /**
     * Get the singleton Redis instance
     *
     * @return \Redis
     * @throws \RuntimeException
     */
    public static function get(): \Redis
    {
        if (!self::$instance) {
            $config = require __DIR__ . '/../../config/database.php';
            $redisConfig = $config['cache']['redis'];

            try {
                $redis = new \Redis();
                $redis->connect(
                    $redisConfig['host'],
                    (int) $redisConfig['port'],
                    (float) $redisConfig['timeout'] ?? 2.5
                );

                if (isset($redisConfig['database'])) {
                    $redis->select((int) $redisConfig['database']);
                }

                self::$instance = $redis;
            } catch (\RedisException $e) {
                throw new \RuntimeException('Redis connection failed: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
