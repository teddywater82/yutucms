<?php
date_default_timezone_set('PRC');
$flag_file = __DIR__ . '/../JCSQL.JCSQL';
$today = date('Ymd');
$need_update = true;
if (file_exists($flag_file)) {
    $last = trim(file_get_contents($flag_file));
    if ($last === $today) $need_update = false;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>YUTUCMS 数据更新中</title>
<style>
body {
  font-family: "Microsoft YaHei", sans-serif;
  background: #fff;
  color: #333;
  text-align: center;
  padding-top: 120px;
}
.container {
  width: 90%;
  max-width: 600px;
  margin: auto;
}
h1 {
  font-size: 22px;
  margin-bottom: 20px;
}
.progress {
  width: 100%;
  height: 26px;
  background: #f0f0f0;
  border-radius: 13px;
  overflow: hidden;
  box-shadow: inset 0 0 5px rgba(0,0,0,.1);
}
.bar {
  height: 100%;
  width: 0%;
  background: linear-gradient(90deg, #1aad19, #00c853);
  transition: width .3s ease-in-out;
}
.status {
  font-size: 17px;
  margin-top: 18px;
  color: #444;
  word-break: break-word;
  white-space: pre-wrap;
}
small {
  color: #888;
  display: block;
  margin-top: 12px;
}
.loader {
  display: inline-block;
  width: 22px;
  height: 22px;
  border: 3px solid #ccc;
  border-top-color: #1aad19;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  vertical-align: middle;
  margin-right: 8px;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.flash {
  animation: flash 1s ease-in-out infinite alternate;
}
@keyframes flash {
  from {opacity: 1;}
  to {opacity: .4;}
}
.success-box, .error-box {
  text-align: left;
  padding: 14px;
  margin-top: 16px;
  border-radius: 6px;
  font-size: 15px;
  line-height: 1.6;
}
.success-box {
  background: #f1fff5;
  color: #2e7d32;
  border: 1px solid #a5d6a7;
}
.error-box {
  background: #fff5f5;
  color: #c62828;
  border: 1px solid #ffcdd2;
}
</style>
</head>
<body>
<div class="container">
<?php if ($need_update): ?>
  <h1><span class="loader"></span> 正在更新数据，请稍候...</h1>
  <div class="progress"><div class="bar" id="bar"></div></div>
  <div class="status" id="status">连接更新服务器中...</div>
  <small>请不要关闭此页面，更新完成后将自动返回首页</small>

  <script>
  const bar = document.getElementById('bar');
  const status = document.getElementById('status');
  let progress = 0;

  const fake = setInterval(() => {
    if (progress < 90) {
      progress += Math.random() * 6;
      if (progress > 90) progress = 90;
      bar.style.width = progress + '%';
    }
  }, 400);

  fetch('API.php?t=' + Date.now())
    .then(r => r.text())
    .then(tx => {
      clearInterval(fake);

      if (/Permission|denied|权限|拒绝/i.test(tx)) {
        bar.style.background = '#f44336';
        status.innerHTML = `<div class="error-box">❌ 权限错误<br>系统无法写入更新文件，请检查 JCSQL 目录权限（建议 755）</div>`;
        return;
      }

      if (/失败|错误|error/i.test(tx)) {
        bar.style.background = '#f44336';
        status.innerHTML = `<div class="error-box">⚠️ 更新失败<br>${tx}</div>`;
        return;
      }

      bar.style.width = '100%';
      bar.style.background = 'linear-gradient(90deg,#00bfa5,#00c853)';
      status.innerHTML = `<div class="success-box flash">✅ 数据更新完成，正在跳转首页...</div>`;
      setTimeout(() => { location.href = '/' }, 2000);
    })
    .catch(() => {
      clearInterval(fake);
      bar.style.background = '#f44336';
      status.innerHTML = `<div class="error-box">⚠️ 无法连接更新服务器，请稍后重试。</div>`;
    });
  </script>
<?php else: ?>
  <h1>✅ 今日数据已是最新版本</h1>
  <div class="success-box flash">系统检测到当前数据无需更新，将自动跳转首页...</div>
  <script>setTimeout(()=>{location.href='/'},1500);</script>
<?php endif; ?>
</div>
</body>
</html>

