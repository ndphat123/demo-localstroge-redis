<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../configs/redis.php';
ini_set('display_errors', 0);
error_reporting(E_ALL);

if (!isset($_GET["key"])) {
    http_response_code(400);
    echo json_encode(["error" => "Thiếu key"]);
    exit;
}

$key = $_GET["key"];
$redis = getRedis();

// Lấy dữ liệu
$value = $redis->get($key);

if ($value === false) {
    echo json_encode(["error" => "Không tìm thấy dữ liệu với key: $key"]);
} else {
    echo json_encode([
        "success" => true,
        "key" => $key,
        "value" => $value
    ]);
}
