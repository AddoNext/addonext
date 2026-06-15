(function () {
    'use strict';

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function replaceInPlain(segments, regex, type) {
        var out = [];
        for (var i = 0; i < segments.length; i++) {
            if (segments[i].type !== 'plain') {
                out.push(segments[i]);
                continue;
            }
            var text = segments[i].text;
            var lastIndex = 0;
            regex.lastIndex = 0;
            var match;
            while ((match = regex.exec(text)) !== null) {
                if (match.index > lastIndex) {
                    out.push({ type: 'plain', text: text.slice(lastIndex, match.index) });
                }
                out.push({ type: type, text: match[0] });
                lastIndex = regex.lastIndex;
            }
            if (lastIndex < text.length) {
                out.push({ type: 'plain', text: text.slice(lastIndex) });
            }
        }
        return out;
    }

    function segmentsToHtml(segments) {
        return segments.map(function (s) {
            return '<span class="veloria-cs_' + s.type + '">' + escapeHtml(s.text) + '</span>';
        }).join('');
    }

    function highlightJavaScript(code) {
        var keywords = ['function', 'return', 'let', 'const', 'var', 'for', 'if', 'else', 'while', 'do',
            'switch', 'case', 'break', 'default', 'try', 'catch', 'finally', 'throw', 'new',
            'typeof', 'instanceof', 'in', 'of', 'true', 'false', 'null', 'undefined', 'class',
            'extends', 'import', 'export', 'from', 'async', 'await', 'static', 'get', 'set'];
        var builtins = ['length', 'push', 'pop', 'log', 'parseInt', 'parseFloat', 'toString'];
        var segments = [{ type: 'plain', text: code }];
        segments = replaceInPlain(segments, /(["'])(?:(?!\1)[^\\]|\\.)*\1/g, 'string');
        segments = replaceInPlain(segments, /\/\/[^\n]*/g, 'comment');
        segments = replaceInPlain(segments, /\/\*[\s\S]*?\*\//g, 'comment');
        segments = replaceInPlain(segments, /\b(\d+\.?\d*)\b/g, 'number');
        keywords.forEach(function (kw) {
            var re = new RegExp('\\b(' + kw.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')\\b', 'g');
            segments = replaceInPlain(segments, re, 'keyword');
        });
        builtins.forEach(function (b) {
            var re = new RegExp('\\b(' + b.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')\\b', 'g');
            segments = replaceInPlain(segments, re, 'builtin');
        });
        segments = replaceInPlain(segments, /\b([a-zA-Z_$][a-zA-Z0-9_$]*)(?=\s*\()/g, 'funct');
        var notKwBi = keywords.concat(builtins);
        var idRe = new RegExp('\\b(?!' + notKwBi.map(function (w) { return w.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }).join('|') + '\\b)([a-zA-Z_$][a-zA-Z0-9_$]*)\\b', 'g');
        segments = replaceInPlain(segments, idRe, 'identifier');
        return segmentsToHtml(segments);
    }

    function highlightPlain(code) {
        return segmentsToHtml([{ type: 'plain', text: code }]);
    }

    function stripHtmlToText(html) {
        var div = document.createElement('div');
        div.innerHTML = html;
        return div.textContent || div.innerText || '';
    }

    function highlight(block) {
        var codeEl = block.querySelector('.veloria-code-snippet__code');
        var displayEl = block.querySelector('.veloria-code-snippet__display');
        if (!codeEl || !displayEl) return;
        var lang = codeEl.getAttribute('data-language') || 'plain';
        var raw = codeEl.textContent || '';
        if (raw.indexOf('veloria-cs_') !== -1 || raw.indexOf('<span') !== -1) {
            raw = stripHtmlToText(raw);
        }
        if (!raw.trim()) return;
        var html = lang === 'javascript' ? highlightJavaScript(raw) : highlightPlain(raw);
        displayEl.innerHTML = html;
    }

    function run() {
        scan(document);
    }

    function setCopiedState(btn, copied) {
        btn.classList.toggle('copied', copied);
        var copyIcon = btn.querySelector('.veloria-code-snippet__copy-icon');
        var checkIcon = btn.querySelector('.veloria-code-snippet__check-icon');
        if (copyIcon) copyIcon.style.opacity = copied ? '0' : '';
        if (checkIcon) checkIcon.style.opacity = copied ? '1' : '0';
    }

    function initCopy(block) {
        var btn = block.querySelector('.veloria-code-snippet__copy');
        var codeEl = block.querySelector('.veloria-code-snippet__code');
        if (!btn || !codeEl) return;
        if (btn.dataset.copyInit) return;
        btn.dataset.copyInit = '1';
        btn.addEventListener('click', function () {
            var text = codeEl.textContent || '';
            if (!navigator.clipboard || !navigator.clipboard.writeText) {
                fallbackCopy(text, btn);
                return;
            }
            navigator.clipboard.writeText(text).then(function () {
                setCopiedState(btn, true);
                setTimeout(function () { setCopiedState(btn, false); }, 2000);
            }).catch(function () {
                fallbackCopy(text, btn);
            });
        });
    }

    function fallbackCopy(text, btn) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.left = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        try {
            document.execCommand('copy');
            setCopiedState(btn, true);
            setTimeout(function () { setCopiedState(btn, false); }, 2000);
        } catch (e) {}
        document.body.removeChild(ta);
    }

    function initBlock(block) {
        if (block.dataset.codeSnippetInit) return;
        block.dataset.codeSnippetInit = '1';
        highlight(block);
        initCopy(block);
    }

    function scan(root) {
        root = root || document;
        var blocks = root.querySelectorAll('.veloria-code-snippet');
        blocks.forEach(initBlock);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            run();
            var observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (m) {
                    m.addedNodes.forEach(function (node) {
                        if (node.nodeType !== 1) return;
                        if (node.classList && node.classList.contains('veloria-code-snippet')) {
                            initBlock(node);
                        }
                        if (node.querySelectorAll) {
                            node.querySelectorAll('.veloria-code-snippet').forEach(initBlock);
                        }
                    });
                });
            });
            if (document.body) {
                observer.observe(document.body, { childList: true, subtree: true });
            }
        });
    } else {
        run();
        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (m) {
                m.addedNodes.forEach(function (node) {
                    if (node.nodeType !== 1) return;
                    if (node.classList && node.classList.contains('veloria-code-snippet')) {
                        initBlock(node);
                    }
                    if (node.querySelectorAll) {
                        node.querySelectorAll('.veloria-code-snippet').forEach(initBlock);
                    }
                });
            });
        });
        if (document.body) {
            observer.observe(document.body, { childList: true, subtree: true });
        }
    }

    window.addEventListener('load', run);

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
        elementorFrontend.hooks.addAction('frontend/element_ready/veloria-code-snippet.default', function ($scope) {
            var container = $scope && $scope[0] !== undefined ? $scope[0] : $scope;
            if (container && container.querySelectorAll) {
                scan(container);
            }
        });
    }
})();
