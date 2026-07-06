(function () {
    'use strict';

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function splitWordsPreserve(text) {
        if (!text) {
            return [];
        }
        return text.split(/(\s+)/u).filter(function (p) {
            return p.length > 0;
        });
    }

    function buildRevealHtml(text, split) {
        if (split === 'char') {
            return Array.from(text)
                .map(function (ch, i) {
                    return (
                        '<span class="addonext-animated-text__word addonext-animated-text__char" style="--word-index: ' +
                        i +
                        ';">' +
                        escapeHtml(ch) +
                        '</span>'
                    );
                })
                .join('');
        }
        var parts = splitWordsPreserve(text);
        var index = 0;
        var html = '';
        for (var j = 0; j < parts.length; j += 1) {
            var part = parts[j];
            if (/^\s+$/.test(part)) {
                html += '<span class="addonext-animated-text__space">' + escapeHtml(part) + '</span>';
            } else {
                html +=
                    '<span class="addonext-animated-text__word" style="--word-index: ' +
                    index +
                    ';">' +
                    escapeHtml(part) +
                    '</span>';
                index += 1;
            }
        }
        return html;
    }

    function getRevealDelay(text, split, duration, stagger) {
        if (split === 'char') {
            var n = Array.from(text).length;
            return duration + stagger * Math.max(0, n - 1) + 100;
        }
        var parts = splitWordsPreserve(text);
        var wc = 0;
        for (var i = 0; i < parts.length; i += 1) {
            if (!/^\s+$/.test(parts[i])) {
                wc += 1;
            }
        }
        return duration + stagger * Math.max(0, wc - 1) + 100;
    }

    function parsePhrases(el) {
        var raw = el.getAttribute('data-animated-phrases');
        if (!raw) {
            return [];
        }
        try {
            var arr = JSON.parse(raw);
            if (Array.isArray(arr)) {
                return arr
                    .map(function (p) {
                        return String(p || '');
                    })
                    .filter(function (p) {
                        return p.length > 0;
                    });
            }
        } catch (e) {
            return [];
        }
        return [];
    }

    function clearTypewriter(el) {
        if (el._addonextAtTypeInterval) {
            clearInterval(el._addonextAtTypeInterval);
            el._addonextAtTypeInterval = null;
        }
        if (el._addonextAtLoopTimeout) {
            clearTimeout(el._addonextAtLoopTimeout);
            el._addonextAtLoopTimeout = null;
        }
        if (el._addonextAtStartTimeout) {
            clearTimeout(el._addonextAtStartTimeout);
            el._addonextAtStartTimeout = null;
        }
        if (el._addonextAtRevealTimeout) {
            clearTimeout(el._addonextAtRevealTimeout);
            el._addonextAtRevealTimeout = null;
        }
        if (el._addonextAtSqTimeout) {
            clearTimeout(el._addonextAtSqTimeout);
            el._addonextAtSqTimeout = null;
        }
        if (el._addonextAtSqStartTimeout) {
            clearTimeout(el._addonextAtSqStartTimeout);
            el._addonextAtSqStartTimeout = null;
        }
    }

    function disconnectSquare(el) {
        clearTypewriter(el);
        if (el._addonextAtSqIo) {
            el._addonextAtSqIo.disconnect();
            el._addonextAtSqIo = null;
        }
        el._addonextSqGen = (el._addonextSqGen || 0) + 1;
        var frame = el.querySelector('.addonext-animated-text__squeeze-frame');
        if (frame) {
            frame.classList.remove('is-squeezed');
        }
    }

    function disconnectReveal(el) {
        clearTypewriter(el);
        if (el._addonextAtIo) {
            el._addonextAtIo.disconnect();
            el._addonextAtIo = null;
        }
    }

    function initTypewriter(el) {
        var phrases = parsePhrases(el);
        if (!phrases.length) {
            return;
        }
        var speed = parseInt(el.getAttribute('data-typewriter-speed'), 10);
        if (!speed || speed < 10) {
            speed = 100;
        }
        var startDelay = parseInt(el.getAttribute('data-typewriter-delay'), 10);
        if (isNaN(startDelay) || startDelay < 0) {
            startDelay = 0;
        }
        var loop = el.getAttribute('data-typewriter-loop') === '1';
        var phraseListLoop = el.getAttribute('data-phrase-list-loop') === '1';
        var pauseAfterCycle = parseInt(el.getAttribute('data-typewriter-pause'), 10);
        if (!pauseAfterCycle || pauseAfterCycle < 100) {
            pauseAfterCycle = 2000;
        }
        var betweenPhrases = parseInt(el.getAttribute('data-typewriter-between'), 10);
        if (isNaN(betweenPhrases) || betweenPhrases < 0) {
            betweenPhrases = 600;
        }

        var typed = el.querySelector('.addonext-animated-text__typed');
        if (!typed) {
            return;
        }
        var cursor = el.querySelector('.addonext-animated-text__cursor');

        clearTypewriter(el);

        var phraseIndex = 0;

        function typeCurrentPhrase() {
            var text = phrases[phraseIndex];
            var chars = Array.from(text);
            if (cursor) {
                cursor.classList.add('is-visible');
            }
            var i = 0;
            el._addonextAtTypeInterval = setInterval(function () {
                if (i < chars.length) {
                    typed.textContent += chars[i];
                    i += 1;
                } else {
                    clearInterval(el._addonextAtTypeInterval);
                    el._addonextAtTypeInterval = null;
                    onPhraseComplete();
                }
            }, speed);
        }

        function onPhraseComplete() {
            phraseIndex += 1;
            if (phraseIndex >= phrases.length) {
                if (cursor) {
                    cursor.classList.remove('is-visible');
                }
                if (!phraseListLoop) {
                    if (loop) {
                        phraseIndex = 0;
                        el._addonextAtLoopTimeout = setTimeout(function () {
                            runTyping();
                        }, pauseAfterCycle);
                    }
                    return;
                }
                phraseIndex = 0;
                var pause = loop ? pauseAfterCycle : betweenPhrases;
                el._addonextAtLoopTimeout = setTimeout(function () {
                    runTyping();
                }, pause);
                return;
            }
            typed.textContent = '';
            el._addonextAtLoopTimeout = setTimeout(function () {
                typeCurrentPhrase();
            }, betweenPhrases);
        }

        function runTyping() {
            typed.textContent = '';
            phraseIndex = 0;
            typeCurrentPhrase();
        }

        el._addonextAtStartTimeout = setTimeout(function () {
            runTyping();
        }, startDelay);
    }

    function initReveal(el) {
        var phrases = parsePhrases(el);
        if (!phrases.length) {
            return;
        }
        var split = el.getAttribute('data-reveal-split') || 'word';
        var once = el.getAttribute('data-reveal-once') === '1';
        var phraseListLoop = el.getAttribute('data-phrase-list-loop') === '1';
        var betweenMs = parseInt(el.getAttribute('data-reveal-between'), 10);
        if (isNaN(betweenMs) || betweenMs < 0) {
            betweenMs = 600;
        }
        var duration = parseInt(el.getAttribute('data-reveal-duration'), 10);
        if (!duration || duration < 50) {
            duration = 500;
        }
        var stagger = parseInt(el.getAttribute('data-reveal-stagger'), 10);
        if (isNaN(stagger) || stagger < 0) {
            stagger = 80;
        }

        var container = el.querySelector('.addonext-animated-text__animated');
        if (!container) {
            return;
        }

        disconnectReveal(el);

        el._addonextRevealGen = (el._addonextRevealGen || 0) + 1;
        var revealGen = el._addonextRevealGen;

        var phraseIndex = 0;
        var running = false;
        var doneOnce = false;

        function playPhrase() {
            if (el._addonextAtRevealTimeout) {
                clearTimeout(el._addonextAtRevealTimeout);
                el._addonextAtRevealTimeout = null;
            }
            if (revealGen !== el._addonextRevealGen) {
                return;
            }
            if (phraseIndex >= phrases.length) {
                if (phraseListLoop) {
                    phraseIndex = 0;
                } else {
                    running = false;
                    return;
                }
            }
            var text = phrases[phraseIndex];
            el.classList.remove('is-revealed');
            container.innerHTML = buildRevealHtml(text, split);
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    if (revealGen !== el._addonextRevealGen) {
                        return;
                    }
                    el.classList.add('is-revealed');
                    var delay = getRevealDelay(text, split, duration, stagger);
                    el._addonextAtRevealTimeout = setTimeout(function () {
                        if (revealGen !== el._addonextRevealGen) {
                            return;
                        }
                        phraseIndex += 1;
                        el._addonextAtRevealTimeout = setTimeout(function () {
                            if (revealGen !== el._addonextRevealGen) {
                                return;
                            }
                            playPhrase();
                        }, betweenMs);
                    }, delay);
                });
            });
        }

        var io = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        if (el._addonextAtRevealTimeout) {
                            clearTimeout(el._addonextAtRevealTimeout);
                            el._addonextAtRevealTimeout = null;
                        }
                        if (!once) {
                            el.classList.remove('is-revealed');
                            phraseIndex = 0;
                            if (phrases[0]) {
                                container.innerHTML = buildRevealHtml(phrases[0], split);
                            }
                            running = false;
                            doneOnce = false;
                        }
                        return;
                    }
                    if (once && doneOnce) {
                        return;
                    }
                    if (once) {
                        doneOnce = true;
                    }
                    if (!running) {
                        phraseIndex = 0;
                        running = true;
                        playPhrase();
                    }
                });
            },
            { threshold: 0, rootMargin: '0px' }
        );

        io.observe(el);
        el._addonextAtIo = io;
    }

    function initSquareSqueeze(el) {
        var phrases = parsePhrases(el);
        if (!phrases.length) {
            return;
        }
        var frame = el.querySelector('.addonext-animated-text__squeeze-frame');
        var textEl = el.querySelector('.addonext-animated-text__squeeze-text');
        var sizerEl = el.querySelector('.addonext-animated-text__squeeze-sizer');
        if (!frame || !textEl) {
            return;
        }

        function setSqueezePhrase(t) {
            textEl.textContent = t;
            if (sizerEl) {
                sizerEl.textContent = t;
            }
        }

        disconnectSquare(el);

        el._addonextSqGen = (el._addonextSqGen || 0) + 1;
        var gen = el._addonextSqGen;

        var squeezeMs = parseInt(el.getAttribute('data-square-squeeze'), 10);
        if (!squeezeMs || squeezeMs < 100) {
            squeezeMs = 600;
        }
        var expandMs = parseInt(el.getAttribute('data-square-expand'), 10);
        if (!expandMs || expandMs < 100) {
            expandMs = 600;
        }
        var holdMs = parseInt(el.getAttribute('data-square-hold'), 10);
        if (isNaN(holdMs) || holdMs < 0) {
            holdMs = 800;
        }
        var startDelay = parseInt(el.getAttribute('data-square-delay'), 10);
        if (isNaN(startDelay) || startDelay < 0) {
            startDelay = 0;
        }
        var once = el.getAttribute('data-square-once') === '1';
        var phraseListLoop = el.getAttribute('data-phrase-list-loop') === '1';

        var phraseIndex = 0;
        var running = false;
        var stopped = false;
        var doneOnce = false;

        function runCycle() {
            if (stopped || gen !== el._addonextSqGen) {
                return;
            }
            frame.style.setProperty('--sq-dur', squeezeMs + 'ms');
            frame.classList.add('is-squeezed');
            el._addonextAtSqTimeout = setTimeout(function () {
                if (gen !== el._addonextSqGen) {
                    return;
                }
                var next = phraseIndex + 1;
                if (next >= phrases.length) {
                    if (!phraseListLoop) {
                        frame.style.setProperty('--sq-dur', expandMs + 'ms');
                        frame.classList.remove('is-squeezed');
                        stopped = true;
                        running = false;
                        return;
                    }
                    next = 0;
                }
                phraseIndex = next;
                setSqueezePhrase(phrases[phraseIndex]);
                frame.style.setProperty('--sq-dur', expandMs + 'ms');
                frame.classList.remove('is-squeezed');
                el._addonextAtSqTimeout = setTimeout(function () {
                    if (gen !== el._addonextSqGen) {
                        return;
                    }
                    el._addonextAtSqTimeout = setTimeout(function () {
                        if (gen !== el._addonextSqGen || stopped) {
                            return;
                        }
                        runCycle();
                    }, holdMs);
                }, expandMs);
            }, squeezeMs);
        }

        function startSequence() {
            if (running) {
                return;
            }
            phraseIndex = 0;
            setSqueezePhrase(phrases[0]);
            stopped = false;
            running = true;
            el._addonextAtSqStartTimeout = setTimeout(function () {
                if (gen !== el._addonextSqGen) {
                    return;
                }
                runCycle();
            }, startDelay);
        }

        if (typeof IntersectionObserver === 'undefined') {
            startSequence();
        } else {
            var io = new IntersectionObserver(
                function (entries) {
                    entries.forEach(function (entry) {
                        if (!entry.isIntersecting) {
                            if (el._addonextAtSqTimeout) {
                                clearTimeout(el._addonextAtSqTimeout);
                                el._addonextAtSqTimeout = null;
                            }
                            if (el._addonextAtSqStartTimeout) {
                                clearTimeout(el._addonextAtSqStartTimeout);
                                el._addonextAtSqStartTimeout = null;
                            }
                            frame.classList.remove('is-squeezed');
                            phraseIndex = 0;
                            if (phrases[0]) {
                                setSqueezePhrase(phrases[0]);
                            }
                            running = false;
                            stopped = false;
                            if (!once) {
                                doneOnce = false;
                            }
                            return;
                        }
                        if (once && doneOnce) {
                            return;
                        }
                        if (once) {
                            doneOnce = true;
                        }
                        startSequence();
                    });
                },
                { threshold: 0, rootMargin: '0px' }
            );

            io.observe(el);
            el._addonextAtSqIo = io;
        }
    }

    function initEl(el) {
        if (!el || el.getAttribute('data-at-init') === '1') {
            return;
        }
        el.setAttribute('data-at-init', '1');

        var anim = el.getAttribute('data-animation');
        if (anim === 'typewriter') {
            initTypewriter(el);
        } else if (anim === 'reveal') {
            initReveal(el);
        } else if (anim === 'square_squeeze') {
            initSquareSqueeze(el);
        }
    }

    function resetAndInit(el) {
        el.removeAttribute('data-at-init');
        disconnectSquare(el);
        disconnectReveal(el);
        initEl(el);
    }

    function scan(root) {
        var scope = root || document;
        var nodes = scope.querySelectorAll('.addonext-animated-text');
        for (var n = 0; n < nodes.length; n += 1) {
            initEl(nodes[n]);
        }
    }

    function onDomReady() {
        scan(document);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', onDomReady);
    } else {
        onDomReady();
    }

    function bindElementor() {
        if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks || !elementorFrontend.hooks.addAction) {
            return;
        }
        elementorFrontend.hooks.addAction('frontend/element_ready/addonext-animated-text.default', function ($scope) {
            if ($scope && $scope.find) {
                $scope.find('.addonext-animated-text').each(function () {
                    resetAndInit(this);
                });
            } else if ($scope && $scope[0]) {
                var list = $scope[0].querySelectorAll('.addonext-animated-text');
                for (var i = 0; i < list.length; i += 1) {
                    resetAndInit(list[i]);
                }
            }
        });
    }

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
        bindElementor();
    } else {
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(bindElementor, 0);
        });
        window.addEventListener('load', function () {
            setTimeout(bindElementor, 0);
        });
    }
})();
