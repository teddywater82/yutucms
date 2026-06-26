/**
 * YUTUCMS 视频播放器 - 含15秒免费试看 + 付费功能
 */
function initVideo(param) {
    var videoH5Id = '#' + param.id + '_html5_api',
        ua = navigator.userAgent.toLocaleLowerCase(),
        mobileOn = ua.match(/(phone|pad|pod|iPhone|iPod|ios|iPad|Android|Mobile|BlackBerry|IEMobile|MQQBrowser|JUC|Fennec|wOSBrowser|BrowserNG|WebOS|Symbian|Windows Phone)/i),
        
        // 支付配置
        payment = param.payment || {},
        payEnabled = String(payment.enabled) === '1' || String(payment.enabled) === 'true' || payment.enabled === true,
        freeSeconds = parseInt(payment.freeSeconds) || 15,
        payPrice = payment.price || '9.90',
        payTitle = payment.title || '观看完整视频',
        payDesc = payment.desc || '试看后需付费',
        orderUrl = payment.orderUrl || '',
        checkPaidUrl = payment.checkPaidUrl || '',
        videoId = payment.videoId || '',
        videoName = payment.videoName || '',
        videoPage = payment.videoPage || '',
        
        // 播放器状态
        isFreePeriod = true,
        freeTimer = null,
        freeElapsed = 0,
        paid = false,
        playerPausedByPay = false,
        payCheckInterval = null;

    // 创建视频播放器
    var videoObj = videojs(param.id, {
        sources: [{ src: param.url, type: 'application/x-mpegURL' }],
        width: '100%',
        height: '100%',
        controls: true,
        autoplay: false,
        techOrder: ['html5'],
        controlBar: { remainingTimeDisplay: false }
    });

    // 获取UI元素
    var freeCountdown = document.getElementById('freeCountdown');
    var countdownNum = document.getElementById('countdownNum');
    var paymentOverlay = document.getElementById('paymentOverlay');
    var paymentSuccessOverlay = document.getElementById('paymentSuccessOverlay');
    var btnPayNow = document.getElementById('btnPayNow');

    if (mobileOn) {
        videoObj.addClass('mobile');
    } else {
        var keyListen = {
            speed: 5,
            resetSpeed: 1,
            showMove: videojs.dom.createEl('div', {}, { class: 'show-move' }),
            direct: 0
        };
        document.onkeydown = function(event) {
            var e = event || window.event || arguments.callee.caller.arguments[0];
            if (e && (e.keyCode == 39 || e.keyCode == 37 || e.keyCode == 32)) { e.preventDefault(); }
            if (e.keyCode == 32) { videoObj.paused() ? videoObj.play() : videoObj.pause(); }
            if (e && e.keyCode == 39 && !videoObj.paused()) {
                if (keyListen.direct < 0) { keyListen.speed = 5; }
                keyListen.direct = 1; keyListen.speed += 5;
                videoObj.currentTime(videoObj.currentTime() + keyListen.speed);
                keyListen.showMove.innerHTML = '+' + keyListen.speed;
                videojs.dom.appendContent(videoObj.el_, keyListen.showMove);
            }
            if (e && e.keyCode == 37 && !videoObj.paused()) {
                if (keyListen.direct > 0) { keyListen.speed = 5; }
                keyListen.direct = -1; keyListen.speed += 5;
                videoObj.currentTime(videoObj.currentTime() - keyListen.speed);
                keyListen.showMove.innerHTML = '-' + keyListen.speed;
                videojs.dom.appendContent(videoObj.el_, keyListen.showMove);
            }
            if (keyListen.resetSpeed) { clearTimeout(keyListen.resetSpeed); }
            keyListen.resetSpeed = setTimeout(function() { keyListen.speed = 5; }, 500);
        };
    }

    // ==================== 付费逻辑 ====================

    /**
     * 解析父页面URL参数
     */
    function getParentUrlParams() {
        try {
            if (window.parent && window.parent.location) {
                var search = window.parent.location.search || '';
                return search;
            }
        } catch(e) {}
        return '';
    }

    /**
     * 检查是否已经支付过（通过URL参数或localStorage）
     */
    function checkAlreadyPaid() {
        // 从URL参数检查
        try {
            var parentSearch = getParentUrlParams();
            if (parentSearch.indexOf('paid=1') !== -1) {
                paid = true;
                return true;
            }
        } catch(e) {}

        // 检查当前URL
        if (window.location.search.indexOf('paid=1') !== -1) {
            paid = true;
            return true;
        }

        // 检查localStorage
        if (videoId) {
            var paidKey = 'yutucms_paid_' + videoId;
            try {
                if (localStorage.getItem(paidKey) === '1') {
                    paid = true;
                    return true;
                }
            } catch(e) {}
        }

        return false;
    }

    /**
     * 开始免费试看计时
     */
    function startFreeCountdown() {
        if (!payEnabled || paid) return;
        
        isFreePeriod = true;
        freeElapsed = 0;
        
        // 显示倒计时
        if (freeCountdown) {
            freeCountdown.classList.add('active');
            if (countdownNum) countdownNum.textContent = freeSeconds;
        }
        
        // 每秒更新
        freeTimer = setInterval(function() {
            freeElapsed++;
            var remaining = freeSeconds - freeElapsed;
            
            if (countdownNum) countdownNum.textContent = Math.max(0, remaining);
            
            if (freeElapsed >= freeSeconds) {
                // 免费时间到，暂停播放并显示支付
                clearInterval(freeTimer);
                freeTimer = null;
                isFreePeriod = false;
                
                if (freeCountdown) freeCountdown.classList.remove('active');
                
                // 暂停视频
                if (!videoObj.paused()) {
                    playerPausedByPay = true;
                    videoObj.pause();
                }
                
                // 显示支付弹窗
                showPaymentOverlay();
            }
        }, 1000);
    }

    /**
     * 显示支付覆盖层
     */
    function showPaymentOverlay() {
        if (!paymentOverlay) return;
        
        // 构建支付链接
        var payUrl = orderUrl;
        if (videoId || videoName) {
            payUrl += '?';
            if (videoId) payUrl += 'video_id=' + encodeURIComponent(videoId) + '&';
            if (videoName) payUrl += 'video_name=' + encodeURIComponent(videoName) + '&';
            if (videoPage) payUrl += 'video_page=' + encodeURIComponent(videoPage);
            payUrl = payUrl.replace(/[&?]$/, '');
        }
        
        if (btnPayNow) {
            btnPayNow.href = payUrl;
            btnPayNow.textContent = '💳 立即支付 ¥' + payPrice;
        }
        
        // 移动端特殊处理：退出全屏模式
        if (mobileOn) {
            try {
                // 尝试退出全屏
                if (document.fullscreenElement || document.webkitFullscreenElement) {
                    if (document.exitFullscreen) document.exitFullscreen();
                    else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
                }
                // 阻止视频重新播放
                videoObj.pause();
            } catch(e) {}
        }
        
        paymentOverlay.classList.add('active');
        
        // 开始轮询支付状态
        startPaymentPolling();
    }

    /**
     * 轮询支付状态
     */
    function startPaymentPolling() {
        if (payCheckInterval) clearInterval(payCheckInterval);
        
        payCheckInterval = setInterval(function() {
            if (!videoId) return;
            
            var xhr = new XMLHttpRequest();
            var checkUrl = checkPaidUrl + '?video_id=' + encodeURIComponent(videoId) + '&_t=' + Date.now();
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        var resp = JSON.parse(xhr.responseText);
                        if (resp.paid === true || resp.paid === '1' || resp.paid === 1) {
                            onPaymentSuccess();
                        }
                    } catch(e) {}
                }
            };
            xhr.open('GET', checkUrl, true);
            xhr.send();
        }, 3000); // 每3秒轮询
    }

    /**
     * 支付成功处理
     */
    function onPaymentSuccess() {
        paid = true;
        
        // 停止轮询
        if (payCheckInterval) {
            clearInterval(payCheckInterval);
            payCheckInterval = null;
        }
        
        // 隐藏支付弹窗
        if (paymentOverlay) paymentOverlay.classList.remove('active');
        
        // 显示支付成功提示
        if (paymentSuccessOverlay) paymentSuccessOverlay.classList.add('active');
        
        // 保存到localStorage
        if (videoId) {
            try { localStorage.setItem('yutucms_paid_' + videoId, '1'); } catch(e) {}
        }
        
        // 1.5秒后隐藏成功提示，继续播放
        setTimeout(function() {
            if (paymentSuccessOverlay) paymentSuccessOverlay.classList.remove('active');
            
            // 继续播放
            try { videoObj.play(); } catch(e) {}
            playerPausedByPay = false;
        }, 1500);
    }



    // ==================== 初始化 ====================
    
    // 检查是否已支付
    checkAlreadyPaid();
    
    // 手机端：监听退出全屏事件，如果免费期已过需要显示支付弹窗
    if (mobileOn) {
        videoObj.on('webkitendfullscreen', function() {
            // 退出全屏后，如果免费期已过但支付弹窗没显示，显示它
            if (payEnabled && !paid && !isFreePeriod && paymentOverlay && !paymentOverlay.classList.contains('active')) {
                setTimeout(function() {
                    showPaymentOverlay();
                }, 500);
            }
        });
        videoObj.on('fullscreenchange', function() {
            if (payEnabled && !paid && !isFreePeriod && paymentOverlay && !paymentOverlay.classList.contains('active')) {
                setTimeout(function() {
                    showPaymentOverlay();
                }, 500);
            }
        });
    }

    // 备用方案：通过 timeupdate 检测播放进度自动启动倒计时（解决部分手机浏览器 play 事件不触发问题）
    videoObj.on('timeupdate', function() {
        if (payEnabled && !paid && isFreePeriod && !freeTimer) {
            var ct = videoObj.currentTime();
            if (ct > 0.5) {
                // 视频已在播放但 timer 没启动，手动启动
                startFreeCountdown();
            }
        }
        
        // 如果免费期已过但支付弹窗没显示（防止手机端漏掉）
        if (payEnabled && !paid && !isFreePeriod && !freeTimer && paymentOverlay && !paymentOverlay.classList.contains('active')) {
            var ct = videoObj.currentTime();
            if (ct >= freeSeconds) {
                videoObj.pause();
                showPaymentOverlay();
            }
        }
    });
    
    // 监听播放事件 - 免费倒计时
    videoObj.on('play', function() {
        if (payEnabled && !paid) {
            if (isFreePeriod && !freeTimer) {
                // 首次启动倒计时
                startFreeCountdown();
            } else if (isFreePeriod && freeTimer) {
                // 如果暂停时 timer 被清除了但免费期未过，重启
                // 不做特殊处理，继续使用已有timer
            }
        }
        
        // 移除暂停广告
        if (videojs.dom.$('.pasuAD')) {
            var adCover = videojs.dom.$('.pasuAD');
            if (adCover && adCover.parentNode) {
                adCover.parentNode.removeChild(adCover);
            }
        }
    });
    
    // 暂停时，暂停倒计时
    videoObj.on('pause', function() {
        if (payEnabled && !paid && isFreePeriod && freeTimer) {
            clearInterval(freeTimer);
            freeTimer = null;
        }
        
        // 如果是因为支付弹窗暂停的，不显示暂停广告
        if (playerPausedByPay) return;
        
        // 暂停广告逻辑（原有）
        if (param.ad && param.ad.pause && param.ad.pause.url && !(ua.indexOf('sogou') > -1 && ua.indexOf('mobile') > -1)) {
            if (mobileOn && videojs.dom.$(videoH5Id)) {
                videojs.dom.$(videoH5Id).style.left = '-100%';
            }
            var pauAD = {
                adCover: videojs.dom.createEl('a', {
                    style: "position:absolute;bottom:3em;left:0;top:3em;right:0;text-align:center;",
                    href: param.ad.pause.link ? param.ad.pause.link : 'javascript:void(0);'
                }, { class: "pasuAD", target: "_blank" },
                    videojs.dom.createEl('img', { style: "max-width:100%; max-height:100%;", src: param.ad.pause.url })
                )
            };
            videojs.dom.appendContent(videoObj.el_, pauAD.adCover);
        }
    });

    // 广告（如有）
    if (param.ad && param.ad.pre && param.ad.pre.url) {
        videoObj.ads({ timeout: 10000 });
        var preAD = {
            preAdLink: function() { window.open(param.ad.pre.link); videoObj.pause(); },
            skip: videojs.dom.createEl('div', {}, { class: 'adskip' },
                mobileOn ? [videojs.dom.createEl('span', {}, {}, '查看详情'), videojs.dom.createEl('span', {}, {}, '跳过广告')] : videojs.dom.createEl('span', {}, {}, '跳过广告')
            ),
            closeAD: null
        };
        videoObj.on('readyforpreroll', function() {
            videoObj.ads.startLinearAdMode();
            videoObj.src(param.ad.pre.url);
            videoObj.one('adplaying', function() {
                clearTimeout(preAD.closeAD);
                videoObj.trigger('ads-ad-started');
                videojs.dom.appendContent(videoObj.el_, preAD.skip);
                videoObj.on(videoObj.children_[0], 'click', preAD.preAdLink);
                if (mobileOn) {
                    videoObj.el_.parentNode.style.paddingBottom = '28px';
                    videoObj.on(videoObj.children_[0], 'touchend', preAD.preAdLink);
                }
                videoObj.on(preAD.skip, 'click', function() {
                    videoObj.ads.endLinearAdMode();
                    preAD.preAdLink();
                    videoObj.el_.removeChild(preAD.skip);
                    videoObj.off(videoObj.children_[0], 'click', preAD.preAdLink);
                    videoObj.off(videoObj.children_[0], 'touchend', preAD.preAdLink);
                    if (mobileOn) { videoObj.el_.parentNode.style.paddingBottom = '0'; }
                });
            });
            videoObj.one('adended', function() {
                videoObj.ads.endLinearAdMode();
                videoObj.off(videoObj.children_[0], 'click', preAD.preAdLink);
                videoObj.off(videoObj.children_[0], 'touchend', preAD.preAdLink);
                videoObj.el_.removeChild(preAD.skip);
                videoObj.el_.parentNode.style.paddingBottom = '0';
            });
        });
        videoObj.trigger('adsready');
        preAD.closeAD = setTimeout(function() { videoObj.ads.endLinearAdMode(); }, 15000);
    }

    // 暂停广告
    if (param.ad && param.ad.pause && param.ad.pause.url && !(ua.indexOf('sogou') > -1 && ua.indexOf('mobile') > -1)) {
        // 暂停广告已包含在上面的pause事件中
    }

    // Logo
    if (param.logo && param.logo.url) {
        var logo = {
            dom: videojs.dom.createEl('img', {
                style: 'position:absolute;top:1em;right:1em;z-index:10;width:' + param.logo.width,
                src: param.logo.url
            })
        };
        videojs.dom.appendContent(videoObj.el_, logo.dom);
    }

    // 如果用户通过paid参数进入，直接标记为已支付
    if (paid) {
        if (freeCountdown) freeCountdown.classList.remove('active');
        if (paymentOverlay) paymentOverlay.classList.remove('active');
    }

    return videoObj;
}