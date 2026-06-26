<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="title" content="播放器">
<meta name="apple-touch-fullscreen" content="YES">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="format-detection" content="telephone=no">
<title>播放器</title>
<style>
body { margin: 0; padding: 0; background: #000; overflow: hidden; }
.web_size { position: relative; width: 100%; height: 100vh; background: #000; overflow: hidden; }
.video-js { width: 100% !important; height: 100% !important; max-width: 100%; max-height: 100%; background-color: #000; object-fit: cover !important; }
.vjs-poster { background-size: cover !important; background-position: center center !important; background-repeat: no-repeat !important; }
.video-js .vjs-big-play-button { position: absolute !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%) !important; width: 70px !important; height: 70px !important; line-height: 70px !important; border-radius: 50% !important; background-color: rgba(0,0,0,0.45) !important; border: none !important; font-size: 2.2em !important; margin: 0 !important; z-index: 10 !important; transition: none !important; }
.video-js:hover .vjs-big-play-button { transform: translate(-50%, -50%) scale(1.06) !important; background-color: rgba(255,255,255,0.2) !important; }
.video-js .vjs-big-play-button .vjs-icon-placeholder:before { font-size: 3em !important; }
.video-js .vjs-control.vjs-subs-caps-button, .video-js .vjs-subs-caps-button, .video-js .vjs-texttrack-settings { display: none !important; }
@media (max-width: 768px) { .web_size { height: auto; aspect-ratio: 16/9; } }
.vjs-control-bar { background: none !important; border-top: none !important; }
.vjs-control-bar::after { content: ""; position: absolute; bottom: 0; left: 0; width: 100%; height: 36px; background: rgba(0, 0, 0, 0.35); backdrop-filter: blur(6px); border-top: 1px solid rgba(255, 255, 255, 0.1); pointer-events: none; z-index: 0; }
.vjs-control-bar .vjs-control { position: relative; z-index: 1; }
.vjs-progress-control { z-index: 2; margin-bottom: 4px !important; }
.vjs-volume-panel.vjs-volume-panel-horizontal .vjs-volume-control { display: none !important; }
.vjs-mute-control { cursor: pointer !important; transition: transform 0.2s ease; }
.vjs-mute-control:hover { transform: scale(1.15); }
.vjs-volume-panel { width: 40px !important; min-width: 40px !important; }

/* ----- 支付覆盖层样式 ----- */
.payment-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.85);
    z-index: 2147483647;
    display: none;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(4px);
}
.payment-overlay.active { display: flex; }
.payment-overlay.active .payment-modal {
    z-index: 2147483647;
}
/* 移动端全屏覆盖 */
@media (max-width: 768px) {
    .payment-overlay {
        position: fixed !important;
        top: 0 !important; left: 0 !important;
        width: 100vw !important; height: 100vh !important;
        z-index: 2147483647 !important;
    }
    .payment-overlay.active .payment-modal {
        max-width: 85vw;
        padding: 30px 20px 25px;
    }
}
.payment-modal {
    background: white;
    border-radius: 16px;
    padding: 40px 30px 30px;
    max-width: 360px;
    width: 90%;
    text-align: center;
    animation: modalIn 0.4s ease;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
}
@keyframes modalIn {
    from { transform: translateY(30px) scale(0.95); opacity: 0; }
    to { transform: translateY(0) scale(1); opacity: 1; }
}
.payment-modal .lock-icon {
    font-size: 52px;
    margin-bottom: 15px;
}
.payment-modal h3 {
    font-size: 20px;
    color: #333;
    margin-bottom: 8px;
    font-weight: 700;
}
.payment-modal .desc {
    font-size: 14px;
    color: #999;
    margin-bottom: 8px;
    line-height: 1.5;
}
.payment-modal .countdown-hint {
    font-size: 12px;
    color: #ff6600;
    margin-bottom: 20px;
    font-weight: 500;
}
.payment-modal .price-tag {
    font-size: 36px;
    font-weight: 700;
    color: #667eea;
    margin: 15px 0 5px;
}
.payment-modal .price-tag .currency {
    font-size: 20px;
}
.payment-modal .price-label {
    font-size: 13px;
    color: #999;
    margin-bottom: 20px;
}
.payment-modal .btn-pay-now {
    display: block;
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
}
.payment-modal .btn-pay-now:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(102,126,234,0.4); }

/* 倒计时提示条 */
.free-countdown {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0,0,0,0.6);
    color: #fff;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    z-index: 100;
    display: none;
    backdrop-filter: blur(4px);
}
.free-countdown.active { display: block; }
.free-countdown .count-num { color: #ff6b6b; font-weight: 700; }

/* 支付成功提示 */
.payment-success-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1001;
    display: none;
    justify-content: center;
    align-items: center;
}
.payment-success-overlay.active { display: flex; }
.payment-success-box {
    background: white;
    border-radius: 16px;
    padding: 40px 30px;
    text-align: center;
    animation: modalIn 0.4s ease;
}
.payment-success-box .checkmark {
    width: 60px; height: 60px;
    background: linear-gradient(135deg, #52C41A, #73d13d);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 15px;
    font-size: 30px; color: white;
}
.payment-success-box h3 { font-size: 20px; color: #333; }
.payment-success-box p { font-size: 14px; color: #999; margin-top: 5px; }
</style>
<body>
<?php
include('../../../Php/Public/Helper.php');

$vPath  = safeRequest($_GET['Play']);
$poster = safeRequest($_GET['Cover']);

// 读取支付配置
$paymentConfigPath = __DIR__ . '/../../../JCSQL/Admin/Basic/AdminPayment.php';
$paymentConfig = array();
if (file_exists($paymentConfigPath)) {
    $paymentConfig = json_decode(file_get_contents($paymentConfigPath), true);
}
if (!is_array($paymentConfig)) { $paymentConfig = array(); }

$payment_enabled = isset($paymentConfig['payment_enabled']) ? $paymentConfig['payment_enabled'] : '0';
$free_seconds = isset($paymentConfig['free_seconds']) ? intval($paymentConfig['free_seconds']) : 15;
$payment_price = isset($paymentConfig['payment_price']) ? $paymentConfig['payment_price'] : '9.90';
$payment_name = isset($paymentConfig['payment_name']) ? $paymentConfig['payment_name'] : '观看完整视频';
$payment_description = isset($paymentConfig['payment_description']) ? $paymentConfig['payment_description'] : '试看15秒后需付费观看完整视频';

// 获取域名
$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
$domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'yutucms.com';
$base_url = $http_type . $domain;

// 从URL获取视频信息（通过referer或参数传递）
$video_id = isset($_GET['video_id']) ? safeRequest($_GET['video_id']) : '';
$video_name = isset($_GET['video_name']) ? safeRequest($_GET['video_name']) : '';
$video_page = isset($_GET['video_page']) ? safeRequest($_GET['video_page']) : '';

// 如果通过iframe嵌入，尝试从父页面URL解析参数
$order_url = $base_url . '/Php/Payment/order.php';
$check_paid_url = $base_url . '/Php/Payment/check_paid.php';
?>
<div class="web_size">
    <div class="free-countdown" id="freeCountdown">
        免费试看 <span class="count-num" id="countdownNum"><?php echo $free_seconds; ?></span>s
    </div>

    <video id="myVideo" class="video-js vjs-default-skin" controls preload="auto" poster="<?php echo $poster; ?>" playsinline webkit-playsinline x5-video-player-type="h5" x5-video-player-fullscreen="true" x-webkit-airplay="allow"></video>

    <!-- 支付覆盖层 -->
    <div class="payment-overlay" id="paymentOverlay">
        <div class="payment-modal">
            <div class="lock-icon">🔒</div>
            <h3 id="payTitle"><?php echo htmlspecialchars($payment_name); ?></h3>
            <div class="desc" id="payDesc"><?php echo htmlspecialchars($payment_description); ?></div>
            <div class="countdown-hint" id="freeEndHint">⏰ 免费试看已结束</div>
            <div class="price-tag"><span class="currency">¥</span> <span id="payPrice"><?php echo htmlspecialchars($payment_price); ?></span></div>
            <div class="price-label">支付后完整观看</div>
            <a href="#" class="btn-pay-now" id="btnPayNow">💳 立即支付 ¥<?php echo htmlspecialchars($payment_price); ?></a>
        </div>
    </div>

    <!-- 支付成功提示 -->
    <div class="payment-success-overlay" id="paymentSuccessOverlay">
        <div class="payment-success-box">
            <div class="checkmark">✓</div>
            <h3>支付成功！🎉</h3>
            <p>视频即将继续播放...</p>
        </div>
    </div>

    <link rel="stylesheet" type="text/css" href="video.min.css?v=3">
    <script type="text/javascript" src="video.min.js?v=1" charset="utf-8"></script>
    <script type="text/javascript" src="video-conrtib-ads.js?v=1" charset="utf-8"></script>
    <script type="text/javascript" src="myVideo.js?v=6" charset="utf-8"></script>

    <script type="text/javascript">
        var vPath = '<?php echo $vPath; ?>';
        var logo = '';
        
        // 支付配置
        var PAYMENT_ENABLED = <?php echo $payment_enabled; ?>;
        var FREE_SECONDS = <?php echo $free_seconds; ?>;
        var PAYMENT_PRICE = '<?php echo $payment_price; ?>';
        var PAYMENT_TITLE = '<?php echo addslashes($payment_name); ?>';
        var PAYMENT_DESC = '<?php echo addslashes($payment_description); ?>';
        var ORDER_URL = '<?php echo $order_url; ?>';
        var CHECK_PAID_URL = '<?php echo $check_paid_url; ?>';
        var VIDEO_ID = '<?php echo addslashes($video_id); ?>';
        var VIDEO_NAME = '<?php echo addslashes($video_name); ?>';
        var VIDEO_PAGE = '<?php echo addslashes($video_page); ?>';

        var myVideo = initVideo({
            id: 'myVideo',
            url: vPath,
            ad: { pre: { url: '', link: '' } },
            payment: { // 传递支付配置给myVideo.js
                enabled: PAYMENT_ENABLED,
                freeSeconds: FREE_SECONDS,
                price: PAYMENT_PRICE,
                title: PAYMENT_TITLE,
                desc: PAYMENT_DESC,
                orderUrl: ORDER_URL,
                checkPaidUrl: CHECK_PAID_URL,
                videoId: VIDEO_ID,
                videoName: VIDEO_NAME,
                videoPage: VIDEO_PAGE
            }
        });
    </script>
</div>
</body>
</html>