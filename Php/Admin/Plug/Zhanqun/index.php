<?php
if (isset($ADMINKEY)) { }else{ exit('404');   }   
include('../Php/Admin/cookie.php');?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>站群中心 - YUTUCMS管理中心</title>
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

        /* 页面标题和面包屑 */
        .page-header {
            margin-bottom: 30px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: var(--gray-color);
        }

        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb .divider {
            color: var(--gray-color);
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 10px;
        }

        .page-subtitle {
            color: var(--gray-color);
            font-size: 1rem;
        }

        /* 内容卡片 */
        .content-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--light-color);
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: var(--primary-color);
        }

        .card-actions {
            display: flex;
            gap: 10px;
        }

        /* 按钮样式 */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: #0e4cd3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(22, 93, 255, 0.3);
        }

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #389e0d;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(82, 196, 26, 0.3);
        }

        .btn-warning {
            background: var(--warning-color);
            color: white;
        }

        .btn-warning:hover {
            background: #d48806;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(250, 173, 20, 0.3);
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #d9363e;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 77, 79, 0.3);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--gray-color);
            color: var(--dark-color);
        }

        .btn-outline:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        /* 表格样式 */
        .table-container {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .data-table th {
            background: var(--light-color);
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: var(--dark-color);
            border-bottom: 1px solid #e1e5e9;
        }

        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e1e5e9;
            vertical-align: middle;
        }

        .data-table tr:hover {
            background: #f8f9fa;
        }

        /* 域名链接样式 */
        .domain-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .domain-link:hover {
            color: #0e4cd3;
            text-decoration: underline;
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

        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .card-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .data-table {
                font-size: 0.8rem;
            }

            .data-table th,
            .data-table td {
                padding: 8px 10px;
            }
        }

        @media (max-width: 576px) {
            .header {
                padding: 0 15px;
            }

            .main-content {
                padding: 20px 15px;
            }

            .content-card {
                padding: 15px;
            }

            .btn {
                padding: 8px 12px;
                font-size: 0.8rem;
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
        <!-- 页面标题 -->
        <div class="page-header">
            <div class="breadcrumb">
                <a href="#"><i class="fa fa-home"></i> 首页</a>
                <span class="divider">/</span>
                <span>扩展管理</span>
                <span class="divider">/</span>
                <span class="active">站群中心</span>
            </div>
            <h1 class="page-title">站群中心</h1>
            <p class="page-subtitle">管理多个网站域名和配置</p>
        </div>

        <!-- 站群列表 -->
        <div class="content-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa fa-sitemap"></i>
                    站点列表
                </h2>
                <div class="card-actions">
                    <a href="?Php=Plug/Zhanqun/add" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        添加域名
                    </a>
                </div>
            </div>

            <!-- 站群表格 -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 200px;">域名</th>
                            <th style="width: 150px;">名称</th>
                            <th style="width: 200px;">关键字</th>
                            <th style="width: 100px;">PC模板</th>
                            <th style="width: 100px;">移动模板</th>
                            <th style="width: 150px;">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $Zhanqun = json_decode(file_get_contents("../JCSQL/Admin/Plug/Zhanqun/index.php"),true);
                        $count = count($Zhanqun);

                        for ($x=0; $x<=$count-1; $x++) {
                            $Zhanquns = $Zhanqun[$x];    
                            $WebDomain = $Zhanquns['WebDomain'];
                            $WebTitle = $Zhanquns['WebTitle'];
                            $WebKeywords = $Zhanquns['WebKeywords'];
                            $WebDescription = $Zhanquns['WebDescription'];
                            $WebMobanPC = $Zhanquns['WebMobanPC'];
                            $WebMobanWAP = $Zhanquns['WebMobanWAP'];
                            $WebLogo = $Zhanquns['WebLogo'];
                            $WebEmail = $Zhanquns['WebEmail'];
                        ?>
                        <tr>
                            <td>
                                <a href="http://<?php echo $WebDomain ?>" target="_blank" class="domain-link">
                                    <i class="fa fa-external-link"></i>
                                    <?php echo $WebDomain ?>
                                </a>
                            </td>
                            <td>
                                <span title="<?php echo $WebTitle ?>">
                                    <?php echo mb_strlen($WebTitle) > 15 ? mb_substr($WebTitle, 0, 15) . '...' : $WebTitle; ?>
                                </span>
                            </td>
                            <td>
                                <span title="<?php echo $WebKeywords ?>">
                                    <?php echo mb_strlen($WebKeywords) > 20 ? mb_substr($WebKeywords, 0, 20) . '...' : $WebKeywords; ?>
                                </span>
                            </td>
                            <td>
                                <span class="template-badge"><?php echo $WebMobanPC ?: '默认'; ?></span>
                            </td>
                            <td>
                                <span class="template-badge"><?php echo $WebMobanWAP ?: '默认'; ?></span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                    <a href="?Php=Plug/Zhanqun/mod&Id=<?php echo $x?>" class="btn btn-outline btn-sm">
                                        <i class="fa fa-edit"></i>
                                        设置
                                    </a>
                                    <a href="?Php=Plug/Zhanqun/index&Id=<?php echo $x?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('确定要删除这个站点吗？')">
                                        <i class="fa fa-trash"></i>
                                        删除
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>

                        <?php if ($count == 0): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--gray-color);">
                                <i class="fa fa-inbox" style="font-size: 3rem; margin-bottom: 15px; display: block; opacity: 0.5;"></i>
                                <p>暂无站点数据</p>
                                <a href="?Php=Plug/Zhanqun/add" class="btn btn-primary" style="margin-top: 15px;">
                                    <i class="fa fa-plus"></i>
                                    添加第一个站点
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- 统计信息 -->
            <?php if ($count > 0): ?>
            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid var(--light-color);">
                <div style="display: flex; align-items: center; gap: 15px; color: var(--gray-color); font-size: 0.9rem;">
                    <i class="fa fa-info-circle"></i>
                    <span>共 <strong style="color: var(--primary-color);"><?php echo $count; ?></strong> 个站点</span>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="../Static/Admin/Js/jquery.min.js"></script>
    <?php include('../Php/Admin/footer.php');?>
</body>
</html>

<?php
// 处理删除操作
if (isset($_GET['Php']) && isset($_GET['Id'])) {
    function post_input($data){$data = stripslashes($data);$data = htmlspecialchars($data);return $data;}    
    $Php = post_input($_GET["Php"]);    
    $Id = post_input($_GET["Id"]);    
    if($Php =="Plug/Zhanqun/index" || $Id !== NULL ){
        $AdminTop = json_decode(file_get_contents("../JCSQL/Admin/Plug/Zhanqun/index.php"),true);
        include('../Php/Public/Mysql.php');    
        $file = fopen("../JCSQL/Admin/Plug/Zhanqun/index.php","w");
        fwrite($file,json_encode(DELETE($AdminTop,$Id)));
        fclose($file);  
?>
    <script language="javascript"> 
        alert("恭喜删除成功！"); 
        window.location.href="?Php=Plug/Zhanqun/index";
    </script> 
<?php
    }
}
?>