(function () {
    function initModal(wrapper) {
        if (!wrapper || typeof wrapper.classList === 'undefined') return;
        if (wrapper.dataset && wrapper.dataset.modalInit) return;
        wrapper.dataset.modalInit = '1';

        var trigger = wrapper.querySelector('.addonext-modal__trigger');
        var overlay = wrapper.querySelector('.addonext-modal__overlay');
        var closeBtn = wrapper.querySelector('.addonext-modal__close');
        if (!trigger || !overlay) return;

        var countdownSeconds = parseInt(overlay.getAttribute('data-countdown'), 10) || 0;
        var closeOnOverlay = overlay.getAttribute('data-close-on-overlay') !== '0';
        var countdownTimer = null;

        function openModal() {
            overlay.classList.add('is-open');
            document.body.style.overflow = 'hidden';
            if (countdownSeconds > 0) {
                if (countdownTimer) clearTimeout(countdownTimer);
                countdownTimer = setTimeout(closeModal, countdownSeconds * 1000);
            }
        }

        function closeModal() {
            overlay.classList.remove('is-open');
            document.body.style.overflow = '';
            if (countdownTimer) {
                clearTimeout(countdownTimer);
                countdownTimer = null;
            }
        }

        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            openModal();
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                closeModal();
            });
        }

        overlay.addEventListener('click', function (e) {
            if (e.target === overlay && closeOnOverlay) {
                e.preventDefault();
                closeModal();
            }
        });

        var content = overlay.querySelector('.addonext-modal__content');
        if (content) {
            content.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
                closeModal();
            }
        });
    }

    function run() {
        document.querySelectorAll('.addonext-modal').forEach(initModal);
    }

    function observeModalNodes() {
        run();
        var body = document.body;
        if (!body) return;
        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                mutation.addedNodes.forEach(function (node) {
                    if (node.nodeType !== 1) return;
                    if (node.classList && node.classList.contains('addonext-modal')) {
                        initModal(node);
                    }
                    if (node.querySelector) {
                        node.querySelectorAll('.addonext-modal').forEach(initModal);
                    }
                });
            });
        });
        observer.observe(body, { childList: true, subtree: true });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            run();
            observeModalNodes();
        });
    } else {
        run();
        observeModalNodes();
    }

    if (typeof elementor !== 'undefined' && elementor.on) {
        elementor.on('frontend:init', function () {
            run();
        });
    }

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
        elementorFrontend.hooks.addAction('frontend/element_ready/addonext-modal-popup.default', function ($scope) {
            var wrapper = $scope && ($scope[0] != null ? $scope[0] : $scope);
            if (wrapper && wrapper.querySelector) {
                initModal(wrapper);
            }
        });
    }
})();
