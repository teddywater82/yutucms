<?php
if (isset($ADMINKEY)) { }else{ exit('404');   }   
include('../Php/Admin/cookie.php');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>站群插件新增 - YUTUCMS管理中心</title>
    <meta name="description" content="YUTUCMS 站群插件新增">
    <meta name="keywords" content="YUTUCMS, 站群管理, 插件管理">
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
            grid-template-columns: 2fr 1fr;
            gap: 15px;
            align-items: end;
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

        /* 表单选择器 */
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
            <span class="current">扩展管理</span>
        </div>

        <!-- 页面标题 -->
        <h1 class="page-title">
            <i class="fa fa-plus-circle"></i> 站群插件-新增站点
        </h1>
        <p class="page-subtitle">添加新的站群站点配置信息</p>

        <?php
        /***读取数据库中的数据并且给予变量中***/
        $AdminBasic = json_decode(file_get_contents("../JCSQL/Admin/Basic/AdminBasic.php"),true);

        $WebMobanPC = $AdminBasic['WebMobanPC'];
        $WebMobanWAP = $AdminBasic['WebMobanWAP'];
        $WebTitle = $AdminBasic['WebTitle'];
        $WebKeywords = $AdminBasic['WebKeywords'];
        $WebDescription = $AdminBasic['WebDescription'];
        $WebGongao = $AdminBasic['WebGongao'];
        $WebGongaoOpen = $AdminBasic['WebGongaoOpen'];
        $WebLogo = $AdminBasic['WebLogo'];
        $WebEmail = $AdminBasic['WebEmail'];
        ?>								
        <?php 
        /***检测模板文件夹里的模板名称并且给予输出***/
        $Template = NULL;
        $Templates = scandir("../Template/");
        $bakpc = array();
        foreach ($Templates as $name) {
            if(strpos($name,'.') !== false || strpos($name,'-') !== false ){
            }else{
                $Template .= '<option value="'.$name.'">'.$name.'</option>';
                $bakpc[] = $name;
            }
        }
        $pcsuiji1 = $bakpc[array_rand($bakpc)];
        $tmpsuiji1 = '<option value="'.$pcsuiji1.'">'.$pcsuiji1.'</option>';
        $pcsuiji2 = $bakpc[array_rand($bakpc)];
        $tmpsuiji2 = '<option value="'.$pcsuiji2.'">'.$pcsuiji2.'</option>';
        ?>

        <!-- 设置表单 -->
        <div class="settings-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa fa-sitemap"></i> 站点基本信息
                </h2>
            </div>
            <div class="card-body">
                <form method="post" name='form' class="settings-form">
                    <div class="form-group">
                        <label class="form-label">网站域名</label>
                        <input type="text" class="form-control" value="" name="WebDomain" placeholder="例如: example.com">
                        <div class="form-hint">网站域名不带前缀与带前缀为两个站点</div>
                    </div>

                    <div class="form-group">
    <label class="form-label">网站名称</label>
    <div class="form-row">
        <input type="text" class="form-control" value="" name="WebTitle" placeholder="输入网站名称">
        <button type="button" onclick="randomWebTitle()" class="btn btn-outline">
            <i class="fa fa-random"></i> 随机抽取
        </button>
    </div>
     <div class="form-hint">随机数据文本目录：/JCSQL/Admin/Plug/Zhanqun/title.txt</div>
</div>

<div class="form-group">
    <label class="form-label">关键字</label>
    <div class="form-row">
        <input type="text" class="form-control" value="" name="WebKeywords" placeholder="输入网站关键字">
        <button type="button" onclick="randomWebKeywords()" class="btn btn-outline">
            <i class="fa fa-random"></i> 随机抽取
        </button>
    </div>
    
    <div class="form-hint">随机数据文本目录：/JCSQL/Admin/Plug/Zhanqun/keywords.txt</div>
</div>

<div class="form-group">
    <label class="form-label">关键描述</label>
    <div class="form-row">
        <input type="text" class="form-control" value="" name="WebDescription" placeholder="输入网站描述">
        <button type="button" onclick="randomWebDescription()" class="btn btn-outline">
            <i class="fa fa-random"></i> 随机抽取
        </button>
    </div>
    <div class="form-hint">随机数据文本目录：/JCSQL/Admin/Plug/Zhanqun/description.txt</div>
</div>

                    <div class="form-group">
                        <label class="form-label">PC模板选择</label>
                        <select name="WebMobanPC" class="form-select">
                            <?php echo $tmpsuiji1;?>
                            <?php echo $Template;?>
                        </select>
                        <div class="form-hint">默认随机抽取可手动选择</div>
                        <div style="margin-top: 10px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">WAP模板选择</label>
                        <select name="WebMobanWAP" class="form-select">
                            <?php echo $tmpsuiji2;?>
                            <?php echo $Template;?>
                        </select>
                        <div class="form-hint">默认随机抽取可手动选择</div>
                        <div style="margin-top: 10px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">网站logo URL</label>
                        <input type="text" class="form-control" value="<?php echo $WebLogo;?>" name="WebLogo" placeholder="输入网站logo URL">
                    </div>

                    <div class="form-group">
                        <label class="form-label">广告邮箱</label>
                        <input type="email" class="form-control" value="<?php echo $WebEmail;?>" name="WebEmail" placeholder="输入广告合作邮箱">
                    </div>

                    <!-- 提交按钮 -->
                    <div class="btn-group">
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fa fa-plus"></i> 添加站点
                        </button>
                        <button type="reset" class="btn btn-outline">
                            <i class="fa fa-undo"></i> 重置表单
                        </button>
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
    // 移动端菜单切换
    $('.mobile-toggle').click(function() {
        $('.sidebar').toggleClass('mobile-open');
    });
    
    $('.sidebar-overlay').click(function() {
        $('.sidebar').removeClass('mobile-open');
    });

    // 随机标题函数
    window.randomWebTitle = function(){
        $.post("?Php=Plug/Zhanqun/SEO",{fl:'title'},
        function(data){
            $('[name="WebTitle"]').val(data);
        },
        "text");
    }
    
    // 随机关键词函数
    window.randomWebKeywords = function(){
        $.post("?Php=Plug/Zhanqun/SEO",{fl:'keywords'},
        function(data){
            $('[name="WebKeywords"]').val(data);
        },
        "text");
    }
    
    // 随机描述函数
    window.randomWebDescription = function(){
        $.post("?Php=Plug/Zhanqun/SEO",{fl:'description'},
        function(data){
            $('[name="WebDescription"]').val(data);
        },
        "text");
    }

    // 表单验证
    $('form').submit(function() {
        var requiredFields = ['WebDomain', 'WebTitle', 'WebKeywords', 'WebDescription', 'WebMobanPC', 'WebMobanWAP', 'WebLogo', 'WebEmail'];
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
        
        return confirm('确定要添加这个站点吗？');
    });

    // 设置焦点
    $('[name="submit"]').focus();
});
</script>

    <?php
    // 保存逻辑
    if (isset($_POST['submit']) && isset($_POST['WebDomain']) && isset($_POST['WebTitle']) && isset($_POST['WebKeywords']) && isset($_POST['WebDescription']) && isset($_POST['WebMobanPC']) && isset($_POST['WebMobanWAP']) && isset($_POST['WebLogo']) && isset($_POST['WebEmail'])) {
        function post_input($data){
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        
        $Zhanqun = json_decode(file_get_contents("../JCSQL/Admin/Plug/Zhanqun/index.php"),true);
        $WebDomain = post_input($_POST["WebDomain"]);
        $WebTitle = post_input($_POST["WebTitle"]);
        $WebKeywords = post_input($_POST["WebKeywords"]);
        $WebDescription = post_input($_POST["WebDescription"]);
        $WebMobanPC = post_input($_POST["WebMobanPC"]);
        $WebMobanWAP = post_input($_POST["WebMobanWAP"]);
        $WebLogo = post_input($_POST["WebLogo"]);
        $WebEmail = post_input($_POST["WebEmail"]);
        
        // 验证字段
        if ($WebDomain == null) { echo'<script language="javascript">alert("域名不可为空"); </script>';exit();}
        if ($WebTitle == null) { echo'<script language="javascript">alert("标题不可为空"); </script>';exit();}
        if ($WebKeywords == null) { echo'<script language="javascript">alert("关键词不可为空"); </script>';exit();}
        if ($WebDescription == null) { echo'<script language="javascript">alert("介绍不可为空"); </script>';exit();}
        if ($WebMobanPC == null) { echo'<script language="javascript">alert("PC模板不可为空"); </script>';exit();}
        if ($WebMobanWAP == null) { echo'<script language="javascript">alert("WAP模板不可为空"); </script>';exit();}
        if ($WebLogo == null) { echo'<script language="javascript">alert("网站LOGO不可为空"); </script>';exit();}
        if ($WebEmail == null) { echo'<script language="javascript">alert("网站邮箱不可为空"); </script>';exit();}
        
        include('../Php/Public/Mysql.php');	
        $Zhanqunadd['WebDomain'] = $WebDomain;
        $Zhanqunadd['WebTitle'] = $WebTitle;
        $Zhanqunadd['WebKeywords'] = $WebKeywords;
        $Zhanqunadd['WebDescription'] = $WebDescription;
        $Zhanqunadd['WebMobanPC'] = $WebMobanPC;
        $Zhanqunadd['WebMobanWAP'] = $WebMobanWAP;
        $Zhanqunadd['WebLogo'] = $WebLogo;
        $Zhanqunadd['WebEmail'] = $WebEmail;

        $UPDATE = INSERT($Zhanqun, $Zhanqunadd); 
        $file = fopen("../JCSQL/Admin/Plug/Zhanqun/index.php","w");
        fwrite($file,json_encode($UPDATE));
        fclose($file);  
    ?>
    <script language="javascript"> 
        alert("恭喜添加成功！"); 
        window.location.href="?Php=Plug/Zhanqun/index";
    </script> 
    <?php } ?>

    <?php include('../Php/Admin/footer.php');?>
</body>
</html>