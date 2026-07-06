(function ($) {
    "use strict";

    var docCloseBound = false;

    function closeOpenSiblings(li) {
        var parent = li.parentElement;
        if (!parent) {
            return;
        }
        parent.querySelectorAll(":scope > .menu-item.is-open").forEach(function (sibling) {
            if (sibling !== li) {
                sibling.classList.remove("is-open");
            }
        });
    }

    function openParentChainForItem(li, nav) {
        var cur = li;
        while (cur && cur !== nav) {
            var parentUl = cur.parentElement;
            if (!parentUl || !parentUl.classList.contains("addonext-nav-menu__sub")) {
                break;
            }
            var parentLi = parentUl.parentElement;
            if (!parentLi || !parentLi.classList.contains("menu-item-has-children")) {
                break;
            }
            closeOpenSiblings(parentLi);
            parentLi.classList.add("is-open");
            cur = parentLi;
        }
    }

    function bindDocumentClose() {
        if (docCloseBound) {
            return;
        }
        docCloseBound = true;
        document.addEventListener("click", function (e) {
            document.querySelectorAll(".addonext-nav-menu").forEach(function (nav) {
                var mobile = nav.classList.contains("addonext-nav-menu--layout-mobile");
                var trigger = nav.getAttribute("data-submenu-trigger") || "hover";
                var closeOnOutside = mobile || (trigger === "click" && !mobile);
                if (!closeOnOutside) {
                    return;
                }
                if (!nav.contains(e.target)) {
                    nav.querySelectorAll(".menu-item.is-open").forEach(function (el) {
                        el.classList.remove("is-open");
                    });
                }
            });
        });
    }

    function bindNav(nav) {
        if (nav.getAttribute("data-addonext-nav-bound") === "1") {
            return;
        }
        nav.setAttribute("data-addonext-nav-bound", "1");

        var bp = parseInt(nav.getAttribute("data-mobile-breakpoint") || "921", 10);
        if (bp < 320) {
            bp = 320;
        }

        function updateLayout() {
            if (window.innerWidth <= bp) {
                nav.classList.add("addonext-nav-menu--layout-mobile");
            } else {
                nav.classList.remove("addonext-nav-menu--layout-mobile");
                nav.querySelectorAll(".menu-item.is-open").forEach(function (el) {
                    el.classList.remove("is-open");
                });
            }
        }

        nav.addEventListener("click", function (e) {
            var mobile = nav.classList.contains("addonext-nav-menu--layout-mobile");
            var trigger = nav.getAttribute("data-submenu-trigger") || "hover";
            if (!mobile && trigger !== "click") {
                return;
            }
            var anchor = e.target.closest("a");
            if (!anchor || !nav.contains(anchor)) {
                return;
            }
            var li = anchor.closest(".menu-item");
            if (!li || !li.classList.contains("menu-item-has-children")) {
                return;
            }
            var sub = li.querySelector(":scope > .addonext-nav-menu__sub");
            if (!sub) {
                return;
            }
            if (!li.classList.contains("is-open")) {
                e.preventDefault();
                closeOpenSiblings(li);
                li.classList.add("is-open");
            }
        });

        nav.addEventListener("focusin", function (e) {
            if (nav.classList.contains("addonext-nav-menu--layout-mobile")) {
                return;
            }
            if ((nav.getAttribute("data-submenu-trigger") || "hover") !== "click") {
                return;
            }
            var t = e.target;
            if (!nav.contains(t) || t === nav) {
                return;
            }
            var li = t.closest(".menu-item");
            if (!li || !nav.contains(li)) {
                return;
            }
            if (li.classList.contains("menu-item-has-children")) {
                var directA = li.querySelector(":scope > a");
                if (directA && (t === directA || directA.contains(t))) {
                    closeOpenSiblings(li);
                    li.classList.add("is-open");
                }
            }
            openParentChainForItem(li, nav);
        });

        window.addEventListener("resize", updateLayout);
        updateLayout();
        bindDocumentClose();
    }

    function initScope(scope) {
        var root = scope && scope.length ? scope[0] : document;
        root.querySelectorAll(".addonext-nav-menu").forEach(bindNav);
    }

    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/addonext-navigation-menu.default",
            initScope
        );
        elementorFrontend.hooks.addAction("frontend/element_ready/addonext-navigation-menu", initScope);
    });

    document.querySelectorAll(".elementor-widget-addonext-navigation-menu").forEach(function (widget) {
        widget.querySelectorAll(".addonext-nav-menu").forEach(bindNav);
    });
})(jQuery);
