<?php
if (isset($ADMINKEY)) { }else{ exit('404');   }   
include('../Php/Admin/cookie.php');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加广告 - YUTUCMS管理中心</title>
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

        /* 面包屑导航 */
        .breadcrumb {
            background: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
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
            font-weight: 500;
        }

        /* 表单容器 */
        .form-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--light-color);
        }

        .form-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-title i {
            color: var(--primary-color);
        }

        /* 表单样式 */
        .form-group {
            margin-bottom: 25px;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
        }

        .form-label {
            width: 200px;
            padding: 10px 15px;
            font-weight: 500;
            color: var(--dark-color);
            text-align: right;
        }

        .form-control {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: all 0.3s;
            min-width: 300px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(22, 93, 255, 0.1);
            outline: none;
        }

        .form-hint {
            width: 100%;
            margin-top: 8px;
            padding-left: 215px;
            font-size: 0.85rem;
            color: var(--danger-color);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            padding-top: 20px;
            border-top: 1px solid var(--light-color);
            margin-top: 30px;
        }

        .btn {
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
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
            
            /* 移动端表单调整 */
            .form-label {
                width: 100%;
                text-align: left;
                padding: 0 0 10px 0;
            }
            
            .form-control {
                min-width: 100%;
            }
            
            .form-hint {
                padding-left: 0;
            }
        }

        /* 桌面端显示折叠按钮 */
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
            
            .form-container {
                padding: 20px;
            }
            
            .breadcrumb {
                padding: 12px 15px;
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
            <a href="#">详情与内容横幅广告</a>
            <span class="separator">/</span>
            <span class="current">添加</span>
        </div>

        <!-- 表单容器 -->
        <div class="form-container">
            <div class="form-header">
                <h2 class="form-title">
                    <i class="fa fa-plus-circle"></i> 添加广告
                </h2>
            </div>

            <form method="post" class="am-form am-form-horizontal">
                <div class="form-group">
                    <label for="VideoId" class="form-label">广告排序</label>
                    <input type="text" name="VideoId" id="VideoId" class="form-control" value="" placeholder="广告排序">
                </div>
                
                <div class="form-group">
                    <label for="VideoWebUrl" class="form-label">广告链接URL</label>
                    <input type="text" name="VideoWebUrl" id="VideoWebUrl" class="form-control" value="" placeholder="广告链接URL">
                </div>
                
                <div class="form-group">
                    <label for="VideoPicUrl" class="form-label">广告图片URL</label>
                    <input type="text" name="VideoPicUrl" id="VideoPicUrl" class="form-control" value="" placeholder="广告图片">
                </div>
                
                <div class="form-group">
                    <label for="VideoPicUrlWidth" class="form-label">广告图片宽度</label>
                    <input type="text" name="VideoPicUrlWidth" id="VideoPicUrlWidth" class="form-control" value="100%" placeholder="广告图片宽度">
                    <div class="form-hint">说明：尺寸有百分比与px格式的，100%代表自适应宽度，默认100%</div>
                </div>
                
                <div class="form-group">
                    <label for="VideoPicUrlHeight" class="form-label">广告图片高度</label>
                    <input type="text" name="VideoPicUrlHeight" id="VideoPicUrlHeight" class="form-control" value="100px" placeholder="广告图片高度">
                    <div class="form-hint">说明：尺寸有百分比与px格式的，100%代表自适应高度，默认100px</div>
                </div>
                
                <div class="form-group">
                    <label for="VideoState" class="form-label">广告到期时间</label>
                    <input type="text" name="VideoState" id="VideoState" class="form-control" value="" placeholder="广告到期时间如20190627">
                    <div class="form-hint">说明：20190627代表的就是2019年6月27日，请按照格式填写</div>
                </div>
                
                <div class="form-group">
                    <label for="VideoRemarks" class="form-label">广告费/联系方式</label>
                    <input type="text" name="VideoRemarks" id="VideoRemarks" class="form-control" value="" placeholder="如55元,QQ:123457">
                    <div class="form-hint">说明：最好按照官方格式来写，格式：【1000元，邮箱：123@qq.com】【1000元，QQ：123456】</div>
                </div>
                
                <div class="form-actions">
                    <button name="submit" type="submit" class="btn btn-primary">
                        <i class="fa fa-check"></i> 添加
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php
    if (isset($_POST['submit']) && isset($_POST['VideoId']) && isset($_POST['VideoWebUrl']) && isset($_POST['VideoRemarks'])  && isset($_POST['VideoPicUrl'])  && isset($_POST['VideoState'])&& isset($_POST['VideoPicUrlWidth'])&& isset($_POST['VideoPicUrlHeight'])) {
        function post_input($data){
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        
        $AdminVideo = json_decode(file_get_contents("../JCSQL/Admin/Ad/AdminVideo.php"),true);
        $VideoId = post_input($_POST["VideoId"]);//广告排序
        $VideoWebUrl = post_input($_POST["VideoWebUrl"]);//广告链接
        $VideoRemarks = post_input($_POST["VideoRemarks"]);//广告备注
        $VideoPicUrl = post_input($_POST["VideoPicUrl"]);//广告图片
        $VideoState = post_input($_POST["VideoState"]);//到期时间
        $VideoPicUrlWidth = post_input($_POST["VideoPicUrlWidth"]);//广告图片宽度
        $VideoPicUrlHeight = post_input($_POST["VideoPicUrlHeight"]);//广告图片高度
        
        if ($VideoWebUrl ==null) { 
            echo'<script language="javascript">alert("广告链接URL不可为空"); </script>';
            exit();
        }
        if ($VideoPicUrl ==null) { 
            echo'<script language="javascript">alert("广告图片不可为空"); </script>';
            exit();
        }
        if ($VideoPicUrlWidth ==null) { 
            echo'<script language="javascript">alert("广告图片宽度不可为空"); </script>';
            exit();
        }
        if ($VideoPicUrlHeight ==null) { 
            echo'<script language="javascript">alert("广告图片高度不可为空"); </script>';
            exit();
        }
        if ($VideoRemarks ==null) { 
            echo'<script language="javascript">alert("广告费/联系方式不可为空"); </script>';
            exit();
        }	
        if ($VideoState ==null) { 
            echo'<script language="javascript">alert("广告到期时间不可为空"); </script>';
            exit();
        }	
        
        include('../Php/Public/Mysql.php');	
        $AdminVideoMod['VideoId'] = $VideoId;
        $AdminVideoMod['VideoWebUrl'] = $VideoWebUrl;
        $AdminVideoMod['VideoRemarks'] = $VideoRemarks;
        $AdminVideoMod['VideoPicUrl'] = $VideoPicUrl;
        $AdminVideoMod['VideoState'] = $VideoState;
        $AdminVideoMod['VideoPicUrlWidth'] = $VideoPicUrlWidth;
        $AdminVideoMod['VideoPicUrlHeight'] = $VideoPicUrlHeight;	
        $UPDATE=INSERT($AdminVideo,$AdminVideoMod); 
        $file = fopen("../JCSQL/Admin/Ad/AdminVideo.php","w");
        fwrite($file,json_encode($UPDATE));
        fclose($file);  
    ?>
    <script language="javascript"> 
        alert("恭喜添加成功！"); 
        window.location.href="?Php=Home/Ad/Video";
    </script> 
    <?php
    }
    ?>	

    <?php include('../Php/Admin/footer.php');?>
    
    <script src="../Static/Admin/Js/jquery.min.js"></script>
</body>
</html>