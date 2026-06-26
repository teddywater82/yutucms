<?php
/**
 * YUTUCMS 支付下单页面
 * 用户支付后，支付平台异步通知 -> notify.php -> 写入支付状态到JSON
 * 前端通过 AJAX 轮询检测支付状态
 */
header('Content-Type:text/html;charset=utf-8');
date_default_timezone_set('PRC');

// 加载支付配置
$paymentConfig = json_decode(file_get_contents(__DIR__ . '/../JCSQL/Admin/Basic/AdminPayment.php'), true);

// 接收参数
$video_id = isset($_GET['video_id']) ? trim($_GET['video_id']) : '';
$video_name = isset($_GET['video_name']) ? trim($_GET['video_name']) : '视频付费';
$video_page = isset($_GET['video_page']) ? trim($_GET['video_page']) : '';
$price = isset($paymentConfig['payment_price']) ? $paymentConfig['payment_price'] : '9.90';
$merchant_type = isset($paymentConfig['merchant_type']) ? $paymentConfig['merchant_type'] : 'epay';

// 生成唯一订单号
$order_id = date('YmdHis') . rand(1000, 9999) . rand(100, 999);

// 存储订单信息到文件
$order_dir = __DIR__ . '/../JCSQL/Payment';
if (!is_dir($order_dir)) {
    mkdir($order_dir, 0755, true);
}

// 检测是否有返回的订单参数（支付成功后跳转回来）
$pay_result = isset($_GET['trade_no']) ? $_GET['trade_no'] : '';
$pay_order_id = isset($_GET['out_trade_no']) ? $_GET['out_trade_no'] : '';

if ($pay_order_id) {
    // 支付平台回调回来的参数，验证订单状态
    $order_file = $order_dir . '/' . $pay_order_id . '.json';
    if (file_exists($order_file)) {
        $order_data = json_decode(file_get_contents($order_file), true);
        if ($order_data && $order_data['status'] == 'paid') {
            // 支付成功，跳转到视频页面
            $return_video_id = isset($order_data['video_id']) ? $order_data['video_id'] : '';
            $return_video_page = isset($order_data['video_page']) ? $order_data['video_page'] : '';
            if ($return_video_id && $return_video_page) {
                header("Location: /?m=video_conter-id-{$return_video_id}-page-{$return_video_page}&paid=1&trade_no=" . urlencode($pay_result));
                exit;
            } else {
                header("Location: /");
                exit;
            }
        }
    }
}

// 表单提交 - 发起支付
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_pay'])) {
    $video_id = isset($_POST['video_id']) ? trim($_POST['video_id']) : $video_id;
    $video_name = isset($_POST['video_name']) ? trim($_POST['video_name']) : $video_name;
    $video_page = isset($_POST['video_page']) ? trim($_POST['video_page']) : $video_page;
    $price = isset($_POST['price']) ? trim($_POST['price']) : $price;

    // 存储订单
    $order_data = array(
        'order_id' => $order_id,
        'video_id' => $video_id,
        'video_name' => $video_name,
        'video_page' => $video_page,
        'price' => $price,
        'status' => 'pending',
        'create_time' => date('Y-m-d H:i:s'),
        'trade_no' => '',
        'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
    );
    file_put_contents($order_dir . '/' . $order_id . '.json', json_encode($order_data));

    // 构建支付请求（易支付标准接口）
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    $domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'yutucms.com';
    $notify_url = isset($paymentConfig['notify_url']) && $paymentConfig['notify_url'] ? $paymentConfig['notify_url'] : $http_type . $domain . '/Php/Payment/notify.php';
    $return_url = isset($paymentConfig['return_url']) && $paymentConfig['return_url'] ? $paymentConfig['return_url'] : $http_type . $domain . '/Php/Payment/return.php?out_trade_no=' . $order_id;

    if ($merchant_type == 'epay') {
        // 易支付标准参数
        $api_url = isset($paymentConfig['merchant_api_url']) ? $paymentConfig['merchant_api_url'] : '';
        $merchant_id = isset($paymentConfig['merchant_id']) ? $paymentConfig['merchant_id'] : '';
        $merchant_key = isset($paymentConfig['merchant_key']) ? $paymentConfig['merchant_key'] : '';

        if (!$api_url || !$merchant_id || !$merchant_key) {
            echo '<div class="alert alert-danger">支付接口配置不完整，请先到后台配置支付参数</div>';
            exit;
        }

        $param = array(
            'pid' => $merchant_id,
            'type' => 'alipay', // 默认支付宝，支持 alipay/qqpay/wxpay
            'out_trade_no' => $order_id,
            'notify_url' => $notify_url,
            'return_url' => $return_url,
            'name' => $video_name,
            'money' => $price,
            'sign_type' => 'MD5',
        );

        // 计算签名
        $param['sign'] = epay_md5_sign($param, $merchant_key);
        $param['sign_type'] = 'MD5';

        // 构建支付请求URL
        $pay_url = $api_url . '?' . http_build_query($param);

        // 跳转到支付页面
        header("Location: " . $pay_url);
        exit;
    } else {
        echo '<div class="alert alert-danger">暂不支持该支付类型</div>';
        exit;
    }
}

/**
 * 易支付MD5签名函数
 */
function epay_md5_sign($param, $key) {
    // 排序
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

/**
 * 验证易支付签名
 */
function epay_md5_verify($param, $key) {
    $sign = isset($param['sign']) ? $param['sign'] : '';
    $my_sign = epay_md5_sign($param, $key);
    return $sign === $my_sign;
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>视频付费 - YUTUCMS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .pay-container { background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); width: 100%; max-width: 420px; overflow: hidden; animation: slideUp 0.5s ease; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .pay-header { background: linear-gradient(135deg, #667eea, #764ba2); padding: 30px; text-align: center; color: white; }
        .pay-header .icon { font-size: 48px; margin-bottom: 10px; }
        .pay-header h2 { font-size: 20px; font-weight: 600; }
        .pay-header p { font-size: 14px; opacity: 0.9; margin-top: 5px; }
        .pay-body { padding: 30px; }
        .video-info { background: #f8f9fa; border-radius: 10px; padding: 15px; margin-bottom: 20px; }
        .video-info .label { font-size: 12px; color: #999; }
        .video-info .value { font-size: 15px; color: #333; font-weight: 500; margin-top: 3px; }
        .price-display { text-align: center; padding: 20px 0; }
        .price-display .amount { font-size: 42px; font-weight: 700; color: #667eea; }
        .price-display .amount .symbol { font-size: 24px; }
        .price-display .desc { font-size: 14px; color: #999; margin-top: 5px; }
        .pay-methods { display: flex; gap: 10px; margin: 20px 0; }
        .pay-method { flex: 1; text-align: center; padding: 15px 10px; border: 2px solid #eee; border-radius: 10px; cursor: pointer; transition: all 0.3s; }
        .pay-method:hover { border-color: #667eea; }
        .pay-method.active { border-color: #667eea; background: #f0f0ff; }
        .pay-method .method-icon { font-size: 28px; }
        .pay-method .method-name { font-size: 13px; color: #666; margin-top: 5px; }
        .btn-pay { width: 100%; padding: 16px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 10px; font-size: 18px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-pay:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(102,126,234,0.4); }
        .pay-footer { text-align: center; padding: 20px 30px; border-top: 1px solid #eee; font-size: 12px; color: #999; }
        .alert-danger { background: #fff5f5; border: 1px solid #ffcdd2; color: #c62828; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
        input[type="radio"] { display: none; }
    </style>
</head>
<body>
    <div class="pay-container">
        <div class="pay-header">
            <div class="icon">🔒</div>
            <h2>安全支付</h2>
            <p>支付完成后视频将自动继续播放</p>
        </div>
        <div class="pay-body">
            <div class="video-info">
                <div class="label">🎬 付费内容</div>
                <div class="value"><?php echo htmlspecialchars($video_name); ?></div>
            </div>

            <div class="price-display">
                <div class="amount"><span class="symbol">¥</span> <?php echo htmlspecialchars($price); ?></div>
                <div class="desc"><?php echo isset($paymentConfig['payment_description']) ? htmlspecialchars($paymentConfig['payment_description']) : '付费观看完整视频'; ?></div>
            </div>

            <form method="post" id="payForm">
                <input type="hidden" name="video_id" value="<?php echo htmlspecialchars($video_id); ?>">
                <input type="hidden" name="video_name" value="<?php echo htmlspecialchars($video_name); ?>">
                <input type="hidden" name="video_page" value="<?php echo htmlspecialchars($video_page); ?>">
                <input type="hidden" name="price" value="<?php echo htmlspecialchars($price); ?>">

                <div class="pay-methods">
                    <label class="pay-method active">
                        <input type="radio" name="pay_type" value="alipay" checked>
                        <div class="method-icon">💳</div>
                        <div class="method-name">支付宝</div>
                    </label>
                    <label class="pay-method">
                        <input type="radio" name="pay_type" value="wxpay">
                        <div class="method-icon">💚</div>
                        <div class="method-name">微信支付</div>
                    </label>
                    <label class="pay-method">
                        <input type="radio" name="pay_type" value="qqpay">
                        <div class="method-icon">💙</div>
                        <div class="method-name">QQ钱包</div>
                    </label>
                </div>

                <button type="submit" name="submit_pay" class="btn-pay">
                    确认支付 ¥<?php echo htmlspecialchars($price); ?>
                </button>
            </form>
        </div>
        <div class="pay-footer">
            <p>🛡️ 支付信息由第三方支付平台加密处理</p>
        </div>
    </div>

    <script>
    document.querySelectorAll('.pay-method').forEach(function(method) {
        method.addEventListener('click', function() {
            document.querySelectorAll('.pay-method').forEach(function(m) { m.classList.remove('active'); });
            this.classList.add('active');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });
    </script>
</body>
</html>