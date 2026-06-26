<?php
/**
 * YUTUCMS 支付状态检查接口
 * 用于前端AJAX轮询，检测指定视频或订单是否已支付
 * 返回JSON格式的支付状态
 */
header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('PRC');

// 允许跨域（因为播放器在iframe中）
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

$response = array('paid' => false, 'error' => '');

// 支持通过video_id查询最近是否有成功支付
$video_id = isset($_GET['video_id']) ? trim($_GET['video_id']) : '';
$out_trade_no = isset($_GET['out_trade_no']) ? trim($_GET['out_trade_no']) : '';

$order_dir = __DIR__ . '/../JCSQL/Payment';

if ($out_trade_no) {
    // 根据订单号查询
    $order_file = $order_dir . '/' . basename($out_trade_no) . '.json';
    if (file_exists($order_file)) {
        $order_data = json_decode(file_get_contents($order_file), true);
        if ($order_data && isset($order_data['status']) && $order_data['status'] == 'paid') {
            $response['paid'] = true;
            $response['order_id'] = $out_trade_no;
            $response['trade_no'] = isset($order_data['trade_no']) ? $order_data['trade_no'] : '';
            echo json_encode($response);
            exit;
        }
        
        // 检查是否有.paid标记文件
        $paid_flag = $order_dir . '/' . basename($out_trade_no) . '.paid';
        if (file_exists($paid_flag)) {
            $response['paid'] = true;
            $response['order_id'] = $out_trade_no;
            echo json_encode($response);
            exit;
        }
    }
} elseif ($video_id) {
    // 根据video_id查询是否在最近有支付记录
    if (is_dir($order_dir)) {
        $files = glob($order_dir . '/*.json');
        $recent_paid = false;
        
        // 只检查最近10分钟内的订单
        $check_time = time() - 600;
        
        foreach ($files as $file) {
            $data = json_decode(file_get_contents($file), true);
            if ($data && isset($data['video_id']) && $data['video_id'] == $video_id) {
                if (isset($data['status']) && $data['status'] == 'paid') {
                    // 检查时间
                    $create_time = isset($data['create_time']) ? strtotime($data['create_time']) : 0;
                    if ($create_time > $check_time) {
                        $response['paid'] = true;
                        $response['order_id'] = $data['order_id'];
                        $response['trade_no'] = isset($data['trade_no']) ? $data['trade_no'] : '';
                        break;
                    }
                }
            }
        }
    }
}

// 记录检查日志（用于调试）
$log = date('Y-m-d H:i:s') . " - check_paid: video_id={$video_id}, out_trade_no={$out_trade_no}, result=" . ($response['paid'] ? 'paid' : 'unpaid') . "\n";
$log_dir = __DIR__ . '/../JCSQL/Payment/logs';
if (is_dir($log_dir)) {
    file_put_contents($log_dir . '/check_' . date('Ymd') . '.log', $log, FILE_APPEND);
}

echo json_encode($response);
