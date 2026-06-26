<?php
/**
 * YUTUCMS 更新函数模块（静默+日志版）
 */

function GETZIP($version, $zipUrl, $log_file) {
    $name = basename($zipUrl);
    file_put_contents($log_file, "[下载] {$name}\n", FILE_APPEND);

    $remote = @fopen($zipUrl, 'rb');
    if (!$remote) {
        file_put_contents($log_file, "[错误] 无法访问 {$zipUrl}\n", FILE_APPEND);
        return;
    }
    if (!is_dir(__DIR__ . '/tmp')) mkdir(__DIR__ . '/tmp');
    $tmp = __DIR__ . '/tmp/' . $name;
    $local = fopen($tmp, 'wb');

    while (!feof($remote)) {
        fwrite($local, fread($remote, 1024 * 512)); // 每次512KB
    }

    fclose($remote);
    fclose($local);

    include_once(__DIR__ . '/Zip.php');
    $zip = new Zip();
    $zip->extra($tmp, __DIR__ . '/Home/');
    unlink($tmp);

    file_put_contents($log_file, "[完成] 解压 {$name}\n", FILE_APPEND);
}

function BUG($bugUrl, $log_file) {
    file_put_contents($log_file, "[补丁] {$bugUrl}\n", FILE_APPEND);

    $fp = @fopen($bugUrl, 'rb');
    if (!$fp) {
        file_put_contents($log_file, "[错误] 无法下载补丁包 {$bugUrl}\n", FILE_APPEND);
        return;
    }

    if (!is_dir(__DIR__ . '/tmp')) mkdir(__DIR__ . '/tmp');
    $tmp = __DIR__ . '/tmp/' . date('YmdHis') . '.zip';
    $local = fopen($tmp, 'wb');

    while (!feof($fp)) fwrite($local, fread($fp, 1024 * 512));
    fclose($fp);
    fclose($local);

    include_once(__DIR__ . '/Zip.php');
    $zip = new Zip();
    $zip->extra($tmp, '../');
    unlink($tmp);

    file_put_contents($log_file, "[完成] 补丁更新完成。\n", FILE_APPEND);
}
?>
