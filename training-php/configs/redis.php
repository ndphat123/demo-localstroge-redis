<?php
function getRedis() {
    if (!class_exists('Redis')) {
        throw new Exception('The Redis extension is not installed or enabled.');
    }

    $redis = new Redis();

    try {
        // Kết nối Redis
        $redis->connect('127.0.0.1', 6379);

        // Test ping
        $pong = $redis->ping();
        if ($pong !== true && $pong !== '+PONG' && $pong !== 'PONG') {
            throw new Exception('Could not connect to Redis server. Ping failed: ' . $pong);
        }
    } catch (Exception $e) {
        throw new Exception('Redis connection failed: ' . $e->getMessage());
    }

    return $redis;
}
