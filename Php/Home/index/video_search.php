<?php
error_reporting(0);
ini_set('memory_limit', '1024M');
set_time_limit(0);
date_default_timezone_set('Asia/Shanghai');

/*
=========================================================
🚀 玉兔 CMS 视频搜索系统 （纯文件缓存版）
兼容 PHP 5.6 及以上版本
不依赖 APCu，仅使用文件缓存 + flock 锁
作者：ChatGPT 优化版 2025.10
=========================================================
*/

$cache_dir  = './cache/search/';
$full_file  = $cache_dir . 'name_index.json';   // 全字段缓存
$light_file = $cache_dir . 'name_search.json';  // 轻索引，仅 d_name
$lock_file  = $cache_dir . 'refresh.lock';

if (!is_dir($cache_dir)) @mkdir($cache_dir, 0777, true);

// ------------------------------------------------------
// 每天 00:30 自动重建缓存
// ------------------------------------------------------
$today        = date('Ymd');
$nowHM        = date('H:i');
$last_refresh = is_file($lock_file) ? trim(@file_get_contents($lock_file)) : '';

if ($nowHM >= '00:30' && $last_refresh !== $today) {
    @unlink($full_file);
    @unlink($light_file);
    @file_put_contents($lock_file, $today);
}

// ------------------------------------------------------
// 安全读写 JSON （带 flock）
// ------------------------------------------------------
function safe_read_json($file) {
    if (!is_file($file)) return null;
    $fp = fopen($file, 'r');
    if (!$fp) return null;
    $json = '';
    if (flock($fp, LOCK_SH)) {
        $json = stream_get_contents($fp);
        flock($fp, LOCK_UN);
    }
    fclose($fp);
    return json_decode($json, true);
}

function safe_write_json($file, $data) {
    $fp = fopen($file, 'c+');
    if (!$fp) return false;
    if (flock($fp, LOCK_EX)) {
        ftruncate($fp, 0);
        fwrite($fp, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        fflush($fp);
        flock($fp, LOCK_UN);
    }
    fclose($fp);
    return true;
}

// ------------------------------------------------------
// 检测系统内存（仅用于参考）
// ------------------------------------------------------
function system_memory_mb() {
    $mem = 0;
    if (stristr(PHP_OS, 'Linux')) {
        $data = @file_get_contents("/proc/meminfo");
        if (preg_match('/MemTotal:\s+(\d+)/i', $data, $m)) {
            $mem = intval($m[1] / 1024); // MB
        }
    } else {
        $mem = 512;
    }
    return $mem;
}
$sys_mem = system_memory_mb();

// ------------------------------------------------------
// 从文件加载缓存数据
// ------------------------------------------------------
$index = null;
$full  = null;

if (is_file($light_file)) {
    $index = safe_read_json($light_file);
}
if (is_file($full_file)) {
    $full = safe_read_json($full_file);
}

// ------------------------------------------------------
// 若缓存不存在或损坏则自动重建
// ------------------------------------------------------
if (empty($index) || !file_exists($full_file)) {
    $root = __DIR__;
    $found = false;
    for ($i = 0; $i < 9; $i++) {
        if (is_dir($root . '/JCSQL/Home')) { $found = true; break; }
        $root = dirname($root);
    }
    $data_dir = $found ? ($root . '/JCSQL/Home/') : './JCSQL/Home/';

    $uubb  = array();
    $index = array();

    for ($x = 1; $x <= 32; $x++) {
        $file_path = $data_dir . $x . '.txt';
        if (!is_file($file_path)) continue;
        $data = json_decode(file_get_contents($file_path), true);
        if (!is_array($data)) continue;

        foreach ($data as $v) {
            $uubb[] = $v;
            $text = isset($v['d_name']) ? $v['d_name'] : '';
            $index[] = array('text' => $text);
        }
    }

    safe_write_json($full_file,  $uubb);
    safe_write_json($light_file, $index);

    @file_put_contents($cache_dir . 'refresh.log',
        date('Y-m-d H:i:s') . " 自动重建缓存，共 " . count($uubb) . " 条数据\n", FILE_APPEND);

    $full = $uubb;
    unset($uubb);
}

// ------------------------------------------------------
// 安全过滤
// ------------------------------------------------------
if (!function_exists('clean_xss')) {
    function clean_xss($str, $trim = false) {
        if ($trim) $str = trim($str);
        return htmlspecialchars(strip_tags($str), ENT_QUOTES, 'UTF-8');
    }
}

$GetMb_id   = isset($GetMb_id) ? clean_xss($GetMb_id, true) : '';
$GetMb_page = isset($GetMb_page) ? intval($GetMb_page) : 1;

// ------------------------------------------------------
// 懒加载搜索函数（仅文件缓存）
// ------------------------------------------------------
function search_lazy($keyword, $index, $full, $index_file, $full_file)
{
    $result = array();
    if ($keyword === '') return $result;

    if (empty($index)) $index = safe_read_json($index_file);
    if (empty($full))  $full  = safe_read_json($full_file);
    if (!is_array($index) || !is_array($full)) return $result;

    $pattern = '/' . preg_quote($keyword, '/') . '/i';
    foreach ($index as $i => $row) {
        if (!empty($row['text']) && preg_match($pattern, $row['text'])) {
            if (isset($full[$i])) $result[] = $full[$i];
        }
    }
    return $result;
}

// ------------------------------------------------------
// 执行搜索
// ------------------------------------------------------
$MYSQLVODS = search_lazy($GetMb_id, $index, $full, $light_file, $full_file);

// ------------------------------------------------------
// 无结果提示
// ------------------------------------------------------
if (empty($MYSQLVODS)) {
    $safe_kw = addslashes($GetMb_id);
    echo '<script>alert("抱歉！未找到 “' . $safe_kw . '” 的结果！");window.location.href="?";</script>';
    exit();
}

// ------------------------------------------------------
// 输出模板变量
// ------------------------------------------------------
$count = count($MYSQLVODS) - 1;
$tpl->assign('SearchTypeJCSQL', $MYSQLVODS);
$tpl->assign('SearchTypePage', $GetMb_page);
$tpl->assign('SearchTypeId', $GetMb_id);
$tpl->assign('SearchTypeName', $GetMb_id);

$tpl->show($this_WebMoban . '/html/' . $GetMb_tmp);
?>
