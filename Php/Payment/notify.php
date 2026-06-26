<?php
/**
 * YUTUCMS 支付异步通知处理
 * 四方支付平台的异步通知接收地址
 * 支付成功后更新订单状态，并写入支付标记文件
 */
header('Content-Type:text/html;charset=utf-8');
date_default_timezone_set('PRC');

// 加载支付配置
$paymentConfig = json_decode(file_get_contents(__DIR__ . '/../JCSQL/Admin/Basic/AdminPayment.php'), true);
$merchant_key = isset($paymentConfig['merchant_key']) ? $paymentConfig['merchant_key'] : '';
$merchant_type = isset($paymentConfig['merchant_type']) ? $paymentConfig['merchant_type'] : 'epay';

// 日志
$log_dir = __DIR__ . '/../JCSQL/Payment/logs';
if (!is_dir($log_dir)) {
    mkdir($log_dir, 0755, true);
}

// 易支付签名验证函数
function epay_md5_sign($param, $key) {
    ksort($param);
    reset($param);
    $sign_str = '';
    foreach ($param as $k => $v) {
        if ($k != 'sign' && $k != 'sign_type' && $v !== '') {
            $sign_str .= $k . '=' . $v . '&';
        }
    }
    $sign_str = trim($sign_str, '&');
    $sign_str .= $key;
    return md5($sign_str);
}

// 记录原始通知数据
$notify_data = $_POST ? json_encode($_POST) : json_encode($_GET);
file_put_contents($log_dir . '/notify_' . date('Ymd') . '.log', date('Y-m-d H:i:s') . ' - ' . $notify_data . "\n", FILE_APPEND);

if ($merchant_type == 'epay') {
    // 易支付异步通知处理
    $params = $_POST ? $_POST : $_GET;
    
    if (empty($params)) {
        file_put_contents($log_dir . '/error.log', date('Y-m-d H:i:s') . ' - 无通知参数' . "\n", FILE_APPEND);
        exit('fail');
    }

    // 验证签名
    $calc_sign = epay_md5_sign($params, $merchant_key);
    $receive_sign = isset($params['sign']) ? $params['sign'] : '';
    
    if ($calc_sign !== $receive_sign) {
        file_put_contents($log_dir . '/error.log', date('Y-m-d H:i:s') . ' - 签名验证失败: calc=' . $calc_sign . ' receive=' . $receive_sign . "\n", FILE_APPEND);
        exit('fail');
    }

    // 检查支付状态
    $trade_status = isset($params['trade_status']) ? $params['trade_status'] : '';
    $out_trade_no = isset($params['out_trade_no']) ? $params['out_trade_no'] : '';
    $trade_no = isset($params['trade_no']) ? $params['trade_no'] : '';

    if ($trade_status == 'TRADE_SUCCESS' || $trade_status == 'success') {
        // 更新订单状态
        $order_dir = __DIR__ . '/../JCSQL/Payment';
        $order_file = $order_dir . '/' . $out_trade_no . '.json';
        
        if (file_exists($order_file)) {
            $order_data = json_decode(file_get_contents($order_file), true);
            if ($order_data && $order_data['status'] != 'paid') {
                $order_data['status'] = 'paid';
                $order_data['trade_no'] = $trade_no;
                $order_data['pay_time'] = date('Y-m-d H:i:s');
                file_put_contents($order_file, json_encode($order_data));
                
                // 写入支付成功标记文件（供前端轮询检测）
                file_put_contents($order_dir . '/' . $out_trade_no . '.paid', date('Y-m-d H:i:s'));
                
                file_put_contents($log_dir . '/success.log', date('Y-m-d H:i:s') . ' - 订单' . $out_trade_no . '支付成功' . "\n", FILE_APPEND);
            }
        } else {
            // 订单文件不存在，创建它
            $order_data = array(
                'order_id' => $out_trade_no,
                'status' => 'paid',
                'trade_no' => $trade_no,
                'pay_time' => date('Y-m-d H:i:s'),
                'create_time' => date('Y-m-d H:i:s'),
            );
            file_put_contents($order_file, json_encode($order_data));
            file_put_contents($order_dir . '/' . $out_trade_no . '.paid', date('Y-m-d H:i:s'));
        }
        
        // 通知支付平台处理成功
        exit('success');
    }
} else {
    // 其他支付接口处理
    file_put_contents($log_dir . '/error.log', date('Y-m-d H:i:s') . ' - 不支持的支付类型: ' . $merchant_type . "\n", FILE_APPEND);
    exit('fail');
}

exit('fail');
