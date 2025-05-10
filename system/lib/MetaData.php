<?php

namespace System\Lib;

use System\Database\RedisClient;
use System\Model;

class MetaData
{
    protected $model;
    protected $redis;

    public function __construct()
    {
        $this->model = new Model();
        $this->redis = \System\Database\RedisClient::get();
    }

    public function get(string $route): array
    {
        $cacheKey = 'meta:' . md5($route);

        // Try Redis cache
        $cached = $this->redis->get($cacheKey);
        if ($cached) {
            return json_decode($cached, true);
        }

        // Fallback to DB
        $result = $this->model->table('meta_data')->where('route', $route)->first();

        $data = [
            'title'       => $result['title'] ?? '',
            'description' => $result['description'] ?? '',
            'keywords'    => $result['keyword'] ?? ''
        ];

        // Save to Redis
        $this->redis->setex($cacheKey, 3600, json_encode($data));

        return $data;
    }
}
