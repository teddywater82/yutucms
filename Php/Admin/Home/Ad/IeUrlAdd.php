<?php
if (isset($ADMINKEY)) { }else{ exit('404');   }   
include('../Php/Admin/cookie.php');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加友情链接 - YUTUCMS管理中心</title>
    <meta name="description" content="YUTUCMS 添加友情链接">
    <meta name="keywords" content="YUTUCMS, 友情链接, 链接管理">
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

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control:focus {
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
            <a href="#">友情链接</a>
            <span class="separator">/</span>
            <a href="?Php=Home/Ad/IeUrl">友情链接设置</a>
            <span class="separator">/</span>
            <span class="current">添加链接</span>
        </div>

        <!-- 页面标题 -->
        <h1 class="page-title">
            <i class="fa fa-link"></i> 添加友情链接
        </h1>
        <p class="page-subtitle">添加新的友情链接信息</p>

        <!-- 设置表单 -->
        <div class="settings-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa fa-plus"></i> 链接信息
                </h2>
            </div>
            <div class="card-body">
                <form method="post" class="settings-form">
                    <div class="form-group">
                        <label class="form-label">友链排序</label>
                        <input type="text" class="form-control" name="IeUrlId" value="" placeholder="请输入友链排序编号">
                        <div class="form-hint">数字越小排序越靠前</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">友链标题</label>
                        <input type="text" class="form-control" name="IeUrlName" value="" placeholder="请输入友链标题">
                        <div class="form-hint">显示在网站上的友情链接名称</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">友链链接</label>
                        <input type="text" class="form-control" name="IeUrlWebUrl" value="" placeholder="请输入友链链接">
                        <div class="form-hint">完整的URL地址，如：https://example.com</div>
                    </div>

                    <!-- 提交按钮 -->
                    <div class="btn-group">
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fa fa-plus"></i> 添加链接
                        </button>
                        <a href="?Php=Home/Ad/IeUrl" class="btn btn-outline">
                            <i class="fa fa-arrow-left"></i> 返回列表
                        </a>
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
                    <i class="fa fa-info-circle"></i> 填写说明
                </h2>
            </div>
            <div class="card-body">
                <div style="background: #f0f7ff; border-left: 4px solid var(--primary-color); padding: 15px; border-radius: 4px;">
                    <h4 style="margin-bottom: 10px; color: var(--primary-color);">友情链接添加注意事项：</h4>
                    <ul style="color: var(--dark-color); line-height: 1.6;">
                        <li>友链排序：数字越小显示位置越靠前，建议使用10、20、30这样的间隔</li>
                        <li>友链标题：建议使用简洁明了的网站名称</li>
                        <li>友链链接：请确保URL地址完整且可访问</li>
                        <li>添加后链接将立即生效，请确保信息准确无误</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- 移动端遮罩层 -->
    <div class="sidebar-overlay"></div>

    <script src="../Static/Admin/Js/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
      
        // 表单验证
        $('form').submit(function() {
            var requiredFields = ['IeUrlName', 'IeUrlWebUrl'];
            var isValid = true;
            var errorMessage = '';
            
            requiredFields.forEach(function(field) {
                var value = $('[name="' + field + '"]').val();
                if (!value) {
                    isValid = false;
                    errorMessage = field + ' 字段不能为空';
                    return false;
                }
            });
            
            if (!isValid) {
                alert(errorMessage);
                return false;
            }
            
            // 验证URL格式
            var url = $('[name="IeUrlWebUrl"]').val();
            if (!isValidUrl(url)) {
                alert('请输入有效的URL地址，如：https://example.com');
                return false;
            }
            
            return confirm('确定要添加这个友情链接吗？');
        });

        // URL验证函数
        function isValidUrl(string) {
            try {
                new URL(string);
                return true;
            } catch (_) {
                return false;
            }
        }
    });
    </script>

    <?php
    // 保存逻辑
    if (isset($_POST['submit']) && isset($_POST['IeUrlId']) && isset($_POST['IeUrlName']) && isset($_POST['IeUrlWebUrl'])) {
        function post_input($data){
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        
        $AdminIeUrl = json_decode(file_get_contents("../JCSQL/Admin/Ad/AdminIeUrl.php"),true);
        $IeUrlId = post_input($_POST["IeUrlId"]);
        $IeUrlName = post_input($_POST["IeUrlName"]);
        $IeUrlWebUrl = post_input($_POST["IeUrlWebUrl"]);
        $IeUrlState = 'ok';
        
        // 验证字段
        if ($IeUrlName == null) { echo'<script language="javascript">alert("友链标题不可为空"); </script>';exit();}
        if ($IeUrlWebUrl == null) { echo'<script language="javascript">alert("友链链接不可为空"); </script>';exit();}
        
        include('../Php/Public/Mysql.php');	
        $AdminIeUrlMod['IeUrlId'] = $IeUrlId;
        $AdminIeUrlMod['IeUrlName'] = $IeUrlName;
        $AdminIeUrlMod['IeUrlWebUrl'] = $IeUrlWebUrl;
        $AdminIeUrlMod['IeUrlState'] = $IeUrlState;
        $UPDATE = INSERT($AdminIeUrl, $AdminIeUrlMod); 
        $file = fopen("../JCSQL/Admin/Ad/AdminIeUrl.php","w");
        fwrite($file,json_encode($UPDATE));
        fclose($file);  
    ?>
    <script language="javascript"> 
        alert("恭喜添加成功！"); 
        window.location.href="?Php=Home/Ad/IeUrl";
    </script> 
    <?php } ?>

    <?php include('../Php/Admin/footer.php');?>
</body>
</html>