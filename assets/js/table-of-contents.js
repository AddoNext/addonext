(function () {
    'use strict';

    function normalizeText(str) {
        return String(str || '')
            .replace(/\s+/g, ' ')
            .trim()
            .toLowerCase();
    }

    function findElementByText(doc, excludeRoot, selector, needle) {
        var n = normalizeText(needle);
        if (!n) {
            return null;
        }
        var nodes = doc.querySelectorAll(selector);
        for (var i = 0; i < nodes.length; i += 1) {
            var el = nodes[i];
            if (excludeRoot && excludeRoot.contains(el)) {
                continue;
            }
            var txt = normalizeText(el.textContent || '');
            if (txt.indexOf(n) !== -1 || txt === n) {
                return el;
            }
        }
        return null;
    }

    function getScrollOffset(nav) {
        var raw = nav.getAttribute('data-scroll-offset');
        var n = parseInt(raw, 10);
        if (isNaN(n) || n < 0) {
            return 96;
        }
        return n;
    }

    function getTextSelector(nav) {
        var s = nav.getAttribute('data-text-selector');
        return s && s.trim() !== '' ? s.trim() : 'h1, h2, h3, h4, h5, h6';
    }

    function resolveTarget(nav, link) {
        var type = link.getAttribute('data-toc-type');
        if (type === 'id') {
            var id = link.getAttribute('data-target-id');
            if (!id) {
                return null;
            }
            return document.getElementById(id);
        }
        if (type === 'text') {
            var match = link.getAttribute('data-match-text');
            return findElementByText(document, nav, getTextSelector(nav), match);
        }
        return null;
    }

    function scrollToElement(el, offset) {
        if (!el || !el.getBoundingClientRect) {
            return;
        }
        var rect = el.getBoundingClientRect();
        var y = window.scrollY + rect.top - offset;
        window.scrollTo({ top: Math.max(0, y), behavior: 'smooth' });
    }

    function clearActive(nav) {
        var prev = nav.querySelectorAll('.is-active');
        for (var i = 0; i < prev.length; i += 1) {
            prev[i].classList.remove('is-active');
        }
        var groups = nav.querySelectorAll('.is-active-descendant');
        for (var g = 0; g < groups.length; g += 1) {
            groups[g].classList.remove('is-active-descendant');
        }
    }

    function setActiveLink(nav, link) {
        clearActive(nav);
        if (!link) {
            return;
        }
        link.classList.add('is-active');
        var group = link.closest('.veloria-toc__group');
        if (group) {
            group.classList.add('is-active-descendant');
        }
    }

    function pickActiveEntry(entries, offset) {
        var best = null;
        var bestTop = -Infinity;
        var y = window.scrollY + offset + 2;
        for (var i = 0; i < entries.length; i += 1) {
            var item = entries[i];
            if (!item.el) {
                continue;
            }
            var top = item.el.getBoundingClientRect().top + window.scrollY;
            if (top <= y && top >= bestTop) {
                bestTop = top;
                best = item;
            }
        }
        return best;
    }

    function destroyNav(nav) {
        if (!nav || !nav._veloriaToc) {
            return;
        }
        var h = nav._veloriaToc;
        window.removeEventListener('scroll', h.onScroll, false);
        window.removeEventListener('resize', h.onScroll, false);
        nav.removeEventListener('click', h.onClick);
        delete nav._veloriaToc;
        nav.removeAttribute('data-toc-initialized');
    }

    function initNav(nav) {
        if (!nav) {
            return;
        }
        destroyNav(nav);
        nav.setAttribute('data-toc-initialized', '1');

        var offset = getScrollOffset(nav);
        var links = nav.querySelectorAll('[data-toc-type="id"], [data-toc-type="text"]');
        var entries = [];

        for (var i = 0; i < links.length; i += 1) {
            var link = links[i];
            var el = resolveTarget(nav, link);
            entries.push({ link: link, el: el });
        }

        function onClick(ev) {
            var target = ev.target.closest('[data-toc-type]');
            if (!target || !nav.contains(target)) {
                return;
            }
            var type = target.getAttribute('data-toc-type');
            if (type !== 'id' && type !== 'text') {
                return;
            }
            var dest = resolveTarget(nav, target);
            if (!dest) {
                if (type === 'id') {
                    return;
                }
                ev.preventDefault();
                return;
            }
            ev.preventDefault();
            if (type === 'id') {
                var id = target.getAttribute('data-target-id');
                if (id && history.pushState) {
                    history.pushState(null, '', '#' + id);
                }
            }
            scrollToElement(dest, offset);
        }

        nav.addEventListener('click', onClick);

        var ticking = false;
        function onScroll() {
            if (ticking) {
                return;
            }
            ticking = true;
            window.requestAnimationFrame(function () {
                ticking = false;
                var resolved = [];
                for (var j = 0; j < entries.length; j += 1) {
                    var item = entries[j];
                    var el = item.el;
                    if (!el || !document.body.contains(el)) {
                        el = resolveTarget(nav, item.link);
                        item.el = el;
                    }
                    if (el) {
                        resolved.push(item);
                    }
                }
                var active = pickActiveEntry(resolved, offset);
                if (active) {
                    setActiveLink(nav, active.link);
                } else {
                    clearActive(nav);
                }
            });
        }

        window.addEventListener('scroll', onScroll, false);
        window.addEventListener('resize', onScroll, false);
        nav._veloriaToc = { onScroll: onScroll, onClick: onClick };
        onScroll();
    }

    function scan(root) {
        var scope = root || document;
        var list = scope.querySelectorAll('.veloria-toc');
        for (var i = 0; i < list.length; i += 1) {
            initNav(list[i]);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            scan(document);
        });
    } else {
        scan(document);
    }

    function bindElementor() {
        if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks || !elementorFrontend.hooks.addAction) {
            return;
        }
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/veloria-table-of-contents.default',
            function ($scope) {
                var el = $scope && $scope[0] ? $scope[0] : null;
                if (!el) {
                    return;
                }
                var nodes = el.querySelectorAll('.veloria-toc');
                for (var i = 0; i < nodes.length; i += 1) {
                    initNav(nodes[i]);
                }
            }
        );
    }

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
        bindElementor();
    } else {
        window.addEventListener('load', function () {
            setTimeout(bindElementor, 0);
        });
    }
})();
