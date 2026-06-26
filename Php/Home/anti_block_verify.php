<?php
// ===================================================
// ✅ YUTUCMS 防屏蔽验证页（带签名防绕过 + 保留原样式版）
// ===================================================

session_start();
$secretKey = 'yutucms_secure_key_2025'; // 必须与 index.php 一致

// --- 生成签名 token ---
if (!function_exists('random_bytes')) {
    function random_bytes($len) {
        if (function_exists('openssl_random_pseudo_bytes'))
            return openssl_random_pseudo_bytes($len);
        $bytes = '';
        for ($i=0;$i<$len;$i++) $bytes .= chr(mt_rand(0,255));
        return $bytes;
    }
}
function gen_token($secretKey) {
    $token = bin2hex(random_bytes(16));
    $_SESSION['verify_token'] = $token;
    $_SESSION['verify_time'] = time();
    return $token;
}
function check_token($token) {
    if (empty($token) || empty($_SESSION['verify_token'])) return false;
    if ($token !== $_SESSION['verify_token']) return false;
    if (time() - $_SESSION['verify_time'] > 300) return false;
    unset($_SESSION['verify_token'], $_SESSION['verify_time']);
    return true;
}

// --- POST 验证 ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = isset($_POST['token']) ? $_POST['token'] : '';
    if (!check_token($token)) {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(array('ok'=>0,'msg'=>'非法请求'));
        exit;
    }
    $ts = time();
    $val = $ts . '|' . hash_hmac('sha256', $ts, $secretKey);
    setcookie('anti_block_verified', $val, $ts + 86400, '/');
    echo json_encode(array('ok'=>1));
    exit;
}

// --- 获取配置 ---
$baseDir = __DIR__;
$rootDir = dirname(dirname($baseDir));
$zhanqunIndex = $rootDir . '/JCSQL/Admin/Plug/Zhanqun/index.php';
$mainCfg      = $rootDir . '/JCSQL/Admin/Basic/AdminBasic.php';
$AdminBasic = array();
if (file_exists($mainCfg)) {
    $AdminBasic = json_decode(file_get_contents($mainCfg), true);
    if (!is_array($AdminBasic)) $AdminBasic = array();
}
if (file_exists($zhanqunIndex)) {
    $list = json_decode(file_get_contents($zhanqunIndex), true);
    if (is_array($list)) {
        $host = isset($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) : '';
        if (substr($host,0,4)=='www.') $host = substr($host,4);
        foreach ($list as $v) {
            if (isset($v['WebDomain']) && strtolower($v['WebDomain'])==$host) {
                $AdminBasic = array_merge($AdminBasic, $v);
                break;
            }
        }
    }
}
$WebTitle = isset($AdminBasic['WebTitle']) ? $AdminBasic['WebTitle'] : 'YUTUCMS';
$WebLogo  = isset($AdminBasic['WebLogo']) ? $AdminBasic['WebLogo'] : '/logo.png';
$AntiBlockDomain = isset($AdminBasic['AntiBlockDomain']) ? html_entity_decode($AdminBasic['AntiBlockDomain'], ENT_QUOTES, 'UTF-8') : '';

$token = gen_token($secretKey);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($WebTitle); ?> - 验证访问</title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<meta name="robots" content="noindex, nofollow">
<style>
html, body {
  margin: 0;
  padding: 0;
  height: 100%;
  background: #fff;
  font-family: "Inter","Microsoft YaHei",sans-serif;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: #333;
  -webkit-tap-highlight-color: transparent;
  text-align: center;
}
:root { font-size: clamp(13px, 1.3vw, 18px); }
.logo {height: clamp(30px, 5vw, 45px);margin-bottom: 6px;max-width: 60%;transition: all 0.3s ease;}
@media (max-width: 767px) {.logo {height: clamp(24px, 8vw, 36px);max-width: 55%;}}
h1 {font-size: clamp(1.3rem, 2vw, 1.7rem);font-weight: 600;color: #165DFF;margin: 0.3em 0 0.6em;}
.domain {color:#555;font-size:clamp(1.1rem,1.5vw,1.3rem);margin-bottom:1.3em;word-break:break-word;}
.verify-wrap {width: min(85vw, 280px);max-width: 280px;position: relative;user-select: none;margin: 0 auto;}
.tip {font-size: clamp(0.95rem, 1.2vw, 0.95rem);color: #555;margin-top: 10px;line-height: 1.4em;}
.dragProgress {width: 0;height: 38px;background: linear-gradient(90deg, #165DFF, #4da1ff, #165DFF);background-size: 200% 100%;
  border-radius: 8px;transition: width 0.2s ease, background-position 1s linear;box-shadow: inset 0 0 6px rgba(22,93,255,0.3);}
.dragBtn {position: absolute;top: 2px;left: 2px;width: 34px;height: 34px;border-radius: 8px;
  background: linear-gradient(145deg, #165DFF, #4da1ff);box-shadow: 0 0 5px rgba(22,93,255,0.5);cursor: pointer;transition: all 0.15s ease;}
.dragBtn:hover {background: linear-gradient(145deg, #4da1ff, #165DFF);box-shadow: 0 0 10px rgba(22,93,255,0.6);}
.fixTips {display: inline-block;width: 100%;line-height: 38px;font-size: clamp(0.78rem, 1.1vw, 0.9rem);
  color: #666;position: absolute;top: 0;left: 0;background: linear-gradient(90deg, #aaa, #333, #aaa);
  -webkit-background-clip: text;-webkit-text-fill-color: transparent;animation: shimmer 2s infinite linear;}
@keyframes shimmer {0% {background-position: -200px 0;}100% {background-position: 200px 0;}}
.sucMsg {display: none;position: absolute;top: 0;width: 100%;line-height: 38px;color: #2e7d32;
  font-size: clamp(0.8rem, 1.2vw, 0.9rem);text-shadow: 0 0 5px rgba(46,125,50,0.4);}
.success .dragProgress {width: 100% !important;background: linear-gradient(90deg, #28a745, #5cff99, #28a745);
  animation: glowMove 2s infinite linear;box-shadow: 0 0 10px rgba(45,255,115,0.5);}
@keyframes glowMove {0% {background-position: 0% 50%;}100% {background-position: 200% 50%;}}
.success .dragBtn {background: linear-gradient(145deg, #28a745, #5cff99);box-shadow: 0 0 10px rgba(45,255,115,0.6);transform: scale(1.05);}
.success .fixTips {display: none;}
.success .sucMsg {display: block;}
.footer {margin-top: 25px;font-size: clamp(1.1rem, 1.1vw, 1.1rem);color: #777;line-height: 1.6em;max-width: 90%;word-wrap: break-word;}
.footer a {color: #165DFF;text-decoration: none;}
.footer a:hover {text-decoration: underline;}
</style>
</head>
<body>
  <img src="<?php echo htmlspecialchars($WebLogo); ?>" alt="Logo" class="logo">
  <h1><?php echo htmlspecialchars($WebTitle); ?></h1>
  <div class="domain" id="domain"><span style="color:#e60000;">网址: </span></div>
  <div id="verify-wrap" class="verify-wrap"></div>
  <p class="tip">👉 向右滑动完成验证 / Slide right to verify</p>
  <div class="footer">
  <?php
      echo !empty($AntiBlockDomain) ? $AntiBlockDomain : '⚠️ 为避免浏览器封禁，请使用防屏蔽入口：<br><a href="https://yutucms.com" target="_blank">https://yutucms.com</a>';
  ?>
  </div>
<script src="/Static/Admin/Js/auth.min.js" charset="utf-8"></script>
<script>
document.getElementById("domain").innerHTML += window.location.hostname;
document.addEventListener("DOMContentLoaded", function() {
  var SlideVerifyPlug = window.slideVerifyPlug;
  if (!SlideVerifyPlug) {
    document.getElementById("verify-wrap").innerHTML =
      "<p style='color:#f66;'>⚠️ 验证模块加载失败，请刷新页面。</p>";
    return;
  }
  var width = (window.innerWidth <= 480) ? 220 : 280;
  new SlideVerifyPlug("#verify-wrap", {
    wrapWidth: width,
    initText: "👉 向右滑动进入 / Slide right to continue",
    sucessText: "✅ 验证通过，正在进入... / Verification passed. Entering...",
    getSuccessState: function () {
      document.querySelector('#verify-wrap').classList.add('success');
      document.cookie = "last_page=" + encodeURIComponent(window.location.href) + ";path=/;max-age=600";
      var xhr = new XMLHttpRequest();
      xhr.open("POST", location.href, true);
      xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
      xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
          try{
            var res = JSON.parse(xhr.responseText);
            if(res.ok){
              // 验证成功：跳转带签名参数
              var sig = "<?php echo md5($token . $secretKey); ?>";
              var u = new URL(window.location.href);
              u.searchParams.set('verify_slide','1');
              u.searchParams.set('sig', sig);
              window.location.href = u.toString();
            } else {
              alert('验证失败');
            }
          }catch(e){ alert('验证异常'); }
        }
      };
      xhr.send("token=" + encodeURIComponent("<?php echo $token; ?>"));
    }
  });
});
</script>
</body>
</html>
