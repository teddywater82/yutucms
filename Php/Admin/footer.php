<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.sidebar');
    const toggleSidebar = document.getElementById('toggleSidebar');
    const mobileToggle = document.getElementById('mobileToggle');
    const menus = document.querySelectorAll('.menu-item[id$="-menu"]');
    const submenuLinks = document.querySelectorAll('.submenu-item');
    const homeLink = document.querySelector('.menu-item[href*="Home/index"]');

    /* ===== LocalStorage 工具 ===== */
    const saveState = (menuId, open) => {
        const state = JSON.parse(localStorage.getItem('activeMenus') || '{}');
        state[menuId] = open;
        localStorage.setItem('activeMenus', JSON.stringify(state));
    };
    const getState = () => JSON.parse(localStorage.getItem('activeMenus') || '{}');
    const setActiveLink = (href) => localStorage.setItem('activeLink', href);
    const getActiveLink = () => localStorage.getItem('activeLink');

    /* ===== 动画展开/收起函数 ===== */
    const slideToggle = (el, open) => {
        el.style.overflow = 'hidden';
        el.style.transition = 'max-height 0.3s ease';
        if (open) {
            el.style.display = 'flex';
            el.style.maxHeight = el.scrollHeight + 'px';
            setTimeout(() => el.style.maxHeight = '', 300);
        } else {
            el.style.maxHeight = el.scrollHeight + 'px';
            setTimeout(() => {
                el.style.maxHeight = '0';
                setTimeout(() => el.style.display = 'none', 300);
            });
        }
    };

    /* ===== 折叠按钮 ===== */
    toggleSidebar?.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        const icon = toggleSidebar.querySelector('i');
        icon.classList.toggle('fa-chevron-right', sidebar.classList.contains('collapsed'));
        icon.classList.toggle('fa-chevron-left', !sidebar.classList.contains('collapsed'));
    });

    /* ===== 移动端按钮 ===== */
    mobileToggle?.addEventListener('click', () => {
        sidebar.classList.toggle('mobile-open');
        sidebar.classList.remove('collapsed');
    });

    /* ===== PHP 激活项标记 ===== */
    document.querySelectorAll('.menu-item.active').forEach(el => el.dataset.phpActive = 'true');

    /* ===== 恢复菜单展开状态与选中项 ===== */
    const restoreState = () => {
        const state = getState();
        for (const [menuId, open] of Object.entries(state)) {
            const menu = document.getElementById(menuId + '-menu');
            const submenu = document.getElementById(menuId + '-submenu');
            if (menu && submenu) {
                submenu.style.display = open ? 'flex' : 'none';
                submenu.classList.toggle('active', open);
                menu.classList.toggle('active', open);
                menu.querySelector('.fa-angle-down')?.classList.toggle('rotate-180', open);
            }
        }

        const activeHref = getActiveLink();
        if (activeHref) {
            // 二级菜单选中恢复
            document.querySelectorAll('.submenu-item').forEach(link => {
                if (link.getAttribute('href') === activeHref) {
                    link.classList.add('selected');
                    const parentSub = link.closest('.submenu');
                    const parentMenu = parentSub?.previousElementSibling;
                    if (parentSub && parentMenu) {
                        parentSub.classList.add('active');
                        parentMenu.classList.add('active');
                        parentMenu.querySelector('.fa-angle-down')?.classList.add('rotate-180');
                        saveState(parentMenu.id.replace('-menu', ''), true);
                    }
                }
            });
            // 一级菜单选中恢复（友链管理等）
            document.querySelectorAll('.menu-item[href]').forEach(link => {
                if (link.getAttribute('href') === activeHref) {
                    link.classList.add('selected');
                }
            });
        }
    };
    restoreState();

    /* ===== 菜单展开 / 收起（支持多开） ===== */
    menus.forEach(menu => {
        const menuId = menu.id.replace('-menu', '');
        const submenu = document.getElementById(menuId + '-submenu');
        if (!submenu) return;

        menu.addEventListener('click', e => {
            e.preventDefault();
            e.stopPropagation();
            const isOpen = submenu.classList.contains('active');
            submenu.classList.toggle('active', !isOpen);
            menu.classList.toggle('active', !isOpen);
            menu.querySelector('.fa-angle-down')?.classList.toggle('rotate-180', !isOpen);
            saveState(menuId, !isOpen);
            slideToggle(submenu, !isOpen);
        });
    });

    /* ===== 点击子菜单项：高亮 + 记忆 ===== */
    submenuLinks.forEach(link => {
        link.addEventListener('click', () => {
            document.querySelectorAll('.submenu-item.selected').forEach(el => el.classList.remove('selected'));
            document.querySelectorAll('.menu-item[href]').forEach(el => el.classList.remove('selected'));
            link.classList.add('selected');
            setActiveLink(link.getAttribute('href'));
        });
    });

    /* ===== 一级菜单（如友链管理）点击高亮 + 记忆 ===== */
    const directLinks = document.querySelectorAll('.menu-item[href]');
    directLinks.forEach(link => {
        link.addEventListener('click', () => {
            document.querySelectorAll('.menu-item[href]').forEach(el => el.classList.remove('selected'));
            document.querySelectorAll('.submenu-item.selected').forEach(el => el.classList.remove('selected'));
            link.classList.add('selected');
            setActiveLink(link.getAttribute('href'));
        });
    });

    /* ===== 点击“首页”：收起所有菜单并清除选中状态 ===== */
    if (homeLink) {
        homeLink.addEventListener('click', () => {
            localStorage.removeItem('activeMenus');
            localStorage.removeItem('activeLink');
            document.querySelectorAll('.submenu').forEach(sub => {
                sub.classList.remove('active');
                sub.style.display = 'none';
            });
            document.querySelectorAll('.menu-item[id$="-menu"]').forEach(menu => {
                menu.classList.remove('active');
                menu.querySelector('.fa-angle-down')?.classList.remove('rotate-180');
            });
            document.querySelectorAll('.submenu-item.selected, .menu-item[href].selected').forEach(el => el.classList.remove('selected'));
        });
    }

    /* ===== 响应式控制 ===== */
    let resizeTimer;
    const handleResize = () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (window.innerWidth >= 992) sidebar.classList.remove('mobile-open');
        }, 150);
    };
    window.addEventListener('resize', handleResize);
});
</script>

<style>
    @media (max-width: 992px) {
            .sidebar {
                left: -100%;
            }

            .sidebar.mobile-open {
                left: 255px;
            }

            .header {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }

            .sidebar.collapsed ~ .header,
            .sidebar.collapsed ~ .main-content {
                left: 0;
                margin-left: 0;
            }
        }
</style>
