 <?php 
 include('../Php/Admin/cookie.php'); 
 date_default_timezone_set('Asia/Shanghai'); // ← 你可改为 Asia/Singapore 或其他时区
$serverTs = time(); // 当前服务器时间戳
 ?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YUTUCMS管理中心</title>
    <meta name="description" content="YUTUCMS 管理中心">
    <meta name="keywords" content="YUTUCMS, 管理后台">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    
    <!-- 引入Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', 'Microsoft YaHei', sans-serif;
        }

        :root {
            --primary-color: #165DFF;
            --secondary-color: #36CFC9;
            --accent-color: #4895ef;
            --light-color: #F2F3F5;
            --dark-color: #1D2129;
            --success-color: #52C41A;
            --warning-color: #FAAD14;
            --danger-color: #FF4D4F;
            --gray-color: #86909C;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --header-height: 70px;
        }

        body {
            background-color: var(--light-color);
            color: var(--dark-color);
            display: flex;
            min-height: 100vh;
        }

        /* 侧边栏样式 */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            color: var(--dark-color);
            height: 100vh;
            position: fixed;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--light-color);
            height: var(--header-height);
            background: white;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--primary-color);
        }

        .logo i {
            font-size: 1.5rem;
        }

        .logo-text {
            transition: opacity 0.3s;
        }

        .sidebar.collapsed .logo-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: var(--gray-color);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .toggle-btn:hover {
            background: var(--light-color);
        }

        .sidebar-menu {
            padding: 15px 0;
            height: calc(100vh - var(--header-height));
            overflow-y: auto;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.3s;
            border-left: 4px solid transparent;
            color: var(--dark-color);
            text-decoration: none;
        }

        .menu-item:hover {
            background: var(--light-color);
            border-left: 4px solid var(--primary-color);
        }

        .menu-item.active {
            background: rgba(22, 93, 255, 0.1);
            border-left: 4px solid var(--primary-color);
            color: var(--primary-color);
        }

        .menu-item i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .menu-text {
            transition: opacity 0.3s;
            font-size: 0.95rem;
        }

        .sidebar.collapsed .menu-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .submenu {
            margin-left: 20px;
            margin-top: 5px;
            display: none;
        }

        .submenu.active {
            display: block;
        }

        .submenu-item {
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s;
            border-radius: 6px;
            color: var(--gray-color);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .submenu-item:hover {
            background: var(--light-color);
            color: var(--primary-color);
        }

        .submenu-item.active {
            background: rgba(22, 93, 255, 0.08);
            color: var(--primary-color);
        }

        .submenu-item i {
            font-size: 0.9rem;
            width: 16px;
        }

        .menu-badge {
            background: var(--danger-color);
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: auto;
        }

        /* 头部样式 */
        .header {
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            z-index: 99;
            transition: left 0.3s;
        }

        .sidebar.collapsed ~ .header {
            left: var(--sidebar-collapsed-width);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
            color: var(--gray-color);
        }

        .header-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-icon {
            position: relative;
            cursor: pointer;
            color: var(--gray-color);
            font-size: 1.2rem;
            transition: color 0.3s;
        }

        .header-icon:hover {
            color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger-color);
            color: white;
            font-size: 0.7rem;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .user-name {
            font-weight: 500;
        }

        /* 主内容区样式 */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 30px;
            transition: margin-left 0.3s;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        .welcome-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .welcome-title {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .welcome-subtitle {
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-info {
            flex: 1;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--gray-color);
            font-size: 0.9rem;
        }

        .quick-actions {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.3rem;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }

        .action-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px 15px;
            border: 1px solid var(--light-color);
            border-radius: 8px;
            text-decoration: none;
            color: var(--dark-color);
            transition: all 0.3s;
        }

        .action-item:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .action-icon {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .action-text {
            font-size: 0.9rem;
            text-align: center;
        }

        .system-info {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .info-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 15px;
        }

        .info-close {
            cursor: pointer;
            color: var(--gray-color);
            font-size: 1.2rem;
        }

        .info-close:hover {
            color: var(--dark-color);
        }

        /* 响应式设计 */
        

        @media (max-width: 576px) {
            .header {
                padding: 0 15px;
            }

            .main-content {
                padding: 20px 15px;
            }

            .welcome-section {
                padding: 20px;
            }

            .stats-cards {
                grid-template-columns: 1fr;
            }

            .user-name {
                display: none;
            }

            .action-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
    
    <!-- 引入ECharts -->
    <script src="../Static/Admin/Css/echarts.min.js"></script>
</head>
<body>
   
    
    <!-- 侧边栏 -->
    <?php include('../Php/Admin/list.php');?>

    <!-- 头部 -->
    <?php include('../Php/Admin/header.php');?>

    <!-- 主内容区 -->
    <div class="main-content">
        <!-- 快速操作 -->
        <div class="usage-guide">
    <h3 class="section-title">
        <span><i class="fa fa-book"></i> 使用说明</span>
    </h3>
    <div class="guide-content">
        <div class="guide-notice">
            <i class="fa fa-copyright"></i>
            <span>此程序版权所有YUTUCMS, YUTUCMS.COM，请勿盗版。使用官网最新版本，提高建站体验和建站安全</span>
        </div>
        
        <div class="guide-list">
            <div class="guide-item">
                <div class="guide-number">1</div>
                <div class="guide-text">本系统所有资源均自动采集，无需人工，省时省力。新版架构全新配置界面</div>
            </div>
            <div class="guide-item">
                <div class="guide-number">2</div>
                <div class="guide-text">自适应所有设备，PC/手机/pad均可使用</div>
            </div>
            <div class="guide-item">
                <div class="guide-number">3</div>
                <div class="guide-text">本系统不依托任何第三方CMS，纯PHP，对环境要求小，基本所有的PHP环境都可轻松带起</div>
            </div>
            <div class="guide-item">
                <div class="guide-number">4</div>
                <div class="guide-text">所有广告及版权信息均可从后台更改</div>
            </div>
        </div>
        
        <div class="guide-contact">
            <div class="contact-item">
                <i class="fa fa-paper-plane"></i>
                <span>电报交流群：</span>
                <a href="https://t.me/yutucms" target="_blank">https://t.me/yutucms -欢迎加入交流群提供宝贵意见</a>
            </div>
        </div>
    </div>
</div>

<style>
.usage-guide {
    background: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
}

.guide-content {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.guide-notice {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    border: 1px solid #ffeaa7;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
    color: #856404;
}

.guide-notice i {
    color: #f39c12;
    font-size: 1.1rem;
}

.guide-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.guide-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 12px;
    border-radius: 8px;
    background: #f8f9fa;
    transition: all 0.3s;
}

.guide-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.guide-number {
    background: var(--primary-color);
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 600;
    flex-shrink: 0;
    margin-top: 2px;
}

.guide-text {
    color: var(--dark-color);
    line-height: 1.5;
    flex: 1;
}

.guide-contact {
    background: #e8f4fd;
    border: 1px solid #b8daff;
    border-radius: 8px;
    padding: 15px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #004085;
}

.contact-item i {
    color: var(--primary-color);
}

.contact-item a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.contact-item a:hover {
    text-decoration: underline;
}

.guide-footer {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
    color: #155724;
    text-align: center;
    justify-content: center;
}

.guide-footer i {
    color: #28a745;
    animation: heartbeat 2s infinite;
}

@keyframes heartbeat {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* 响应式设计 */
@media (max-width: 768px) {
    .guide-item {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .guide-number {
        align-self: center;
    }
    
    .guide-notice,
    .guide-footer {
        text-align: center;
        flex-direction: column;
        gap: 8px;
    }
}
@media (max-width: 992px) {
    .sidebar {
        left: -100%;
        width: 280px;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .sidebar.mobile-open {
        left: 270px;
    }

    /* 移动端完全隐藏collapsed状态 */
    .sidebar.collapsed {
        left: -100%;
        transform: translateX(-100%);
        width: 280px;
    }

    .sidebar.collapsed.mobile-open {
        transform: translateX(0);
    }

    .header {
        left: 0;
        width: 100%;
    }

    .main-content {
        margin-left: 0;
        width: 100%;
    }

    .mobile-toggle {
        display: block;
    }

    /* 移动端遮罩层 */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 99;
    }

    .sidebar.mobile-open ~ .sidebar-overlay {
        display: block;
    }
    
    /* 只在移动端隐藏折叠按钮 */
    .sidebar .toggle-btn {
        display: none;
    }
}

/* 桌面端显示折叠按钮 */
@media (min-width: 993px) {
    .sidebar .toggle-btn {
        display: block;
    }
}


/* ===== 系统信息字体优化 ===== */
.system-info .stat-value {
    font-size: 0.95rem;   /* 默认约15px */
    font-weight: 600;
    color: #333;
}

.system-info .stat-label {
    font-size: 0.8rem;    /* 默认约13px */
    color: #777;
}

.system-info .stat-card {
    padding: 16px 18px;   /* 卡片稍紧凑 */
}

.system-info .stat-icon {
    width: 46px;
    height: 46px;
    font-size: 18px;
}


</style>

<!-- 系统信息 -->
<div class="system-info">
    <div class="info-header">
        <h3 class="section-title">
            <span><i class="fa fa-info-circle"></i> 系统信息</span>
        </h3>
    </div>
    
    <div class="stats-cards">
        <!-- CMS版本 -->
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--primary-color);">
                <i class="fa fa-cogs"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">v2.0</div>
                <div class="stat-label">YUTUCMS 版本</div>
            </div>
        </div>

        <!-- 操作系统 -->
        <div class="stat-card">
            <div class="stat-icon" style="background: #2f54eb;">
                <i class="fa fa-desktop"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo php_uname('s') . ' ' . php_uname('r'); ?></div>
                <div class="stat-label">操作系统</div>
            </div>
        </div>

        <!-- Web 服务器 -->
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--success-color);">
                <i class="fa fa-server"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">
                    <?php
                    if (isset($_SERVER['SERVER_SOFTWARE'])) {
                        echo $_SERVER['SERVER_SOFTWARE'];
                    } else {
                        echo '未知';
                    }
                    ?>
                </div>
                <div class="stat-label">Web 服务器</div>
            </div>
        </div>

        <!-- PHP 版本 -->
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--warning-color);">
                <i class="fa fa-code"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo PHP_VERSION; ?></div>
                <div class="stat-label">PHP 版本</div>
            </div>
        </div>

        <!-- 系统时区 -->
        <div class="stat-card">
            <div class="stat-icon" style="background: #13c2c2;">
                <i class="fa fa-globe"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo date_default_timezone_get(); ?></div>
                <div class="stat-label">系统时区</div>
            </div>
        </div>

<!-- 当前时间（自动刷新，服务器时间） -->

<div class="stat-card">
    <div class="stat-icon" style="background: #722ed1;">
        <i class="fa fa-clock-o"></i>
    </div>
    <div class="stat-info">
        <!-- data-server-ts 由 PHP 输出服务器时间戳 -->
        <div class="stat-value" 
             id="currentTime" 
             data-server-ts="<?php echo $serverTs; ?>">
             <?php echo date('Y-m-d H:i:s', $serverTs); ?>
        </div>
        <div class="stat-label">服务器时间</div>
    </div>
</div>

        <!-- 内存限制 -->
        <div class="stat-card">
            <div class="stat-icon" style="background: #fa541c;">
                <i class="fa fa-microchip"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo ini_get("memory_limit"); ?></div>
                <div class="stat-label">内存限制</div>
            </div>
        </div>

        <!-- 上传限制 -->
        <div class="stat-card">
            <div class="stat-icon" style="background: #eb2f96;">
                <i class="fa fa-upload"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo ini_get("upload_max_filesize"); ?></div>
                <div class="stat-label">上传文件限制</div>
            </div>
        </div>
    </div>
</div>


<!-- 自动更新时间脚本 -->
<!-- 自动更新时间脚本（强制使用服务器时区） -->
<script>
(function() {
    const el = document.getElementById('currentTime');
    if (!el) return;

    // 从 PHP 传递来的服务器时间戳（秒→毫秒）
    let ts = parseInt(el.dataset.serverTs, 10) * 1000;

    // PHP 设置的服务器时区（与 PHP 中的 date_default_timezone_set 一致）
    const serverTz = 'Asia/Shanghai'; // 你也可改成 'Asia/Singapore' 等

    // 定义格式化器：强制使用服务器时区
    const fmt = new Intl.DateTimeFormat('zh-CN', {
        timeZone: serverTz,
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
    });

    // 每秒更新
    function tick() {
        el.textContent = fmt.format(new Date(ts)).replace(/\//g, '-').replace(',', '');
        ts += 1000;
    }

    tick(); // 页面加载时立即执行一次
    setInterval(tick, 1000);
})();
</script>



    <script src="../Static/Admin/Js/jquery.min.js"></script>
  <?php include('../Php/Admin/footer.php');?>
</body>
</html>

<style>
     @media (max-width: 992px) {
            
            .sidebar.mobile-open {
                left: 270px !important;
            }

</style>