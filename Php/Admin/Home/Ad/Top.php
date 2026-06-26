<?php
if (isset($ADMINKEY)) { }else{ exit('404');   }   
include('../Php/Admin/cookie.php');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>头部横幅广告 - YUTUCMS管理中心</title>
    <meta name="description" content="YUTUCMS 头部横幅广告管理">
    <meta name="keywords" content="YUTUCMS, 广告管理, 横幅广告">
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

        /* 按钮样式 */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #0e4dff;
            box-shadow: 0 4px 12px rgba(22, 93, 255, 0.3);
        }

        .btn-success {
            background-color: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background-color: #46a51a;
            box-shadow: 0 4px 12px rgba(82, 196, 26, 0.3);
        }

        .btn-warning {
            background-color: var(--warning-color);
            color: white;
        }

        .btn-warning:hover {
            background-color: #e89c13;
            box-shadow: 0 4px 12px rgba(250, 173, 20, 0.3);
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #ff3a3c;
            box-shadow: 0 4px 12px rgba(255, 77, 79, 0.3);
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline:hover {
            background-color: rgba(22, 93, 255, 0.05);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        /* 表格样式 */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .data-table th {
            background: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: var(--dark-color);
            border-bottom: 1px solid #e9ecef;
        }

        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .data-table tr:hover {
            background: #f8f9fa;
        }

        .data-table .actions {
            display: flex;
            gap: 8px;
        }

        .status-active {
            color: var(--success-color);
            font-weight: 500;
        }

        .status-expired {
            color: var(--danger-color);
            font-weight: 500;
        }

        .image-preview {
            max-width: 80px;
            max-height: 40px;
            border-radius: 4px;
            object-fit: cover;
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

            .data-table {
                display: block;
                overflow-x: auto;
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

            .data-table .actions {
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
            <span class="current">头部横幅广告</span>
        </div>

        <!-- 页面标题 -->
        <h1 class="page-title">
            <i class="fa fa-ad"></i> 头部横幅广告管理
        </h1>
        <p class="page-subtitle">管理网站头部横幅广告内容和状态</p>

        <!-- 操作工具栏 -->
        <div class="settings-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa fa-cog"></i> 广告管理
                </h2>
            </div>
            <div class="card-body">
                <div class="btn-group">
                    <a href="?Php=Home/Ad/TopAdd" class="btn btn-success">
                        <i class="fa fa-plus"></i> 新增广告
                    </a>
                </div>

                <!-- 广告列表表格 -->
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>广告排序</th>
                            <th>广告链接</th>
                            <th>广告图片</th>
                            <th>图片尺寸</th>
                            <th>状态</th>
                            <th>联系方式</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $AdminTop = json_decode(file_get_contents("../JCSQL/Admin/Ad/AdminTop.php"),true);
                        array_multisort(array_column($AdminTop,'TopId'),SORT_DESC,$AdminTop);
                        $count = count($AdminTop);

                        for ($x=0; $x<=$count-1; $x++) {
                            $Top = $AdminTop[$x];	
                            $TopId = $Top['TopId'];
                            $TopWebUrl = $Top['TopWebUrl'];
                            $TopRemarks = $Top['TopRemarks'];
                            $TopPicUrl = $Top['TopPicUrl'];
                            $TopState = $Top['TopState'];
                            $TopPicUrlWidth = $Top['TopPicUrlWidth'];
                            $TopPicUrlHeight = $Top['TopPicUrlHeight'];

                            date_default_timezone_set("Asia/Shanghai");
                            $time = date("Ymd");
                            
                            if($TopState < $time){
                                $TopStateName = '<span class="status-expired">已到期</span>';
                            } else {
                                $TopStateName = '<span class="status-active">展示中</span>';
                            }
                        ?>
                        <tr>
                            <td><?php echo $TopId; ?></td>
                            <td style="max-width: 200px; word-break: break-all;">
                                <a href="<?php echo $TopWebUrl; ?>" target="_blank" title="<?php echo $TopWebUrl; ?>">
                                    <?php echo strlen($TopWebUrl) > 30 ? substr($TopWebUrl, 0, 30).'...' : $TopWebUrl; ?>
                                </a>
                            </td>
                            <td>
                                <?php if($TopPicUrl): ?>
                                    <a href="<?php echo $TopPicUrl; ?>" target="_blank" class="btn btn-outline btn-sm">
                                        <i class="fa fa-eye"></i> 预览
                                    </a>
                                <?php else: ?>
                                    <span style="color: var(--gray-color);">无图片</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $TopPicUrlWidth; ?> × <?php echo $TopPicUrlHeight; ?></td>
                            <td><?php echo $TopStateName; ?></td>
                            <td style="max-width: 150px; word-break: break-all;"><?php echo $TopRemarks; ?></td>
                            <td>
                                <div class="actions">
                                    <a href="?Php=Home/Ad/TopMod&Id=<?php echo $x; ?>" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> 编辑
                                    </a>
                                    <a href="?Php=Home/Ad/Top&Id=<?php echo $x; ?>" class="btn btn-danger btn-sm" onclick="return confirm('确定要删除这个广告吗？')">
                                        <i class="fa fa-trash"></i> 删除
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <?php if($count == 0): ?>
                <div style="text-align: center; padding: 40px; color: var(--gray-color);">
                    <i class="fa fa-inbox" style="font-size: 3rem; margin-bottom: 15px;"></i>
                    <p>暂无广告数据</p>
                    <a href="?Php=Home/Ad/TopAdd" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fa fa-plus"></i> 添加第一个广告
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- 移动端遮罩层 -->
    <div class="sidebar-overlay"></div>

    <script src="../Static/Admin/Js/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
      

        // 删除确认
        $('.btn-danger').click(function(e) {
            if (!confirm('确定要删除这个广告吗？此操作不可恢复！')) {
                e.preventDefault();
                return false;
            }
        });
    });
    </script>

    <?php
    // 删除逻辑
    if (isset($_GET['Php']) && isset($_GET['Id'])) {	
        function post_input($data){
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }	
        $Php = post_input($_GET["Php"]);	
        $Id = post_input($_GET["Id"]);	
        
        if($Php == "Home/Ad/Top" && $Id !== NULL){
            $AdminTop = json_decode(file_get_contents("../JCSQL/Admin/Ad/AdminTop.php"),true);
            array_multisort(array_column($AdminTop,'TopId'),SORT_DESC,$AdminTop);
            include('../Php/Public/Mysql.php');
            $file = fopen("../JCSQL/Admin/Ad/AdminTop.php","w");
            fwrite($file,json_encode(DELETE($AdminTop,$Id)));
            fclose($file);  
    ?>
    <script language="javascript"> 
        alert("恭喜删除成功！"); 
        window.location.href="?Php=Home/Ad/Top";
    </script> 
    <?php
        }
    }	
    ?>

    <?php include('../Php/Admin/footer.php');?>
</body>
</html>