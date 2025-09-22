<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../configs/redis.php';

// Bật log lỗi để debug (chỉ nên dùng khi dev)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data["key"]) || !isset($data["value"])) {
    http_response_code(400);
    echo json_encode(["error" => "❌ Dữ liệu không hợp lệ"]);
    exit;
}

try {
    $redis = getRedis();  // gọi hàm kết nối trong configs/redis.php
    
    // Thử lưu
    if ($redis->set($data["key"], $data["value"])) {
        echo json_encode([
            "success" => true,
            "message" => "✅ Đã lưu vào Redis",
            "key" => $data["key"],
            "value" => $data["value"]
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "error" => "❌ Không thể lưu dữ liệu vào Redis"
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "❌ Lỗi Redis: " . $e->getMessage()
    ]);
}
