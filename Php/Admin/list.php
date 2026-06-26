<div class="sidebar">
  <div class="sidebar-header">
 <div class="logo">
    <img src="/images/logo.png" alt="YUTUCMS" class="logo-img">
    <span class="logo-text"></span>
</div>

    <button class="toggle-btn" id="toggleSidebar" style="display:none;">
      <i class="fa fa-chevron-left"></i>
    </button>
  </div>

  <div class="sidebar-menu">
    <a href="?Php=Home/index" class="menu-item">
      <i class="fa fa-home"></i><span class="menu-text">首页</span>
    </a>

    <!-- 支付设置 -->
    <div class="menu-item <?php echo $MODULE == 'Payment' ? 'active' : '' ?>" id="payment-menu" data-menu-id="payment">
      <i class="fa fa-credit-card"></i><span class="menu-text">支付设置</span>
      <i class="fa fa-angle-down ml-auto <?php echo $MODULE == 'Payment' ? 'rotate-180' : '' ?>"></i>
    </div>
    <div class="submenu <?php echo $MODULE == 'Payment' ? 'active' : '' ?>" id="payment-submenu">
      <a href="./?Php=Home/Payment/PaymentSetup" class="submenu-item <?php echo $MODULE_FILE == 'PaymentSetup' ? 'active' : '' ?>"><i class="fa fa-angle-right"></i><span>支付接口配置</span></a>
    </div>

    <!-- 基本设置 -->
    <div class="menu-item <?php echo $MODULE == 'Basic' ? 'active' : '' ?>" id="basic-menu" data-menu-id="basic">
      <i class="fa fa-cogs"></i><span class="menu-text">基本设置</span>
      <i class="fa fa-angle-down ml-auto <?php echo $MODULE == 'Basic' ? 'rotate-180' : '' ?>"></i>
    </div>
    <div class="submenu <?php echo $MODULE == 'Basic' ? 'active' : '' ?>" id="basic-submenu">
      <a href="./?Php=Home/Basic/Basicsetup" class="submenu-item <?php echo $MODULE_FILE == 'Basicsetup' ? 'active' : '' ?>"><i class="fa fa-angle-right"></i><span>基本设置</span></a>
      <a href="./?Php=Home/Basic/Statistics" class="submenu-item <?php echo $MODULE_FILE == 'Statistics' ? 'active' : '' ?>"><i class="fa fa-angle-right"></i><span>流量统计设置</span></a>
    </div>

    <!-- 安全管理 -->
    <div class="menu-item <?php echo $MODULE == 'Security' ? 'active' : '' ?>" id="security-menu" data-menu-id="security">
      <i class="fa fa-shield"></i><span class="menu-text">安全管理</span>
      <i class="fa fa-angle-down ml-auto <?php echo $MODULE == 'Security' ? 'rotate-180' : '' ?>"></i>
    </div>
    <div class="submenu <?php echo $MODULE == 'Security' ? 'active' : '' ?>" id="security-submenu">
      <a href="./?Php=Home/Security/UserPass" class="submenu-item <?php echo $MODULE_FILE == 'UserPass' ? 'active' : '' ?>"><i class="fa fa-angle-right"></i><span>登录重置</span></a>
    </div>

    <!-- 广告设置 -->
    <div class="menu-item <?php echo ($MODULE == 'Ad' && $MODULE_FILE != 'IeUrl') ? 'active' : '' ?>" id="ad-menu" data-menu-id="ad">
      <i class="fa fa-bullhorn"></i><span class="menu-text">广告设置</span>
      <i class="fa fa-angle-down ml-auto <?php echo ($MODULE == 'Ad' && $MODULE_FILE != 'IeUrl') ? 'rotate-180' : '' ?>"></i>
    </div>
    <div class="submenu <?php echo ($MODULE == 'Ad' && $MODULE_FILE != 'IeUrl') ? 'active' : '' ?>" id="ad-submenu">
      <a href="./?Php=Home/Ad/Top" class="submenu-item <?php echo $MODULE_FILE == 'Top' ? 'active' : '' ?>"><i class="fa fa-angle-right"></i><span>头部横幅广告</span></a>
      <a href="./?Php=Home/Ad/Video" class="submenu-item <?php echo $MODULE_FILE == 'Video' ? 'active' : '' ?>"><i class="fa fa-angle-right"></i><span>详情与内容横幅广告</span></a>
      <a href="./?Php=Home/Ad/Couplets" class="submenu-item <?php echo $MODULE_FILE == 'Couplets' ? 'active' : '' ?>"><i class="fa fa-angle-right"></i><span>对联展现广告</span></a>
      <a href="./?Php=Home/Ad/Float" class="submenu-item <?php echo $MODULE_FILE == 'Float' ? 'active' : '' ?>"><i class="fa fa-angle-right"></i><span>底部浮漂广告</span></a>
      <a href="./?Php=Home/Ad/AdJs" class="submenu-item <?php echo $MODULE_FILE == 'AdJs' ? 'active' : '' ?>"><i class="fa fa-angle-right"></i><span>广告联盟JS广告</span></a>
    </div>

    <!-- 扩展管理 -->
    <?php if (isset($plug_menu)): ?>
    <div class="menu-item <?php echo $MODULE == 'Zhanqun' ? 'active' : '' ?>" id="plugin-menu" data-menu-id="plugin">
      <i class="fa fa-puzzle-piece"></i><span class="menu-text">扩展管理</span>
      <i class="fa fa-angle-down ml-auto <?php echo $MODULE == 'Zhanqun' ? 'rotate-180' : '' ?>"></i>
    </div>
    <div class="submenu <?php echo $MODULE == 'Zhanqun' ? 'active' : '' ?>" id="plugin-submenu">
      <?php foreach ($plug_menu as $plugin): ?>
      <a href="./?Php=<?php echo $plugin['plug_path']?>" class="submenu-item <?php echo $MODULE_FILE == 'index' ? 'active' : '' ?>"><i class="fa fa-angle-right"></i><span><?php echo $plugin['plug_name']?></span></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <a href="./?Php=Home/Ad/IeUrl" class="menu-item <?php echo $MODULE_FILE == 'IeUrl' ? 'active' : '' ?>">
      <i class="fa fa-link"></i><span class="menu-text">友链管理</span>
      <span class="menu-badge">友链</span>
    </a>

    <a href="https://yutucms.com/template.html" target="_blank" class="menu-item">
      <i class="fa fa-object-group"></i><span class="menu-text">模板中心</span>
    </a>

    <a href="https://yutucms.com/tutorial.html" target="_blank" class="menu-item">
      <i class="fa fa-question-circle"></i><span class="menu-text">帮助中心</span>
    </a>
  </div>
</div>

<style>
/* === 侧边栏整体 === */
.sidebar {
  width: 260px;
  background: #fff;
  color: #333;
  height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
  display: flex;
  flex-direction: column;
  border-right: 1px solid #e8e8e8;
  box-shadow: 2px 0 6px rgba(0,0,0,0.05);
  overflow: hidden;
  font-family: "Microsoft YaHei","PingFang SC",sans-serif;
  transition: all .3s ease;
}
.sidebar.collapsed { width: 70px; }

/* 顶部栏 */
.sidebar-header {
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 18px;
  border-bottom: 1px solid #eee;
  background: #fafafa;
}
.logo { display: flex; align-items: center; gap: 8px; }
.logo i { color: #165DFF; font-size: 18px; }
.logo-text { font-weight: 600; color: #222; }

/* 滚动菜单 */
.sidebar-menu {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding-bottom: 12px;
}
.sidebar-menu::-webkit-scrollbar { width: 6px; }
.sidebar-menu::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 3px; }

/* 菜单项 */
.menu-item, .submenu-item {
  display: flex;
  align-items: center;
  padding: 10px 18px;
  color: #555;
  text-decoration: none;
  transition: all .25s ease;
  font-size: 14px;
  position: relative;
  background: #fff;
  border-left: 3px solid transparent;
}
.menu-item:hover, .submenu-item:hover {
  background: #f4f7ff;
  color: #165DFF;
}
.menu-item.active {
  background: linear-gradient(to right, #eef4ff, #fff);
  color: #165DFF;
  font-weight: 600;
  border-left: 3px solid #165DFF;
}

/* 图标 */
.menu-item i.fa:first-child, .submenu-item i.fa:first-child {
  width: 22px;
  text-align: center;
  margin-right: 8px;
  font-size: 15px;
  color: #777;
}
.menu-item.active i.fa:first-child { color: #165DFF; }
.menu-item .fa-angle-down {
  margin-left: auto;
  font-size: 12px;
  color: #999;
  transition: transform .3s ease;
}
.fa.rotate-180 { transform: rotate(180deg); color: #165DFF; }

/* 子菜单 */
.submenu {
  display: none;
  flex-direction: column;
  background: #fafafa;
  border-left: 1px solid #eee;
  margin-left: 3px;
  transition: all .3s ease;
}
.submenu.active { display: flex; }
.submenu-item {
  font-size: 13px;
  color: #666;
  padding: 9px 20px 9px 40px;
  transition: all .35s ease;
}
.submenu-item.selected {
  background: linear-gradient(to right, #e6eeff, #fff);
  color: #165DFF;
  font-weight: 600;
  box-shadow: inset 0 0 4px rgba(22,93,255,0.08);
}

/* 友链徽章 */
.menu-badge {
  background: #165DFF;
  color: #fff;
  border-radius: 10px;
  font-size: 10px;
  padding: 2px 6px;
  margin-left: auto;
}

/* 折叠模式 */
.sidebar.collapsed .menu-text,
.sidebar.collapsed .menu-badge,
.sidebar.collapsed .fa-angle-down { display: none; }
.sidebar.collapsed .menu-item,
.sidebar.collapsed .submenu-item { justify-content: center; }

/* Tooltip 提示 */
.sidebar.collapsed .menu-item:hover::after {
  content: attr(title);
  position: absolute;
  left: 70px;
  background: rgba(0,0,0,0.85);
  color: #fff;
  font-size: 12px;
  padding: 4px 8px;
  border-radius: 4px;
  white-space: nowrap;
  top: 50%;
  transform: translateY(-50%);
}
/* 一级菜单选中状态 */
.menu-item.selected {
    background: linear-gradient(to right, #e8f0ff, #ffffff);
    color: #165DFF;
    font-weight: 600;
    transition: background 0.3s ease, color 0.3s ease;
}

/* 子菜单选中状态 */
.submenu-item.selected {
    background: linear-gradient(to right, #e6efff, #ffffff);
    color: #165DFF;
    font-weight: 600;
    transition: background 0.3s ease, color 0.3s ease;
}
/* ===== Logo 样式优化 ===== */
.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
    color: var(--primary-color);
    white-space: nowrap;
}

/* LOGO 图片 — 固定尺寸，比例自适应 */
.logo-img {
    width: 150px;        /* 宽度可调 */
    height: 50px;        /* 高度可调 */
    object-fit: contain; /* 保持图片比例不拉伸 */
    user-select: none;
}

/* LOGO 文字 — 保留品牌识别 */
.logo-text {
    font-size: 18px;
    font-weight: 600;
    color: var(--primary-color);
    letter-spacing: 0.5px;
}

/* 在侧边栏收起时隐藏文字，仅显示图标 */
.sidebar.collapsed .logo-text {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

/* 移动端自适应 */
@media (max-width: 768px) {
    .logo-img {
        width: 120px;
        height: 40px;
    }
}

</style>
