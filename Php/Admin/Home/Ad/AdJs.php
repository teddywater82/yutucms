<?php
if (isset($ADMINKEY)) { }else{ exit('404');   }   
include('../Php/Admin/cookie.php');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>广告联盟JS广告 - YUTUCMS管理中心</title>
    <meta name="description" content="YUTUCMS 广告联盟JS广告管理">
    <meta name="keywords" content="YUTUCMS, 广告管理, JS广告">
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

        /* 面包屑导航 */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            font-size: 0.9rem;
            color: var(--gray-color);
        }

        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb .separator {
            color: var(--gray-color);
        }

        .breadcrumb .current {
            color: var(--dark-color);
        }

        /* 页面标题 */
        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title i {
            color: var(--primary-color);
        }

        .page-subtitle {
            color: var(--gray-color);
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        /* 设置卡片 */
        .settings-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--light-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: var(--primary-color);
        }

        .card-body {
            padding: 25px;
        }

        /* 表单样式 */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }

        .form-textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.95rem;
            font-family: 'Courier New', monospace;
            resize: vertical;
            min-height: 200px;
            transition: all 0.3s;
            line-height: 1.5;
        }

        .form-textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(22, 93, 255, 0.1);
            outline: none;
        }

        .form-hint {
            font-size: 0.85rem;
            color: var(--gray-color);
            margin-top: 5px;
        }

        /* 按钮样式 */
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #0e4dff;
            box-shadow: 0 4px 12px rgba(22, 93, 255, 0.3);
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline:hover {
            background-color: rgba(22, 93, 255, 0.05);
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        /* 代码预览 */
        .code-preview {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 15px;
            margin-top: 15px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #495057;
            max-height: 300px;
            overflow-y: auto;
            line-height: 1.4;
        }

        .code-preview-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* 响应式设计 */
        @media (max-width: 992px) {
            .sidebar {
                left: -100%;
                width: 280px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.mobile-open {
                left: 0;
            }

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
            
            .sidebar .toggle-btn {
                display: none;
            }
        }

        @media (min-width: 993px) {
            .sidebar .toggle-btn {
                display: block;
            }
        }

        @media (max-width: 576px) {
            .header {
                padding: 0 15px;
            }

            .main-content {
                padding: 20px 15px;
            }

            .card-body {
                padding: 20px;
            }

            .user-name {
                display: none;
            }

            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- 侧边栏 -->
    <?php include('../Php/Admin/list.php');?>

    <!-- 头部 -->
    <?php include('../Php/Admin/header.php');?>

    <!-- 主内容区 -->
    <div class="main-content">
        <!-- 面包屑导航 -->
        <div class="breadcrumb">
            <a href="#"><i class="fa fa-home"></i> 首页</a>
            <span class="separator">/</span>
            <a href="#">广告设置</a>
            <span class="separator">/</span>
            <span class="current">广告联盟JS广告</span>
        </div>

        <!-- 页面标题 -->
        <h1 class="page-title">
            <i class="fa fa-code"></i> 广告联盟JS广告
        </h1>
        <p class="page-subtitle">管理第三方广告联盟的JavaScript广告代码</p>

        <!-- 设置表单 -->
        <div class="settings-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa fa-file-code-o"></i> JS广告代码配置
                </h2>
            </div>
            <div class="card-body">
                <?php
                // 读取现有的广告代码
                $adJsCode = '';
                if (file_exists("../JCSQL/Admin/Ad/AdminAdJs.php")) {
                    $adJsCode = file_get_contents("../JCSQL/Admin/Ad/AdminAdJs.php");
                }
                ?>

                <form method="post" class="settings-form">
                    <div class="form-group">
                        <label class="form-label">广告联盟JS代码</label>
                        <textarea name="AdJs" class="form-textarea" placeholder="请粘贴广告联盟提供的JavaScript代码"><?php echo htmlspecialchars($adJsCode); ?></textarea>
                        <div class="form-hint">可填写多个JS广告代码，代码将插入到网站页面中</div>
                        
                        <!-- 代码预览 -->
                        <div class="code-preview">
                            <div class="code-preview-title">
                                <i class="fa fa-eye"></i> 代码预览：
                            </div>
                            <pre><?php echo htmlspecialchars($adJsCode ?: '暂无广告代码'); ?></pre>
                        </div>
                    </div>

                    <!-- 提交按钮 -->
                    <div class="btn-group">
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> 保存设置
                        </button>
                        <button type="reset" class="btn btn-outline">
                            <i class="fa fa-undo"></i> 重置
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 使用说明 -->
        <div class="settings-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa fa-info-circle"></i> 使用说明
                </h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <h4>支持的广告联盟：</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid var(--primary-color);">
                            <h5 style="margin-bottom: 8px; color: var(--primary-color);">Google AdSense</h5>
                            <p style="color: var(--gray-color); font-size: 0.9rem; line-height: 1.4;">谷歌广告联盟，全球最大的广告平台</p>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid var(--success-color);">
                            <h5 style="margin-bottom: 8px; color: var(--success-color);">百度联盟</h5>
                            <p style="color: var(--gray-color); font-size: 0.9rem; line-height: 1.4;">百度广告联盟，国内主流广告平台</p>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid var(--warning-color);">
                            <h5 style="margin-bottom: 8px; color: var(--warning-color);">腾讯广告</h5>
                            <p style="color: var(--gray-color); font-size: 0.9rem; line-height: 1.4;">腾讯广点通，社交平台广告</p>
                        </div>
                    </div>
                    
                    <div class="info-alert" style="background: #fffbf0; border: 1px solid #ffe58f; color: #d46b08; padding: 15px; border-radius: 6px; margin-top: 20px;">
                        <strong><i class="fa fa-exclamation-triangle"></i> 注意事项：</strong>
                        <ul style="margin-top: 10px; margin-bottom: 0; padding-left: 20px;">
                            <li>请确保JS代码的正确性，错误的代码可能导致网站加载异常</li>
                            <li>多个广告代码可以同时使用，但可能会影响页面加载速度</li>
                            <li>保存后代码将立即生效，请及时检查广告是否正常显示</li>
                            <li>建议定期更新广告代码以获得最佳收益效果</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 移动端遮罩层 -->
    <div class="sidebar-overlay"></div>

    <script src="../Static/Admin/Js/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
     
        // 实时预览代码
        $('textarea[name="AdJs"]').on('input', function() {
            var code = $(this).val();
            $('.code-preview pre').text(code || '暂无广告代码');
        });

        // 表单提交确认
        $('form').submit(function() {
            var code = $('textarea[name="AdJs"]').val();
            if (!code.trim()) {
                if (!confirm('广告代码为空，确定要继续保存吗？')) {
                    return false;
                }
            }
            return confirm('确定要保存广告代码吗？保存后将立即生效。');
        });
    });
    </script>

    <?php
    // 保存逻辑
   $postAdJs = isset($_POST['AdJs']) ? $_POST['AdJs'] : '';
    if (isset($_POST['submit']) && isset($postAdJs)) {
        $file = fopen("../JCSQL/Admin/Ad/AdminAdJs.php","w");
        fwrite($file, $postAdJs);
        fclose($file);  
    ?>
    <script language="javascript"> 
        alert("恭喜修改成功！"); 
        window.location.href="?Php=Home/Ad/AdJs";
    </script> 
    <?php } ?>

    <?php include('../Php/Admin/footer.php');?>
</body>
</html>