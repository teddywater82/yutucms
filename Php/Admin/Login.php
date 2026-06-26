<?php
// 所有PHP处理逻辑移到最顶部，确保在任何HTML输出之前执行
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('PRC');
session_start();	
include("../JCSQL/Admin/Security/AdminUser.php");

// 初始化变量
$alertMessages = [];
$alertTypes = []; // 存储消息类型：info, success, warning, error
$afterAlertRedirect = null;
$showLoginForm = true;

// IP白名单检查
if(IPPASS != NULL && strpos(IPPASS, $_SERVER["REMOTE_ADDR"]) === false ){
    $alertMessages[] = "您的IP为外来入侵者不在IP白名单内！无法访问！";
    $alertTypes[] = "error";
    $afterAlertRedirect = "?";
    $showLoginForm = false;
}

// 处理登录提交
if ($showLoginForm && isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['yzm']) ) {
    function post_input($data){
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    $username = post_input($_POST["username"]);	
    $password = post_input($_POST["password"]);	
    $yzm = post_input($_POST["yzm"]);

    $wornIpNameDir = '../JCSQL/Admin/ErrorIp';
    $wornIpName = '../JCSQL/Admin/ErrorIp/'.$_SERVER["REMOTE_ADDR"].'lock';
    if(!is_dir('../JCSQL/Admin/ErrorIp'))
    {
        mkdir('../JCSQL/Admin/ErrorIp', 0755, true);
    }

    // 指定ip错误次数增加
    function increaseIpLock($wornIpName){
        $wornNum = 0;
        if(file_exists($wornIpName)) {
            $wornNum = file_get_contents($wornIpName);
        }
        file_put_contents($wornIpName, (int)$wornNum + 1);
    }

    // 检查安全模式
    if(file_exists($wornIpName) && file_get_contents($wornIpName) > '3'){
        file_put_contents('../lock.lock','');
        unlink($wornIpName);//删除ip锁
    }

    if(file_exists("../lock.lock")){
        $alertMessages[] = "三次错误进入安全模式，请删除根目录lock.lock文件，恢复正常使用。";
        $alertTypes[] = "error";
        $afterAlertRedirect = "?";
    }
    // 验证验证码
    else if($_SESSION['yzm'] != $yzm){
        increaseIpLock($wornIpName);
        $alertMessages[] = "验证码错误";
        $alertTypes[] = "error";
    }
    // 验证用户名
    else if(USERNAME != $username ){
        increaseIpLock($wornIpName);
        $alertMessages[] = "账号错误";
        $alertTypes[] = "error";
    }
    // 验证密码
    else if(PASSWORD != $password){
        increaseIpLock($wornIpName);
        $alertMessages[] = "密码错误";
        $alertTypes[] = "error";
    }
    // 登录成功
    else {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $alertMessages[] = "登陆成功！正在跳转...";
        $alertTypes[] = "success";
        $afterAlertRedirect = "?Php=Home/index";
    }
} else {
    // 仅在非提交状态下显示默认账号密码提示（避免覆盖错误信息）
    if(USERNAME == 'yutucms'){
        $alertMessages[] = "您当前使用的是默认账号！请尽早修改默认账号换上更加复杂的账号，避免被有心人入侵";
        $alertTypes[] = "info"; // 这是提示类型
    }

    if(PASSWORD == 'yutucms'){
        $alertMessages[] = "您当前使用的是默认密码！请尽早修改默认密码换上更加复杂的密码，避免被有心人入侵";
        $alertTypes[] = "info"; // 这是提示类型
    }
}

// 输出需要显示的消息
if(!empty($alertMessages)) {
    echo '<script>
            window.customAlertMessages = ' . json_encode($alertMessages) . ';
            window.customAlertTypes = ' . json_encode($alertTypes) . ';
            ' . ($afterAlertRedirect ? "window.afterAlertRedirect = '" . addslashes($afterAlertRedirect) . "';" : '') . '
          </script>';
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>YUTUCMS管理中心</title>
  <meta name="description" content="YUTUCMS管理中心登录页面">
  <meta name="keywords" content="YUTUCMS,管理中心,登录">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <meta name="apple-mobile-web-app-title" content="YUTUCMS" />
  
  <!-- 引入Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- 引入Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
  
  <!-- 自定义Tailwind配置 -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#165DFF',
            secondary: '#36CFC9',
            neutral: '#86909C',
            dark: '#1D2129',
            light: '#F2F3F5',
            success: '#52C41A',
            warning: '#FAAD14',
            danger: '#FF4D4F',
            info: '#1890FF' // 新增信息提示颜色
          },
          fontFamily: {
            inter: ['Inter', 'system-ui', 'sans-serif'],
          },
        },
      }
    }
  </script>
  
  <style type="text/tailwindcss">
    @layer utilities {
      .content-auto {
        content-visibility: auto;
      }
      .bg-gradient-custom {
        background: linear-gradient(135deg, #165DFF 0%, #36CFC9 100%);
      }
      .text-shadow {
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
      }
      .transition-custom {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      .backdrop-blur {
        backdrop-filter: blur(5px);
      }
    }
  </style>
</head>
<body class="font-inter bg-gray-50 min-h-screen flex items-center justify-center p-4">
  <!-- 自定义弹窗背景层 -->
  <div id="customAlertOverlay" class="fixed inset-0 bg-black/50 backdrop-blur z-50 hidden items-center justify-center">
    <!-- 弹窗容器 -->
    <div id="customAlert" class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0">
      <!-- 弹窗头部 -->
      <div id="alertHeader" class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-xl font-bold" id="alertTitle">提示</h3>
        <button id="alertClose" class="text-neutral hover:text-dark transition-custom">
          <i class="fa fa-times text-xl"></i>
        </button>
      </div>
      
      <!-- 弹窗内容 -->
      <div class="p-6">
        <p id="alertMessage" class="text-gray-700"></p>
      </div>
      
      <!-- 弹窗底部 -->
      <div class="p-4 bg-gray-50 rounded-b-xl flex justify-end">
        <button id="alertConfirm" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-custom">
          确定
        </button>
      </div>
    </div>
  </div>
  
  <!-- 主容器 -->
  <div class="w-full max-w-6xl grid md:grid-cols-2 gap-8 items-center">
    
    <!-- 左侧品牌展示区 -->
    <div class="hidden md:block p-8 bg-gradient-custom rounded-2xl shadow-xl text-white transform hover:scale-[1.01] transition-custom">
      <div class="mb-8">
        <h1 class="text-[clamp(2rem,5vw,3rem)] font-bold mb-2 text-shadow">YUTUCMS</h1>
        <p class="text-white/80 text-lg">专业的内容管理系统</p>
      </div>
      
      <div class="space-y-6">
        <div class="flex items-start gap-4">
          <div class="bg-white/20 p-3 rounded-full">
            <i class="fa fa-shield text-xl"></i>
          </div>
          <div>
            <h3 class="text-xl font-semibold mb-1">安全可靠</h3>
            <p class="text-white/70">多重安全防护，保障系统稳定运行</p>
          </div>
        </div>
        
        <div class="flex items-start gap-4">
          <div class="bg-white/20 p-3 rounded-full">
            <i class="fa fa-cogs text-xl"></i>
          </div>
          <div>
            <h3 class="text-xl font-semibold mb-1">功能强大</h3>
            <p class="text-white/70">丰富的功能模块，满足各类需求</p>
          </div>
        </div>
        
        <div class="flex items-start gap-4">
          <div class="bg-white/20 p-3 rounded-full">
            <i class="fa fa-tachometer text-xl"></i>
          </div>
          <div>
            <h3 class="text-xl font-semibold mb-1">高效管理</h3>
            <p class="text-white/70">简洁直观的操作界面，提升工作效率</p>
          </div>
        </div>
      </div>
      
      <div class="mt-12 text-center">
        <p class="text-white/80">&copy; 2025 YUTUCMS 版权所有</p>
      </div>
    </div>
    
    <!-- 右侧登录表单区 -->
    <div class="bg-white rounded-2xl shadow-lg p-8 md:p-10 w-full">
      <div class="text-center mb-8">
        <h2 class="text-[clamp(1.5rem,3vw,2rem)] font-bold text-dark">管理中心登录</h2>
        <p class="text-neutral mt-2">请输入您的账号信息登录系统</p>
      </div>
      
      <form class="space-y-6" action="" method="POST">
        <div>
          <label for="username" class="block text-sm font-medium text-neutral mb-1">登录账户</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fa fa-user text-neutral"></i>
            </div>
            <input type="text" name="username" id="username" 
                  class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-custom outline-none" 
                  placeholder="请输入登录账户" required
                  value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
          </div>
        </div>
        
        <div>
          <label for="password" class="block text-sm font-medium text-neutral mb-1">登录密码</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fa fa-lock text-neutral"></i>
            </div>
            <input type="password" name="password" id="password" 
                  class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-custom outline-none" 
                  placeholder="请输入登录密码" required>
          </div>
        </div>
        
        <div>
          <label for="yzm" class="block text-sm font-medium text-neutral mb-1">验证码</label>
          <div class="flex gap-3">
            <div class="relative flex-1">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa fa-shield text-neutral"></i>
              </div>
              <input type="text" name="yzm" id="yzm-input" 
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-custom outline-none" 
                    placeholder="请输入验证码" required>
            </div>
            <div class="flex-shrink-0">
              <img id="yzm-image" onClick="javascript:myyzm();" 
                   class="h-12 w-32 object-cover rounded-lg cursor-pointer border border-gray-300 hover:opacity-90 transition-custom" 
                   alt="点击换一张验证码">
            </div>
          </div>
        </div>
        
        <button name="submit" type="submit" 
                class="w-full bg-primary hover:bg-primary/90 text-white font-medium py-3 px-4 rounded-lg transition-custom transform hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0">
          <i class="fa fa-sign-in mr-2"></i>登录系统
        </button>
      </form>
      
      <!-- 移动端版权信息 -->
      <div class="mt-8 text-center text-neutral text-sm md:hidden">
        <p>&copy; 2025 YUTUCMS 版权所有</p>
      </div>
    </div>
  </div>

  <script src="../Static/Admin/Js/jquery.min.js"></script>
  <script src="../Static/Admin/Js/amazeui.min.js"></script>
  <script src="../Static/Admin/Js/app.js"></script>

  <script>
    // 自定义弹窗功能
    document.addEventListener('DOMContentLoaded', function() {
      // 获取弹窗元素
      const overlay = document.getElementById('customAlertOverlay');
      const alertBox = document.getElementById('customAlert');
      const alertTitle = document.getElementById('alertTitle');
      const alertMessage = document.getElementById('alertMessage');
      const alertClose = document.getElementById('alertClose');
      const alertConfirm = document.getElementById('alertConfirm');
      
      // 消息队列和类型
      let messageQueue = [];
      let typeQueue = [];
      let afterAlertRedirect = null;
      
      // 检查是否有需要显示的消息
      if(window.customAlertMessages && window.customAlertMessages.length > 0) {
        messageQueue = window.customAlertMessages;
        typeQueue = window.customAlertTypes || [];
        afterAlertRedirect = window.afterAlertRedirect || null;
        showNextMessage();
      }
      
      // 显示下一条消息
      function showNextMessage() {
        if(messageQueue.length === 0) return;
        
        const message = messageQueue.shift();
        const type = typeQueue.shift() || 'info'; // 默认信息类型
        alertMessage.textContent = message;
        
        // 根据消息类型设置不同的标题和样式
        switch(type) {
          case 'success':
            alertTitle.textContent = '成功';
            alertTitle.className = 'text-xl font-bold text-success';
            break;
          case 'error':
            alertTitle.textContent = '错误';
            alertTitle.className = 'text-xl font-bold text-danger';
            break;
          case 'warning':
            alertTitle.textContent = '警告';
            alertTitle.className = 'text-xl font-bold text-warning';
            break;
          case 'info':
          default:
            alertTitle.textContent = '提示';
            alertTitle.className = 'text-xl font-bold text-info';
            break;
        }
        
        // 显示弹窗并添加动画
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        setTimeout(() => {
          alertBox.classList.remove('scale-95', 'opacity-0');
          alertBox.classList.add('scale-100', 'opacity-100');
        }, 10);
      }
      
      // 关闭弹窗
      function closeAlert() {
        alertBox.classList.remove('scale-100', 'opacity-100');
        alertBox.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
          overlay.classList.remove('flex');
          overlay.classList.add('hidden');
          
          // 如果有下一条消息，显示它
          if(messageQueue.length > 0) {
            showNextMessage();
          } else if(afterAlertRedirect) {
            // 如果需要跳转，执行跳转
            window.location.href = afterAlertRedirect;
          }
        }, 300);
      }
      
      // 绑定关闭事件
      alertClose.addEventListener('click', closeAlert);
      alertConfirm.addEventListener('click', closeAlert);
      
      // 点击背景关闭弹窗
      overlay.addEventListener('click', function(e) {
        if(e.target === overlay) {
          closeAlert();
        }
      });
      
      // 验证码刷新函数
      window.myyzm = function() {
          $('#yzm-image').attr('src','../Php/Admin/yzm.php?nocache='+Math.random());
      }
      
      // 初始化验证码
      myyzm();
      
      // 添加表单输入动画效果
      const inputs = document.querySelectorAll('input');
      inputs.forEach(input => {
          input.addEventListener('focus', () => {
              input.parentElement.classList.add('scale-[1.02]');
          });
          input.addEventListener('blur', () => {
              input.parentElement.classList.remove('scale-[1.02]');
          });
      });
    });
  </script>
</body>
</html>
    