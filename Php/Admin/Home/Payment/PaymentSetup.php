<?php
if (isset($ADMINKEY)) { }else{ exit('404');   }   
include('../Php/Admin/cookie.php');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支付设置 - YUTUCMS管理中心</title>
    <meta name="description" content="YUTUCMS 支付设置">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', 'Microsoft YaHei', sans-serif; }
        :root { --primary-color: #165DFF; --secondary-color: #36CFC9; --light-color: #F2F3F5; --dark-color: #1D2129; --success-color: #52C41A; --warning-color: #FAAD14; --danger-color: #FF4D4F; --gray-color: #86909C; --sidebar-width: 250px; --header-height: 70px; }
        body { background-color: var(--light-color); color: var(--dark-color); display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: white; height: 100vh; position: fixed; overflow-y: auto; z-index: 100; }
        .sidebar-header { padding: 20px; display: flex; align-items: center; border-bottom: 1px solid var(--light-color); height: var(--header-height); }
        .logo { display: flex; align-items: center; gap: 12px; font-weight: 700; font-size: 1.3rem; color: var(--primary-color); }
        .sidebar-menu { padding: 15px 0; }
        .menu-item { padding: 12px 20px; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: all 0.3s; border-left: 4px solid transparent; color: var(--dark-color); text-decoration: none; }
        .menu-item:hover { background: var(--light-color); border-left: 4px solid var(--primary-color); }
        .menu-item.active { background: rgba(22, 93, 255, 0.1); border-left: 4px solid var(--primary-color); color: var(--primary-color); }
        .menu-item i { font-size: 1.1rem; width: 20px; text-align: center; }
        .menu-text { font-size: 0.95rem; }
        .header { height: var(--header-height); background: white; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; position: fixed; top: 0; left: 250px; right: 0; z-index: 99; }
        .main-content { flex: 1; margin-left: 250px; margin-top: var(--header-height); padding: 30px; }
        .breadcrumb { display: flex; align-items: center; gap: 10px; margin-bottom: 30px; font-size: 0.9rem; color: var(--gray-color); }
        .breadcrumb a { color: var(--primary-color); text-decoration: none; }
        .page-title { font-size: 1.8rem; font-weight: 700; margin-bottom: 10px; display: flex; align-items: center; gap: 10px; }
        .page-title i { color: var(--primary-color); }
        .page-subtitle { color: var(--gray-color); margin-bottom: 30px; }
        .settings-card { background: white; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.05); margin-bottom: 30px; overflow: hidden; }
        .card-header { padding: 20px 25px; border-bottom: 1px solid var(--light-color); display: flex; align-items: center; justify-content: space-between; }
        .card-title { font-size: 1.2rem; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .card-title i { color: var(--primary-color); }
        .card-body { padding: 25px; }
        .form-group { margin-bottom: 25px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 500; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; transition: all 0.3s; }
        .form-control:focus { border-color: var(--primary-color); box-shadow: 0 0 0 2px rgba(22,93,255,0.1); outline: none; }
        .form-select { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; background-color: white; appearance: none; }
        .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 2px rgba(22,93,255,0.1); outline: none; }
        .form-hint { font-size: 0.85rem; color: var(--gray-color); margin-top: 5px; }
        .form-row { display: flex; gap: 20px; margin-bottom: 25px; }
        .form-col { flex: 1; }
        .switch-container { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
        .switch-label { font-weight: 500; }
        .switch { position: relative; display: inline-block; width: 50px; height: 26px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: var(--primary-color); }
        input:checked + .slider:before { transform: translateX(24px); }
        .switch-status { font-size: 0.9rem; color: var(--gray-color); }
        .switch-status.active { color: var(--success-color); font-weight: 500; }
        .btn { display: inline-block; padding: 12px 24px; border: none; border-radius: 6px; font-size: 0.95rem; font-weight: 500; cursor: pointer; transition: all 0.3s; }
        .btn-primary { background-color: var(--primary-color); color: white; }
        .btn-primary:hover { background-color: #0e4dff; box-shadow: 0 4px 12px rgba(22,93,255,0.3); }
        .btn-success { background-color: var(--success-color); color: white; }
        .btn-success:hover { background-color: #46a51a; }
        .btn-group { display: flex; gap: 15px; margin-top: 30px; }
        .alert { padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .alert-success { background-color: #f1fff5; border: 1px solid #a5d6a7; color: #2e7d32; }
        .alert-warning { background-color: #fffbf0; border: 1px solid #ffe58f; color: #d46b08; }
        .alert-info { background-color: #e8f4fd; border: 1px solid #b8daff; color: #004085; }
        @media (max-width: 992px) { .sidebar { left: -100%; } .header { left: 0; } .main-content { margin-left: 0; } .form-row { flex-direction: column; } }
        @media (max-width: 576px) { .main-content { padding: 20px 15px; } }
    </style>
</head>
<body>
    <?php include('../Php/Admin/list.php');?>
    <?php include('../Php/Admin/header.php');?>
    <div class="main-content">
        <div class="breadcrumb">
            <a href="#"><i class="fa fa-home"></i> 首页</a>
            <span class="separator">/</span>
            <span class="current">支付设置</span>
        </div>
        <h1 class="page-title"><i class="fa fa-credit-card"></i> 支付设置</h1>
        <p class="page-subtitle">配置视频付费功能和四方支付聚合支付接口</p>

        <?php
        // 读取支付配置
        $AdminPayment = json_decode(file_get_contents("../JCSQL/Admin/Basic/AdminPayment.php"), true);
        $payment_enabled = isset($AdminPayment['payment_enabled']) ? $AdminPayment['payment_enabled'] : '0';
        $free_seconds = isset($AdminPayment['free_seconds']) ? $AdminPayment['free_seconds'] : '15';
        $payment_price = isset($AdminPayment['payment_price']) ? $AdminPayment['payment_price'] : '9.90';
        $payment_name = isset($AdminPayment['payment_name']) ? $AdminPayment['payment_name'] : '观看完整视频';
        $payment_description = isset($AdminPayment['payment_description']) ? $AdminPayment['payment_description'] : '试看15秒后需付费观看完整视频';
        $merchant_id = isset($AdminPayment['merchant_id']) ? $AdminPayment['merchant_id'] : '';
        $merchant_key = isset($AdminPayment['merchant_key']) ? $AdminPayment['merchant_key'] : '';
        $merchant_api_url = isset($AdminPayment['merchant_api_url']) ? $AdminPayment['merchant_api_url'] : '';
        $merchant_type = isset($AdminPayment['merchant_type']) ? $AdminPayment['merchant_type'] : 'epay';

        // 保存配置
        if (isset($_POST['submit'])) {
            function post_input($data) {
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            $config = array();
            $config['payment_enabled'] = (isset($_POST['payment_enabled']) && $_POST['payment_enabled'] == '1') ? '1' : '0';
            $config['free_seconds'] = isset($_POST['free_seconds']) ? post_input($_POST['free_seconds']) : '15';
            $config['payment_price'] = isset($_POST['payment_price']) ? post_input($_POST['payment_price']) : '9.90';
            $config['payment_name'] = isset($_POST['payment_name']) ? post_input($_POST['payment_name']) : '观看完整视频';
            $config['payment_description'] = isset($_POST['payment_description']) ? post_input($_POST['payment_description']) : '';
            $config['merchant_id'] = isset($_POST['merchant_id']) ? post_input($_POST['merchant_id']) : '';
            $config['merchant_key'] = isset($_POST['merchant_key']) ? post_input($_POST['merchant_key']) : '';
            $config['merchant_api_url'] = isset($_POST['merchant_api_url']) ? post_input($_POST['merchant_api_url']) : '';
            $config['merchant_type'] = isset($_POST['merchant_type']) ? post_input($_POST['merchant_type']) : 'epay';
            $config['notify_url'] = isset($_POST['notify_url']) ? post_input($_POST['notify_url']) : '';
            $config['return_url'] = isset($_POST['return_url']) ? post_input($_POST['return_url']) : '';

            $file = fopen("../JCSQL/Admin/Basic/AdminPayment.php", "w");
            if ($file) {
                fwrite($file, json_encode($config));
                fclose($file);
                echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> 支付设置保存成功！</div>';
                // 更新变量
                $payment_enabled = $config['payment_enabled'];
                $free_seconds = $config['free_seconds'];
                $payment_price = $config['payment_price'];
                $payment_name = $config['payment_name'];
                $payment_description = $config['payment_description'];
                $merchant_id = $config['merchant_id'];
                $merchant_key = $config['merchant_key'];
                $merchant_api_url = $config['merchant_api_url'];
                $merchant_type = $config['merchant_type'];
                $AdminPayment = $config;
            } else {
                echo '<div class="alert" style="background:#fff5f5;border:1px solid #ffcdd2;color:#c62828;"><i class="fa fa-exclamation-triangle"></i> 保存失败，请检查文件权限</div>';
            }
        }

        // 自动生成notify_url和return_url
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        $domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'yutucms.com';
        $auto_notify_url = $http_type . $domain . '/Php/Payment/notify.php';
        $auto_return_url = $http_type . $domain . '/Php/Payment/return.php';
        $auto_order_url = $http_type . $domain . '/Php/Payment/order.php';
        ?>

        <div class="settings-card">
            <div class="card-header">
                <h2 class="card-title"><i class="fa fa-toggle-on"></i> 付费功能开关</h2>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <div class="switch-container">
                            <label class="switch-label">开启视频付费功能</label>
                            <label class="switch">
                                <input type="checkbox" id="payment_enabled" name="payment_enabled" value="1" <?php echo $payment_enabled == '1' ? 'checked' : ''; ?>>
                                <span class="slider"></span>
                            </label>
                            <span class="switch-status <?php echo $payment_enabled == '1' ? 'active' : ''; ?>">
                                <?php echo $payment_enabled == '1' ? '已开启' : '已关闭'; ?>
                            </span>
                        </div>
                        <div class="form-hint">开启后，用户观看视频前15秒免费，之后弹出支付窗口</div>
                    </div>

                    <div id="paymentConfig" style="<?php echo $payment_enabled == '1' ? '' : 'display:none;'; ?>">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> 以下为付费功能和四方支付接口配置，请根据你的支付服务商填写。
                        </div>

                        <h3 style="margin: 20px 0 15px; font-size: 1.1rem;"><i class="fa fa-clock-o"></i> 试看设置</h3>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label">免费试看时间（秒）</label>
                                    <input type="number" class="form-control" name="free_seconds" value="<?php echo $free_seconds; ?>" min="1" max="300">
                                    <div class="form-hint">用户免费观看的秒数，建议15-30秒</div>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label">付费金额（元）</label>
                                    <input type="text" class="form-control" name="payment_price" value="<?php echo $payment_price; ?>" placeholder="9.90">
                                    <div class="form-hint">观看完整视频需要支付的金额</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">付费提示标题</label>
                            <input type="text" class="form-control" name="payment_name" value="<?php echo $payment_name; ?>" placeholder="观看完整视频">
                        </div>
                        <div class="form-group">
                            <label class="form-label">付费提示描述</label>
                            <textarea class="form-control" name="payment_description" rows="2" placeholder="试看15秒后需付费观看完整视频"><?php echo $payment_description; ?></textarea>
                        </div>

                        <h3 style="margin: 25px 0 15px; font-size: 1.1rem;"><i class="fa fa-exchange"></i> 四方支付接口配置</h3>
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i> 支持易支付/ePay 标准的四方支付接口。请先注册四方支付平台获取商户信息。
                        </div>

                        <div class="form-group">
                            <label class="form-label">支付接口类型</label>
                            <select name="merchant_type" class="form-select">
                                <option value="epay" <?php echo $merchant_type == 'epay' ? 'selected' : ''; ?>>易支付 (ePay)</option>
                                <option value="other" <?php echo $merchant_type == 'other' ? 'selected' : ''; ?>>其他标准支付接口</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">商户ID</label>
                            <input type="text" class="form-control" name="merchant_id" value="<?php echo $merchant_id; ?>" placeholder="支付平台分配的商户ID">
                        </div>

                        <div class="form-group">
                            <label class="form-label">商户密钥</label>
                            <input type="text" class="form-control" name="merchant_key" value="<?php echo $merchant_key; ?>" placeholder="支付平台分配的商户密钥">
                        </div>

                        <div class="form-group">
                            <label class="form-label">支付接口API地址</label>
                            <input type="text" class="form-control" name="merchant_api_url" value="<?php echo $merchant_api_url; ?>" placeholder="例如：https://pay.example.com/mapi.php">
                            <div class="form-hint">四方支付平台的API请求地址</div>
                        </div>

                        <h3 style="margin: 25px 0 15px; font-size: 1.1rem;"><i class="fa fa-link"></i> 回调地址（自动生成）</h3>
                        <div class="alert alert-info">
                            <p><strong>异步通知地址 (Notify URL)：</strong></p>
                            <input type="text" class="form-control" name="notify_url" value="<?php echo $notify_url ? $notify_url : $auto_notify_url; ?>" placeholder="<?php echo $auto_notify_url; ?>">
                            <div class="form-hint">在四方支付平台设置此地址用于接收支付结果通知</div>
                            <p style="margin-top:10px;"><strong>同步跳转地址 (Return URL)：</strong></p>
                            <input type="text" class="form-control" name="return_url" value="<?php echo $return_url ? $return_url : $auto_return_url; ?>" placeholder="<?php echo $auto_return_url; ?>">
                            <div class="form-hint">支付成功后用户跳转回本网站的地址</div>
                            <p style="margin-top:10px;"><strong>下单页面地址：</strong></p>
                            <code><?php echo $auto_order_url; ?></code>
                        </div>

                        <div class="btn-group">
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> 保存设置
                            </button>
                        </div>
                    </div>

                    <?php if ($payment_enabled != '1'): ?>
                    <div class="btn-group">
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> 保存设置
                        </button>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="sidebar-overlay"></div>
    <script src="../Static/Admin/Js/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#payment_enabled').change(function() {
            var isChecked = $(this).is(':checked');
            var statusText = isChecked ? '已开启' : '已关闭';
            $(this).closest('.switch-container').find('.switch-status').text(statusText).toggleClass('active', isChecked);
            if (isChecked) {
                $('#paymentConfig').slideDown(300);
            } else {
                $('#paymentConfig').slideUp(300);
            }
        });
    });
    </script>
    <?php include('../Php/Admin/footer.php');?>
</body>
</html>