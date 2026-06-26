<?php
/**
 * =====================================================
 * YUTUCMS 主入口 (安全防屏蔽防绕过版)
 * - 防止 ?verify_slide=1 手动绕过
 * - 验证签名 Cookie（timestamp|HMAC）
 * - 兼容 PHP 5.6
 * =====================================================
 */

/*** 基础模块加载 ***/
include('./Php/Public/Mysql.php');
include('./Php/Public/Page.php');
include('./Php/Public/Helper.php');
include('./Php/Home/JCSQL.php');

/*** 读取系统配置 ***/
$AdminBasic = json_decode(file_get_contents("./JCSQL/Admin/Basic/AdminBasic.php"), true);
$AntiBlockOpen = isset($AdminBasic['AntiBlockOpen']) ? $AdminBasic['AntiBlockOpen'] : '0';

/*** 安全密钥（必须与 anti_block_verify.php 一致） ***/
$secretKey = 'yutucms_secure_key_2025';

/*** PHP5.6 兼容函数 ***/
if (!function_exists('hash_equals')) {
    function hash_equals($a, $b) {
        if (!is_string($a) || !is_string($b)) return false;
        $len = strlen($a);
        if ($len !== strlen($b)) return false;
        $res = $a ^ $b;
        $ret = 0;
        for ($i = $len - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
        return !$ret;
    }
}

/*** 验证签名 Cookie ***/
function verifyAntiBlockCookie($cookie, $secretKey) {
    if (empty($cookie)) return false;
    $parts = explode('|', $cookie);
    if (count($parts) !== 2) return false;

    $ts = $parts[0];
    $hash = $parts[1];

    // 时间格式验证
    if (!ctype_digit($ts)) return false;

    // 超过 24 小时失效
    if (time() - (int)$ts > 86400) return false;

    // 签名验证
    $expect = hash_hmac('sha256', $ts, $secretKey);
    return hash_equals($expect, $hash);
}

/*** 过滤可疑 UA ***/
function allowHumanOrSpider($ua) {
    if (empty($ua)) return false;
    $ua = strtolower($ua);
    $allow = array('chrome','safari','firefox','edge','opera','micromessenger','ucbrowser','baiduboxapp','mqqbrowser','alipay','quark');
    foreach ($allow as $a) if (strpos($ua,$a)!==false) return true;
    $bots = array('googlebot','baiduspider','bingbot','bytespider','sogou','petalbot','yandex','duckduckbot');
    foreach ($bots as $b) if (strpos($ua,$b)!==false) return true;
    $deny = array('curl','python','wget','aiohttp','requests','okhttp','gptbot','claudebot','anthropic','postman','datadog','powershell');
    foreach ($deny as $d) if (strpos($ua,$d)!==false) return false;
    return true;
}

/*** 主逻辑入口 ***/
if ($AntiBlockOpen == '1') {
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

    // 拒绝异常爬虫
    if (!allowHumanOrSpider($ua)) {
        header('HTTP/1.1 403 Forbidden');
        exit('Access Denied.');
    }

    // 检查签名 cookie
    $cookie = isset($_COOKIE['anti_block_verified']) ? $_COOKIE['anti_block_verified'] : '';
    $isValid = verifyAntiBlockCookie($cookie, $secretKey);

    // 不合法则进入验证页
    if (!$isValid) {
        include('./Php/Home/anti_block_verify.php');
        exit;
    }
}

/*** =================== 加载核心模块 =================== ***/
include('./Php/Home/GET.php');
include('./Php/Home/PCorwap.php');
include('./Php/Home/mysql.php');
include('./Php/Home/IeUrl.php');
include('./Php/Home/Ad.php');
include('./Php/Home/Host.php');
include('./Php/Home/index.php');
?>
