<?php
if (isset($ADMINKEY)) { }else{ exit('404');   }   
include('../Php/Admin/cookie.php');?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改站点 - YUTUCMS管理中心</title>
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

        /* 表单样式 */
        .form-group {
            margin-bottom: 20px;
            display: flex;
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
        }

        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d9d9d9;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(22, 93, 255, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d9d9d9;
            border-radius: 6px;
            font-size: 0.95rem;
            background: white;
            cursor: pointer;
        }

        .form-select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(22, 93, 255, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--light-color);
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

        .btn-secondary {
            background: var(--gray-color);
            color: white;
        }

        .btn-secondary:hover {
            background: #6b7785;
            transform: translateY(-2px);
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

        /* 辅助文本 */
        .help-text {
            font-size: 0.85rem;
            color: var(--gray-color);
            margin-top: 5px;
        }

        .form-row {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .form-row .form-input {
            flex: 1;
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
            .form-group {
                flex-direction: column;
                align-items: stretch;
            }

            .form-label {
                width: 100%;
                text-align: left;
                padding: 0 0 8px 0;
            }

            .form-control {
                padding: 0;
            }

            .form-row {
                flex-direction: column;
                gap: 8px;
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
                <span><a href="?Php=Plug/Zhanqun/index">站群中心</a></span>
                <span class="divider">/</span>
                <span class="active">修改站点</span>
            </div>
            <h1 class="page-title">修改站点配置</h1>
            <p class="page-subtitle">修改站点的基本信息和模板设置</p>
        </div>

        <!-- 表单卡片 -->
        <div class="content-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa fa-edit"></i>
                    站点设置
                </h2>
            </div>

            <?php
            $Zhanqun = json_decode(file_get_contents("../JCSQL/Admin/Plug/Zhanqun/index.php"),true);
            function post_input($data){$data = stripslashes($data);$data = htmlspecialchars($data);return $data;}
            $Id = post_input($_GET["Id"]);
            $Zhanquns = $Zhanqun[$Id];    
            $WebDomain = $Zhanquns['WebDomain'];
            $WebTitle = $Zhanquns['WebTitle'];
            $WebKeywords = $Zhanquns['WebKeywords'];
            $WebDescription = $Zhanquns['WebDescription'];
            $WebMobanPC = $Zhanquns['WebMobanPC'];
            $WebMobanWAP = $Zhanquns['WebMobanWAP'];
            $WebLogo = $Zhanquns['WebLogo'];
            $WebEmail = $Zhanquns['WebEmail'];
            ?>

            <?php 
            /***检测模板文件夹里的模板名称并且给予输出***/
            $Template = NULL;
            $Templates = scandir("../Template/");
            $bakpc = array();
            foreach ($Templates as $name) {
                if(strpos($name,'.') !== false || strpos($name,'-') !== false){
                }else{
                    $Template .= '<option value="'.$name.'">'.$name.'</option>';
                    $bakpc[] = $name;
                }
            }

            $WebMobanPCOption = '<option value="'.$WebMobanPC.'">'.$WebMobanPC.'</option>';
            $WebMobanWAPOption = '<option value="'.$WebMobanWAP.'">'.$WebMobanWAP.'</option>';
            ?>

            <form method="post" name='form' class="am-form">
                <input type="hidden" name="Id" value="<?php echo $Id;?>" />

                <!-- 网站域名 -->
                <div class="form-group">
                    <label class="form-label">网站域名</label>
                    <div class="form-control">
                        <input type="text" value="<?php echo $WebDomain;?>" name="WebDomain" class="form-input" placeholder="请输入网站域名（不带http://）">
                        <div class="help-text">网站域名不带前缀与带前缀为两个站点</div>
                    </div>
                </div>

                <!-- 网站名称 -->
                                    <div class="form-group">
    <label class="form-label">网站名称</label>
    <div class="form-row">
        <input type="text" class="form-control" value="" name="WebTitle" placeholder="输入网站名称">
        <button type="button" onclick="randomWebTitle()" class="btn btn-outline">
            <i class="fa fa-random"></i> 随机抽取
        </button>
    </div>
     <div class="help-text">随机数据文本目录：/JCSQL/Admin/Plug/Zhanqun/title.txt</div>
</div>

<div class="form-group">
    <label class="form-label">关键字</label>
    <div class="form-row">
        <input type="text" class="form-control" value="" name="WebKeywords" placeholder="输入网站关键字">
        <button type="button" onclick="randomWebKeywords()" class="btn btn-outline">
            <i class="fa fa-random"></i> 随机抽取
        </button>
    </div>
    <div class="help-text">随机数据文本目录：/JCSQL/Admin/Plug/Zhanqun/keywords.txt</div>
</div>

<div class="form-group">
    <label class="form-label">关键描述</label>
    <div class="form-row">
        <input type="text" class="form-control" value="" name="WebDescription" placeholder="输入网站描述">
        <button type="button" onclick="randomWebDescription()" class="btn btn-outline">
            <i class="fa fa-random"></i> 随机抽取
        </button>
    </div>
    <div class="help-text">随机数据文本目录：/JCSQL/Admin/Plug/Zhanqun/description.txt</div>
</div>


                <!-- PC模板选择 -->
                <div class="form-group">
                    <label class="form-label">PC模板选择</label>
                    <div class="form-control">
                        <select name="WebMobanPC" class="form-select">
                            <?php echo $WebMobanPCOption;?>
                            <?php echo $Template;?>
                        </select>
                        <div class="help-text">
                            <i class="fa fa-info-circle"></i>
                            默认随机抽取可手动选择
                        </div>
                    </div>
                </div>

                <!-- WAP模板选择 -->
                <div class="form-group">
                    <label class="form-label">WAP模板选择</label>
                    <div class="form-control">
                        <select name="WebMobanWAP" class="form-select">
                            <?php echo $WebMobanWAPOption;?>
                            <?php echo $Template;?>
                        </select>
                        <div class="help-text">
                            <i class="fa fa-info-circle"></i>
                            默认随机抽取可手动选择
                        </div>
                    </div>
                </div>

                <!-- 网站logoURL -->
                <div class="form-group">
                    <label class="form-label">网站logo URL</label>
                    <div class="form-control">
                        <input type="text" value="<?php echo $WebLogo;?>" name="WebLogo" class="form-input" placeholder="请输入网站logo的URL地址">
                    </div>
                </div>

                <!-- 广告邮箱 -->
                <div class="form-group">
                    <label class="form-label">广告邮箱</label>
                    <div class="form-control">
                        <input type="email" value="<?php echo $WebEmail;?>" name="WebEmail" class="form-input" placeholder="请输入广告联系邮箱">
                    </div>
                </div>

                <!-- 表单操作 -->
                <div class="form-actions">
                    <button type="submit" name="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i>
                        保存修改
                    </button>
                    <a href="?Php=Plug/Zhanqun/index" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i>
                        返回列表
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="../Static/Admin/Js/jquery.min.js"></script>
    <?php include('../Php/Admin/footer.php');?>

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
</body>
</html>

<?php
// 处理表单提交
if (isset($_POST['submit']) && isset($_POST['Id']) && isset($_POST['WebDomain']) && isset($_POST['WebTitle']) && isset($_POST['WebKeywords']) && isset($_POST['WebDescription']) && isset($_POST['WebMobanPC']) && isset($_POST['WebMobanWAP']) && isset($_POST['WebLogo']) && isset($_POST['WebEmail'])) {
    $Zhanqun = json_decode(file_get_contents("../JCSQL/Admin/Plug/Zhanqun/index.php"),true);
    $Id = post_input($_POST["Id"]);
    $WebDomain = post_input($_POST["WebDomain"]);
    $WebTitle = post_input($_POST["WebTitle"]);
    $WebKeywords = post_input($_POST["WebKeywords"]);
    $WebDescription = post_input($_POST["WebDescription"]);
    $WebMobanPC = post_input($_POST["WebMobanPC"]);
    $WebMobanWAP = post_input($_POST["WebMobanWAP"]);
    $WebLogo = post_input($_POST["WebLogo"]);
    $WebEmail = post_input($_POST["WebEmail"]);
    
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
    
    $UPDATE = UPDATE($Zhanqun,$Id,$Zhanqunadd); 
    $file = fopen("../JCSQL/Admin/Plug/Zhanqun/index.php","w");
    fwrite($file,json_encode($UPDATE));
    fclose($file);  
?>
    <script language="javascript"> 
        alert("恭喜修改成功！"); 
        window.location.href = "?Php=Plug/Zhanqun/mod&Id=<?php echo $Id;?>";
    </script> 
<?php
}
?>