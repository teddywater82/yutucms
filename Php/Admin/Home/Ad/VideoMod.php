<?php
if (isset($ADMINKEY)) { }else{ exit('404');   }   
include('../Php/Admin/cookie.php');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改广告 - YUTUCMS管理中心</title>
    <meta name="description" content="YUTUCMS 修改播放横幅广告">
    <meta name="keywords" content="YUTUCMS, 广告管理, 修改广告">
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .form-col {
            display: flex;
            flex-direction: column;
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

        /* 图片预览 */
        .image-preview {
            margin-top: 10px;
            padding: 15px;
            border: 1px dashed #ddd;
            border-radius: 6px;
            text-align: center;
            background: #f8f9fa;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 4px;
        }

        .image-preview .no-image {
            color: var(--gray-color);
            padding: 20px;
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
                grid-template-columns: 1fr;
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
            <a href="?Php=Home/Ad/Video">播放横幅广告</a>
            <span class="separator">/</span>
            <span class="current">修改广告</span>
        </div>

        <!-- 页面标题 -->
        <h1 class="page-title">
            <i class="fa fa-edit"></i> 修改播放横幅广告
        </h1>
        <p class="page-subtitle">编辑播放页面广告信息和设置</p>

        <?php
        $AdminVideo = json_decode(file_get_contents("../JCSQL/Admin/Ad/AdminVideo.php"),true);
        array_multisort(array_column($AdminVideo,'VideoId'),SORT_DESC,$AdminVideo);
        function post_input($data){
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $Id = post_input($_GET["Id"]);
        $Video = $AdminVideo[$Id];	
        $VideoId = $Video['VideoId'];
        $VideoWebUrl = $Video['VideoWebUrl'];
        $VideoRemarks = $Video['VideoRemarks'];
        $VideoPicUrl = $Video['VideoPicUrl'];
        $VideoState = $Video['VideoState'];
        $VideoPicUrlWidth = $Video['VideoPicUrlWidth'];
        $VideoPicUrlHeight = $Video['VideoPicUrlHeight'];
        ?>

        <!-- 设置表单 -->
        <div class="settings-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa fa-play-circle"></i> 广告信息
                </h2>
            </div>
            <div class="card-body">
                <form method="post" class="settings-form">
                    <input type="hidden" name="Id" value="<?php echo $Id;?>" />
                    
                    <div class="form-group">
                        <label class="form-label">广告排序</label>
                        <input type="text" class="form-control" name="VideoId" value="<?php echo $VideoId;?>" placeholder="请输入广告排序编号">
                        <div class="form-hint">数字越小排序越靠前</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">广告链接URL</label>
                        <input type="text" class="form-control" name="VideoWebUrl" value="<?php echo $VideoWebUrl;?>" placeholder="请输入广告跳转链接">
                        <div class="form-hint">用户点击广告后跳转的目标网址</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">广告图片URL</label>
                        <input type="text" class="form-control" name="VideoPicUrl" value="<?php echo $VideoPicUrl;?>" placeholder="请输入广告图片链接">
                        <div class="form-hint">广告展示的图片地址</div>
                        
                        <!-- 图片预览 -->
                        <div class="image-preview">
                            <?php if($VideoPicUrl): ?>
                                <img src="<?php echo $VideoPicUrl; ?>" alt="广告图片预览" onerror="this.style.display='none'">
                                <div style="margin-top: 10px;">
                                    <a href="<?php echo $VideoPicUrl; ?>" target="_blank" class="btn btn-outline" style="padding: 6px 12px;">
                                        <i class="fa fa-external-link"></i> 查看原图
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="no-image">
                                    <i class="fa fa-image" style="font-size: 2rem; margin-bottom: 10px;"></i>
                                    <p>暂无图片预览</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label">广告图片宽度</label>
                            <input type="text" class="form-control" name="VideoPicUrlWidth" value="<?php echo $VideoPicUrlWidth;?>" placeholder="例如: 100% 或 300px">
                            <div class="form-hint">支持百分比和px格式，100%代表自适应宽度</div>
                        </div>
                        <div class="form-col">
                            <label class="form-label">广告图片高度</label>
                            <input type="text" class="form-control" name="VideoPicUrlHeight" value="<?php echo $VideoPicUrlHeight;?>" placeholder="例如: 100px">
                            <div class="form-hint">支持百分比和px格式，建议使用固定高度</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">广告到期时间</label>
                        <input type="text" class="form-control" name="VideoState" value="<?php echo $VideoState;?>" placeholder="例如: 20241231">
                        <div class="form-hint">格式：YYYYMMDD，如20241231代表2024年12月31日</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">广告费/联系方式</label>
                        <input type="text" class="form-control" name="VideoRemarks" value="<?php echo $VideoRemarks;?>" placeholder="例如: 1000元，QQ：123456">
                        <div class="form-hint">推荐格式：【金额，联系方式】，如【1000元，QQ：123456】</div>
                    </div>

                    <!-- 提交按钮 -->
                    <div class="btn-group">
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> 保存修改
                        </button>
                        <a href="?Php=Home/Ad/Video" class="btn btn-outline">
                            <i class="fa fa-arrow-left"></i> 返回列表
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 移动端遮罩层 -->
    <div class="sidebar-overlay"></div>

    <script src="../Static/Admin/Js/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
     
        // 实时图片预览
        $('input[name="VideoPicUrl"]').on('input', function() {
            var imageUrl = $(this).val();
            var previewContainer = $(this).next('.form-hint').next('.image-preview');
            
            if (imageUrl) {
                previewContainer.html('<img src="' + imageUrl + '" alt="广告图片预览" onerror="this.style.display=\'none\'"><div style="margin-top: 10px;"><a href="' + imageUrl + '" target="_blank" class="btn btn-outline" style="padding: 6px 12px;"><i class="fa fa-external-link"></i> 查看原图</a></div>');
            } else {
                previewContainer.html('<div class="no-image"><i class="fa fa-image" style="font-size: 2rem; margin-bottom: 10px;"></i><p>暂无图片预览</p></div>');
            }
        });

        // 表单验证
        $('form').submit(function() {
            var requiredFields = ['VideoWebUrl', 'VideoPicUrl', 'VideoPicUrlWidth', 'VideoPicUrlHeight', 'VideoRemarks', 'VideoState'];
            var isValid = true;
            var errorMessage = '';
            
            requiredFields.forEach(function(field) {
                var value = $('input[name="' + field + '"]').val();
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
            
            return confirm('确定要保存修改吗？');
        });
    });
    </script>

    <?php
    // 保存逻辑
    if (isset($_POST['submit']) && isset($_POST['Id']) && isset($_POST['VideoId']) && isset($_POST['VideoWebUrl']) && isset($_POST['VideoRemarks']) && isset($_POST['VideoPicUrl']) && isset($_POST['VideoState']) && isset($_POST['VideoPicUrlWidth']) && isset($_POST['VideoPicUrlHeight'])) {

        $Id = post_input($_POST["Id"]);
        $VideoId = post_input($_POST["VideoId"]);
        $VideoWebUrl = post_input($_POST["VideoWebUrl"]);
        $VideoRemarks = post_input($_POST["VideoRemarks"]);
        $VideoPicUrl = post_input($_POST["VideoPicUrl"]);
        $VideoState = post_input($_POST["VideoState"]);
        $VideoPicUrlWidth = post_input($_POST["VideoPicUrlWidth"]);
        $VideoPicUrlHeight = post_input($_POST["VideoPicUrlHeight"]);
        
        // 验证字段
        if ($Id == null) { echo'<script language="javascript">alert("广告Id不可为空"); </script>';exit();}
        if ($VideoWebUrl == null) { echo'<script language="javascript">alert("广告链接URL不可为空"); </script>';exit();}
        if ($VideoPicUrl == null) { echo'<script language="javascript">alert("广告图片不可为空"); </script>';exit();}
        if ($VideoPicUrlWidth == null) { echo'<script language="javascript">alert("广告图片宽度不可为空"); </script>';exit();}
        if ($VideoPicUrlHeight == null) { echo'<script language="javascript">alert("广告图片高度不可为空"); </script>';exit();}
        if ($VideoRemarks == null) { echo'<script language="javascript">alert("广告费/联系方式不可为空"); </script>';exit();}	
        if ($VideoState == null) { echo'<script language="javascript">alert("广告到期时间不可为空"); </script>';exit();}	
        
        include('../Php/Public/Mysql.php');	
        $AdminVideoMod['VideoId'] = $VideoId;
        $AdminVideoMod['VideoWebUrl'] = $VideoWebUrl;
        $AdminVideoMod['VideoRemarks'] = $VideoRemarks;
        $AdminVideoMod['VideoPicUrl'] = $VideoPicUrl;
        $AdminVideoMod['VideoState'] = $VideoState;
        $AdminVideoMod['VideoPicUrlWidth'] = $VideoPicUrlWidth;
        $AdminVideoMod['VideoPicUrlHeight'] = $VideoPicUrlHeight;	
        $UPDATE = UPDATE($AdminVideo, $Id, $AdminVideoMod); 
        $file = fopen("../JCSQL/Admin/Ad/AdminVideo.php","w");
        fwrite($file,json_encode($UPDATE));
        fclose($file);  
    ?>
    <script language="javascript"> 
        alert("恭喜修改成功！"); 
        window.location.href="?Php=Home/Ad/VideoMod&Id=<?php echo $Id;?>";
    </script> 
    <?php
    }
    ?>

    <?php include('../Php/Admin/footer.php');?>
</body>
</html>