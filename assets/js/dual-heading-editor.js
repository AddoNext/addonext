(function ($) {
    'use strict';

    var hooksBound = false;

    function isEditMode() {
        return (
            typeof elementorFrontend !== 'undefined' &&
            typeof elementorFrontend.isEditMode === 'function' &&
            elementorFrontend.isEditMode()
        );
    }

    function bindHighlightInner(el) {
        if (!el || el.getAttribute('data-veloria-dual-hl-bound') === '1') {
            return;
        }
        el.setAttribute('data-veloria-dual-hl-bound', '1');
        el.addEventListener('focusin', function () {
            if (isEditMode()) {
                el.classList.add('veloria-dual-heading__highlight-inner--is-editing');
            }
        });
        el.addEventListener('focusout', function () {
            el.classList.remove('veloria-dual-heading__highlight-inner--is-editing');
        });
    }

    function scan(scope) {
        if (!isEditMode()) {
            return;
        }
        var $root = scope ? $(scope) : $(document);
        $root.find('.veloria-dual-heading__highlight-inner').each(function () {
            bindHighlightInner(this);
        });
    }

    function bindHooks() {
        if (hooksBound) {
            return;
        }
        if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks || !elementorFrontend.hooks.addAction) {
            return;
        }
        hooksBound = true;
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/veloria-dual-heading.default',
            function ($scope) {
                if ($scope && $scope[0]) {
                    scan($scope[0]);
                }
            }
        );
        if (isEditMode()) {
            scan(document);
        }
    }

    $(window).on('elementor/frontend/init', bindHooks);

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
        bindHooks();
    }
})(jQuery);
