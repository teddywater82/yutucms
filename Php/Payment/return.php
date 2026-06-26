<?php
/**
 * YUTUCMS 支付同步跳转页面
 * 用户支付完成后跳转回此页面，并自动跳转回视频页面
 */
header('Content-Type:text/html;charset=utf-8');
date_default_timezone_set('PRC');

$out_trade_no = isset($_GET['out_trade_no']) ? trim($_GET['out_trade_no']) : '';
$trade_no = isset($_GET['trade_no']) ? trim($_GET['trade_no']) : '';

// 验证订单状态
$order_dir = __DIR__ . '/../JCSQL/Payment';
$order_file = $order_dir . '/' . $out_trade_no . '.json';
$video_id = '';
$video_page = '';

if (file_exists($order_file)) {
    $order_data = json_decode(file_get_contents($order_file), true);
    if ($order_data) {
        $video_id = isset($order_data['video_id']) ? $order_data['video_id'] : '';
        $video_page = isset($order_data['video_page']) ? $order_data['video_page'] : '';
    }
}

// 跳转回视频页面
$redirect_url = '/';
if ($video_id && $video_page) {
    $redirect_url = "/?m=video_conter-id-{$video_id}-page-{$video_page}&paid=1";
    if ($trade_no) {
        $redirect_url .= '&trade_no=' . urlencode($trade_no);
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支付成功 - YUTUCMS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        .success-container { background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); padding: 50px; text-align: center; max-width: 450px; animation: scaleIn 0.5s ease; }
        @keyframes scaleIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .checkmark { width: 80px; height: 80px; background: linear-gradient(135deg, #52C41A, #73d13d); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 40px; color: white; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(82,196,26,0.4); } 70% { box-shadow: 0 0 0 20px rgba(82,196,26,0); } 100% { box-shadow: 0 0 0 0 rgba(82,196,26,0); } }
        h2 { font-size: 24px; color: #333; margin-bottom: 10px; }
        p { color: #666; margin-bottom: 30px; font-size: 15px; }
        .btn-continue { display: inline-block; padding: 14px 40px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; text-decoration: none; transition: all 0.3s; }
        .btn-continue:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(102,126,234,0.4); }
        .loading-text { margin-top: 15px; font-size: 13px; color: #999; }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="checkmark">✓</div>
        <h2>支付成功！🎉</h2>
        <p>感谢您的购买，视频即将继续播放</p>
        <a href="<?php echo htmlspecialchars($redirect_url); ?>" class="btn-continue">继续观看</a>
        <p class="loading-text">页面将在 <span id="countdown">3</span> 秒后自动跳转...</p>
    </div>

    <script>
    let countdown = 3;
    const countdownEl = document.getElementById('countdown');
    const timer = setInterval(function() {
        countdown--;
        countdownEl.textContent = countdown;
        if (countdown <= 0) {
            clearInterval(timer);
            window.location.href = '<?php echo htmlspecialchars($redirect_url); ?>';
        }
    }, 1000);
    </script>
</body>
</html>