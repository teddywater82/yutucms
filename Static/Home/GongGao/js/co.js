$(document).ready(function () {

    // ===============================
    // 🔹 1. 顶部滚动公告（横向滚动）
    // ===============================
    var rollDom = $('.scroll');
    var wideRoll = rollDom.width();
    var marquee = $('.marquee .p');
    var wideMarquee = marquee.width();

    // 克隆两份内容实现无缝滚动
    $('.banner .showcase').append(rollDom.clone(true)).append(rollDom.clone(true));
    $('.marquee').append(marquee.clone(true));

    // 滚动动画
    var begin1 = 0, begin2 = 0;
    setInterval(function () {
        begin1 -= 2;
        rollDom.css('margin-left', begin1);
        if (-begin1 >= wideRoll) begin1 = 0;
    }, 20);

    setInterval(function () {
        begin2 -= 1;
        marquee.css('margin-left', begin2);
        if (-begin2 >= wideMarquee) begin2 = 0;
    }, 20);

    // 点击“screen_roll”显示公告层
    $('.screen_roll').on('click', function () {
        $('.cover,.alert_anounce').fadeIn(200);
    });

    // ===============================
    // 🔹 2. 弹窗显示控制（Cookie 一天只显示一次）
    // ===============================
    var COOKIE_NAME = "showbox";

    if ($.cookie(COOKIE_NAME)) {
        $('.cover,.alert_welcome').hide();
    } else {
        $.cookie(COOKIE_NAME, 'shown', { path: '/', expires: 1 });
        $('.cover,.alert_welcome').fadeIn(300);
    }

    // ===============================
    // 🔹 3. 复制按钮逻辑（优化版提示，无 alert）
    // ===============================
    function showMsg(msg) {
        var tip = $('<div>').text(msg).css({
            position: 'fixed',
            top: '25%',
            left: '50%',
            transform: 'translateX(-50%)',
            background: 'rgba(0,0,0,0.75)',
            color: '#fff',
            padding: '10px 18px',
            borderRadius: '8px',
            fontSize: '14px',
            zIndex: 99999,
            opacity: 0
        }).appendTo('body').animate({ opacity: 1 }, 200);
        setTimeout(() => tip.fadeOut(300, () => tip.remove()), 1500);
    }

    $('#copyUrl').on('click', function (e) {
        e.preventDefault();
        var siteUrl = window.location.origin; // 要复制的网址

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(siteUrl).then(() => {
                showMsg('✅ 已复制网址');
                $('.cover,.alert_welcome').fadeOut(200);
            }).catch(() => {
                showMsg('❌ 复制失败，请手动复制');
            });
        } else {
            var input = $('<input>').val(siteUrl).appendTo('body');
            input[0].select();
            try {
                document.execCommand('copy');
                showMsg('✅ 已复制网址');
            } catch (err) {
                showMsg('❌ 复制失败，请手动复制');
            }
            input.remove();
            $('.cover,.alert_welcome').fadeOut(200);
        }
    });

    // ===============================
    // 🔹 4. 关闭按钮事件
    // ===============================
    $('#close1,.cover').on('click', function () {
        $('.cover,.alert_welcome').fadeOut(200);
    });

});
