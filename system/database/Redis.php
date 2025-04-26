<?php

namespace System\Database;

use Redis as RedisClient;

class Redis
{
    /**
     * @var RedisClient
     */
    private static $connection;

    /**
     * Initialize the Redis connection.
     *
     * @return RedisClient|null
     */
    public static function connect()
    {
        if (self::$connection) {
            return self::$connection;
        }

        // Load config
        $config = require __DIR__ . '/../../config/database.php';
        $redisConfig = $config['cache']['redis'];

        try {
            $redis = new RedisClient();
            $redis->connect($redisConfig['host'], $redisConfig['port'], $redisConfig['timeout']);

            if (isset($redisConfig['database'])) {
                $redis->select($redisConfig['database']);
            }

            self::$connection = $redis;
            return self::$connection;
        } catch (\Exception $e) {
            die('Redis connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Get the current Redis connection.
     *
     * @return RedisClient|null
     */
    public static function get()
    {
        return self::$connection ?? self::connect();
    }
}
