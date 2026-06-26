<?php
if (isset($ADMINKEY)) { }else{ exit('404');   }   
include('../Php/Admin/cookie.php');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系统设置 - YUTUCMS管理中心</title>
    <meta name="description" content="YUTUCMS 系统设置">
    <meta name="keywords" content="YUTUCMS, 系统设置, 管理中心">
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

        .form-select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.95rem;
            background-color: white;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2386909C' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            appearance: none;
            transition: all 0.3s;
        }

        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(22, 93, 255, 0.1);
            outline: none;
        }

        .form-hint {
            font-size: 0.85rem;
            color: var(--gray-color);
            margin-top: 5px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .form-col {
            flex: 1;
        }

        /* 开关按钮样式 */
        .switch-container {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .switch-label {
            font-weight: 500;
            color: var(--dark-color);
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--primary-color);
        }

        input:checked + .slider:before {
            transform: translateX(24px);
        }

        .switch-status {
            font-size: 0.9rem;
            color: var(--gray-color);
        }

        .switch-status.active {
            color: var(--success-color);
            font-weight: 500;
        }

        /* 警告框样式 */
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .alert-warning {
            background-color: #fffbf0;
            border: 1px solid #ffe58f;
            color: #d46b08;
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

        /* 自定义弹窗样式 */
        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .custom-modal.show {
            display: flex;
        }

        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(3px);
        }

        .modal-content {
            position: relative;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 450px;
            overflow: hidden;
            animation: modalSlideIn 0.3s ease-out;
            transform: translateY(20px);
            opacity: 0;
        }

        .custom-modal.show .modal-content {
            transform: translateY(0);
            opacity: 1;
        }

        .modal-header {
            padding: 20px 25px 15px;
            border-bottom: 1px solid var(--light-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--gray-color);
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .modal-close:hover {
            background: var(--light-color);
            color: var(--danger-color);
        }

        .modal-body {
            padding: 25px;
        }

        .modal-body p {
            margin: 0;
            font-size: 1rem;
            line-height: 1.5;
            color: var(--dark-color);
        }

        .modal-footer {
            padding: 15px 25px 25px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .modal-footer .btn {
            min-width: 80px;
        }

        /* 动画效果 */
        @keyframes modalSlideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
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

            .form-row {
                flex-direction: column;
                gap: 0;
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
            
            .modal-content {
                width: 95%;
                margin: 20px;
            }
            
            .modal-header,
            .modal-body,
            .modal-footer {
                padding: 15px 20px;
            }
            
            .modal-footer {
                flex-direction: column-reverse;
            }
            
            .modal-footer .btn {
                width: 100%;
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
            <span class="current">基本设置</span>
        </div>

        <!-- 页面标题 -->
        <h1 class="page-title">
            <i class="fa fa-cogs"></i> 基本设置
        </h1>
        <p class="page-subtitle">配置网站基本参数和功能设置</p>

        <!-- 设置表单 -->
        <div class="settings-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa fa-sliders"></i> 系统设置
                </h2>
            </div>
            <div class="card-body">
                <?php
                /***读取数据库中的数据并且给予变量中***/
                $AdminBasic = json_decode(file_get_contents("../JCSQL/Admin/Basic/AdminBasic.php"),true);

                $WebMobanPC		=	$AdminBasic['WebMobanPC'];
                $WebMobanWAP	=	$AdminBasic['WebMobanWAP'];
                $WebTitle		=	$AdminBasic['WebTitle'];
                $WebKeywords	=	$AdminBasic['WebKeywords'];
                $WebDescription	=	$AdminBasic['WebDescription'];
                $WebGongao	    =	$AdminBasic['WebGongao'];
                $WebGongaoOpen	=	$AdminBasic['WebGongaoOpen'];
                $WebLogo		=	$AdminBasic['WebLogo'];
                $WebEmail		=	$AdminBasic['WebEmail'];
                
                // 修正公告状态逻辑：2=关闭，其他值=开启
                $isGongaoOpen = ($WebGongaoOpen != 2);
                
                // 读取防屏蔽设置
                $AntiBlockOpen = isset($AdminBasic['AntiBlockOpen']) ? $AdminBasic['AntiBlockOpen'] : '0';
                $AntiBlockDomain = isset($AdminBasic['AntiBlockDomain']) ? $AdminBasic['AntiBlockDomain'] : '';
                ?>
                <?php 
                /***检测模板文件夹里的模板名称并且给予输出***/
                $Template=NULL;
                $Templates = scandir("../Template/");
                foreach ($Templates as $name) {
                    if(strpos($name,'.') !==false || strpos($name,'-') !==false ){
                    }else{
                        $Template .='<option value="'.$name.'">'.$name.'</option>';
                    }
                }
                ?>
               <?php
/***修改系统设置数据库***/
if (isset($_POST['submit'])) {
    function post_input($data){
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    $Basic = array();
    
    // 处理必填字段
    $Basic['WebMobanPC'] = isset($_POST['WebMobanPC']) ? post_input($_POST['WebMobanPC']) : $WebMobanPC;
    $Basic['WebMobanWAP'] = isset($_POST['WebMobanWAP']) ? post_input($_POST['WebMobanWAP']) : $WebMobanWAP;
    $Basic['WebTitle'] = isset($_POST['WebTitle']) ? post_input($_POST['WebTitle']) : $WebTitle;
    $Basic['WebKeywords'] = isset($_POST['WebKeywords']) ? post_input($_POST['WebKeywords']) : $WebKeywords;
    $Basic['WebGongao'] = isset($_POST['WebGongao']) ? post_input($_POST['WebGongao']) : $WebGongao;
    $Basic['WebDescription'] = isset($_POST['WebDescription']) ? post_input($_POST['WebDescription']) : $WebDescription;
    $Basic['WebLogo'] = isset($_POST['WebLogo']) ? post_input($_POST['WebLogo']) : $WebLogo;
    $Basic['WebEmail'] = isset($_POST['WebEmail']) ? post_input($_POST['WebEmail']) : $WebEmail;

    // 修正公告状态保存逻辑
    if (isset($_POST['WebGongaoOpen']) && $_POST['WebGongaoOpen'] == '1') {
        $Basic['WebGongaoOpen'] = '1'; // 开启状态
    } else {
        $Basic['WebGongaoOpen'] = '2'; // 关闭状态
    }

    // 保存防屏蔽设置
    if (isset($_POST['AntiBlockOpen']) && $_POST['AntiBlockOpen'] == '1') {
        $Basic['AntiBlockOpen'] = '1';
    } else {
        $Basic['AntiBlockOpen'] = '0';
    }
    
    // 保存防屏蔽域名
    $Basic['AntiBlockDomain'] = isset($_POST['AntiBlockDomain']) ? post_input($_POST['AntiBlockDomain']) : '';

    // 写入文件
    $file = fopen("../JCSQL/Admin/Basic/AdminBasic.php","w");
    if ($file) {
        fwrite($file, json_encode($Basic));
        fclose($file);
        
        // 立即更新当前页面的变量，避免刷新后还是旧数据
        $WebGongaoOpen = $Basic['WebGongaoOpen'];
        $isGongaoOpen = ($WebGongaoOpen != 2);
        $AntiBlockOpen = $Basic['AntiBlockOpen'];
        $AntiBlockDomain = $Basic['AntiBlockDomain'];
        
        echo '<script>console.log("数据保存成功，公告状态:", "' . $Basic['WebGongaoOpen'] . '");</script>';
    }
}
?>

                <form method="post" class="settings-form">
                    <!-- 防屏蔽设置 -->
                   <div class="form-group">
    <div class="switch-container">
        <label class="switch-label">防屏蔽验证</label>
        <label class="switch">
            <input type="checkbox" id="AntiBlockOpen" name="AntiBlockOpen" value="1" <?php echo $AntiBlockOpen == '1' ? 'checked' : ''; ?>>
            <span class="slider"></span>
        </label>
        <span class="switch-status <?php echo $AntiBlockOpen == '1' ? 'active' : ''; ?>">
            <?php echo $AntiBlockOpen == '1' ? '已开启' : '已关闭'; ?>
        </span>
    </div>
    
    <!-- 域名输入框 - 只在开启时显示 -->
   <div id="domain-input-container" style="<?php echo $AntiBlockOpen == '1' ? '' : 'display: none;'; ?> margin-top: 15px;">
    <textarea class="form-control" name="AntiBlockDomain" placeholder="请输入内容" rows="4" style="resize: vertical; min-height: 100px;"><?php echo $AntiBlockDomain; ?></textarea>
    <div class="form-hint">支持html代码</div>
</div>
    
    <div class="alert alert-warning">
        <strong>注意：</strong>开启后用户访问网站需要先通过滑动验证，但会影响搜索引擎收录，请谨慎开启！
    </div>
</div>

                    <!-- 模板设置 -->
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">PC模板选择</label>
                                <select name="WebMobanPC" class="form-select">
                                    <option value="<?php echo $WebMobanPC;?>"><?php echo $WebMobanPC;?></option>
                                    <?php echo $Template;?>
                                </select>
                                <div class="form-hint">选择PC端使用的网站模板</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">WAP模板选择</label>
                                <select name="WebMobanWAP" class="form-select">
                                    <option value="<?php echo $WebMobanWAP;?>"><?php echo $WebMobanWAP;?></option>
                                    <?php echo $Template;?>
                                </select>
                                <div class="form-hint">选择移动端使用的网站模板</div>
                            </div>
                        </div>
                    </div>

                    <!-- 网站基本信息 -->
                    <div class="form-group">
                        <label class="form-label">网站名称</label>
                        <input type="text" class="form-control" value="<?php echo $WebTitle;?>" name="WebTitle" placeholder="输入网站名称">
                    </div>

                    <div class="form-group">
                        <label class="form-label">网站关键字</label>
                        <input type="text" class="form-control" value="<?php echo $WebKeywords;?>" name="WebKeywords" placeholder="输入网站关键字，用逗号分隔">
                    </div>

                    <div class="form-group">
                        <label class="form-label">网站描述</label>
                        <input type="text" class="form-control" value="<?php echo $WebDescription;?>" name="WebDescription" placeholder="输入网站描述">
                    </div>

                    <!-- 公告设置 -->
                    <div class="form-group">
                        <label class="form-label">公告内容</label>
                        <input type="text" class="form-control" value="<?php echo $WebGongao;?>" name="WebGongao" placeholder="输入公告内容">
                        <div class="form-hint">网站首页显示的公告信息</div>
                    </div>

                    <div class="form-group">
                        <div class="switch-container">
                            <label class="switch-label">公告状态</label>
                            <label class="switch">
                                <input type="checkbox" id="WebGongaoOpen" name="WebGongaoOpen" value="1" <?php echo $isGongaoOpen ? 'checked' : ''; ?>>
                                <span class="slider"></span>
                            </label>
                            <span class="switch-status <?php echo $isGongaoOpen ? 'active' : ''; ?>">
                                <?php echo $isGongaoOpen ? '已开启' : '已关闭'; ?>
                            </span>
                        </div>
                    </div>

                    <!-- 其他设置 -->
                    <div class="form-group">
                        <label class="form-label">网站Logo URL</label>
                        <input type="text" class="form-control" value="<?php echo $WebLogo;?>" name="WebLogo" placeholder="输入网站Logo的URL地址">
                    </div>

                    <div class="form-group">
                        <label class="form-label">广告邮箱</label>
                        <input type="email" class="form-control" value="<?php echo $WebEmail;?>" name="WebEmail" placeholder="输入广告合作邮箱">
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
    </div>

    <!-- 自定义弹窗 -->
    <div id="custom-modal" class="custom-modal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">系统提示</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <p id="modal-message">这里是消息内容</p>
            </div>
            <div class="modal-footer">
                <button id="modal-cancel" class="btn btn-outline">取消</button>
                <button id="modal-confirm" class="btn btn-primary">确定</button>
            </div>
        </div>
    </div>

    <!-- 移动端遮罩层 -->
    <div class="sidebar-overlay"></div>

    <script src="../Static/Admin/Js/jquery.min.js"></script>
    <script>
$(document).ready(function() {
    // 创建自定义弹窗对象
    const customModal = {
        modal: $('#custom-modal'),
        message: $('#modal-message'),
        confirmBtn: $('#modal-confirm'),
        cancelBtn: $('#modal-cancel'),
        closeBtn: $('.modal-close'),
        overlay: $('.modal-overlay'),
        
        // 显示弹窗
        show: function(message, isConfirm = false) {
            this.message.text(message);
            
            if (isConfirm) {
                this.cancelBtn.show();
                this.confirmBtn.text('确定');
            } else {
                this.cancelBtn.hide();
                this.confirmBtn.text('确定');
            }
            
            this.modal.addClass('show');
            $('body').css('overflow', 'hidden');
        },
        
        // 隐藏弹窗
        hide: function() {
            this.modal.removeClass('show');
            $('body').css('overflow', '');
        },
        
        // 初始化
        init: function() {
            const self = this;
            
            // 关闭弹窗事件
            this.closeBtn.on('click', function() {
                self.hide();
                if (self.rejectCallback) self.rejectCallback();
            });
            
            this.overlay.on('click', function() {
                self.hide();
                if (self.rejectCallback) self.rejectCallback();
            });
            
            // 确认按钮事件
            this.confirmBtn.on('click', function() {
                self.hide();
                if (self.resolveCallback) self.resolveCallback();
            });
            
            // 取消按钮事件
            this.cancelBtn.on('click', function() {
                self.hide();
                if (self.rejectCallback) self.rejectCallback();
            });
            
            // ESC键关闭
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27 && self.modal.hasClass('show')) {
                    self.hide();
                    if (self.rejectCallback) self.rejectCallback();
                }
            });
        }
    };
    
    // 初始化弹窗
    customModal.init();
    
    // 替换原生alert函数
    window.alert = function(message) {
        return new Promise((resolve) => {
            customModal.resolveCallback = resolve;
            customModal.show(message, false);
        });
    };
    
    // 替换原生confirm函数
    window.confirm = function(message) {
        return new Promise((resolve, reject) => {
            customModal.resolveCallback = resolve;
            customModal.rejectCallback = reject;
            customModal.show(message, true);
        });
    };
    
    // 防屏蔽开关状态更新
    if ($('#AntiBlockOpen').length) {
        $('#AntiBlockOpen').change(function() {
            var isChecked = $(this).is(':checked');
            var statusText = isChecked ? '已开启' : '已关闭';
            $(this).closest('.switch-container').find('.switch-status').text(statusText).toggleClass('active', isChecked);
            
            // 显示/隐藏域名输入框
            if (isChecked) {
                $('#domain-input-container').slideDown(300);
                
                // 使用自定义confirm弹窗
                confirm('开启防屏蔽验证后，用户访问网站需要先通过滑动验证，但这会影响搜索引擎收录，确定要开启吗？')
                    .then(() => {
                        // 用户点击确定，不做任何操作，保持开启状态
                    })
                    .catch(() => {
                        // 用户点击取消，恢复关闭状态
                        $(this).prop('checked', false);
                        $(this).closest('.switch-container').find('.switch-status').text('已关闭').removeClass('active');
                        $('#domain-input-container').slideUp(300);
                    });
            } else {
                $('#domain-input-container').slideUp(300);
            }
        });
    }
    
    // 公告开关状态更新
    if ($('#WebGongaoOpen').length) {
        $('#WebGongaoOpen').change(function() {
            var isChecked = $(this).is(':checked');
            var statusText = isChecked ? '已开启' : '已关闭';
            $(this).closest('.switch-container').find('.switch-status').text(statusText).toggleClass('active', isChecked);
        });
    }
    
    // 表单提交成功提示
    <?php if (isset($_POST['submit'])): ?>
    setTimeout(function() {
        alert("恭喜修改成功！").then(() => {
            window.location.href = "?Php=Home/Basic/Basicsetup";
        });
    }, 100);
    <?php endif; ?>
    
    // 调试信息
    console.log("当前公告状态:", "<?php echo $WebGongaoOpen; ?>", "显示状态:", "<?php echo $isGongaoOpen ? '开启' : '关闭'; ?>");
});
    </script>
    <?php include('../Php/Admin/footer.php');?>
</body>
</html>