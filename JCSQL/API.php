<?php
/**
 * YUTUCMS 自动更新核心程序（静默版 + flock 文件锁 + 自动标志写入修复）
 * PHP 5.6 兼容版（去除 ?? 操作符）
 * Version: 2025.10 Final Fixed Edition (php56)
 */

ignore_user_abort(true);
set_time_limit(0);
ob_start();
date_default_timezone_set('PRC');

$log_dir  = __DIR__ . '/tmp';
$log_file = $log_dir . '/update.log';
if (!is_dir($log_dir)) { mkdir($log_dir, 0777, true); }

// ---------- flock 文件锁 ----------
$lock_file = __DIR__ . '/update.lock';
$fp = fopen($lock_file, 'c+');
if (!$fp) {
    file_put_contents($log_file, "[错误] 无法创建锁文件：$lock_file\n", FILE_APPEND);
    exit;
}
if (!flock($fp, LOCK_EX | LOCK_NB)) {
    file_put_contents($log_file, "[警告] 已有更新任务在执行中，退出。\n", FILE_APPEND);
    fclose($fp);
    exit;
}
// ----------------------------------

file_put_contents($log_file, "=== YUTUCMS 自动更新开始 " . date('Y-m-d H:i:s') . " ===\n", FILE_APPEND);

// ---------- 1. 读取 API.KEY ----------
$jurl_encoded = @file_get_contents(__DIR__ . '/API.KEY');
if (!$jurl_encoded) {
    file_put_contents($log_file, "[错误] 未找到 API.KEY\n", FILE_APPEND);
    flock($fp, LOCK_UN); fclose($fp);
    exit;
}

$jurl = $jurl_encoded;
for ($i = 1; $i <= 9; $i++) {
    $jurl = base64_decode($jurl);
    if (strpos($jurl, 'http') === 0) break;
}
if (!$jurl || stripos($jurl, 'http') === false) {
    file_put_contents($log_file, "[错误] API.KEY 无效\n", FILE_APPEND);
    flock($fp, LOCK_UN); fclose($fp);
    exit;
}

function check_url_status($url) {
    $host = $_SERVER['HTTP_HOST'];

    $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_NOBODY         => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 8,
        CURLOPT_HTTPHEADER     => [
            'Referer: https://' . $host, 
            'Origin: https://' . $host 
        ]
    ));
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $code;
}

if (check_url_status($jurl) != 200) {
    $flag_file = __DIR__ . '/../JCSQL.JCSQL';
    if (!is_dir(dirname($flag_file))) { mkdir(dirname($flag_file), 0777, true); }
    file_put_contents($flag_file, date('Ymd'));
    file_put_contents($log_file, "[警告] 远程API不可访问，已直接标记为已更新。\n", FILE_APPEND);
    flock($fp, LOCK_UN); fclose($fp);
    exit;
}

// ---------- 4. 加载下载模块 ----------
include_once __DIR__ . '/JURL.php';

// ---------- 5. 获取远程配置 ----------
$json     = @file_get_contents($jurl);
$jurljson = json_decode($json, true);
if (!$jurljson) {
    file_put_contents($log_file, "[错误] 无法解析远程配置: $jurl\n", FILE_APPEND);
    flock($fp, LOCK_UN); fclose($fp);
    exit;
}

$version_text = isset($jurljson['version']) ? $jurljson['version'] : '未知';
file_put_contents($log_file, "[信息] 检测到版本: " . $version_text . "\n", FILE_APPEND);

// ---------- 6. 下载主包 ----------
if (!empty($jurljson['zip']) && is_array($jurljson['zip'])) {
    $v = isset($jurljson['version']) ? $jurljson['version'] : '';
    foreach ($jurljson['zip'] as $z) {
        GETZIP($v, $z, $log_file);
    }
} else {
    file_put_contents($log_file, "[信息] 无需更新主包。\n", FILE_APPEND);
}

// ---------- 7. 下载补丁 ----------
if (!empty($jurljson['bug'])) {
    $bug = isset($jurljson['bug']) ? $jurljson['bug'] : '';
    BUG($bug, $log_file);
} else {
    file_put_contents($log_file, "[信息] 无补丁更新。\n", FILE_APPEND);
}

// ---------- 8. 写入更新状态 ----------
if (!is_dir(__DIR__ . '/Home')) { mkdir(__DIR__ . '/Home', 0777, true); }
file_put_contents(__DIR__ . '/Home/API.CEY', $json);

// realpath 返回 false 时使用右侧路径（?: 在 PHP 5.3+ 可用）
$flag_file = realpath(__DIR__ . '/../JCSQL.JCSQL');
$flag_file = $flag_file ? $flag_file : (__DIR__ . '/../JCSQL.JCSQL');

if (!is_dir(dirname($flag_file))) { mkdir(dirname($flag_file), 0777, true); }
if (file_put_contents($flag_file, date('Ymd')) === false) {
    file_put_contents($log_file, "[错误] 无法写入标志文件：$flag_file\n", FILE_APPEND);
} else {
    file_put_contents($log_file, "[完成] 已更新标志文件日期为 " . date('Ymd') . "\n", FILE_APPEND);
}

// ---------- 9. 释放文件锁 ----------
flock($fp, LOCK_UN);
fclose($fp);

file_put_contents($log_file, "[完成] 更新完成于 " . date('H:i:s') . "\n", FILE_APPEND);
exit;
?>
