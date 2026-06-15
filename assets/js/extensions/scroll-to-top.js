/**
 * Scroll-to-top floating control.
 */
(function () {
  "use strict";

  var cfg = window.veloriaScrollToTop;

  if (!cfg || typeof cfg !== "object") {
    return;
  }

  function parseIds(list) {
    if (!Array.isArray(list)) {
      return [];
    }
    return list
      .map(function (id) {
        return parseInt(String(id), 10);
      })
      .filter(function (n) {
        return n > 0;
      });
  }

  function shouldShow() {
    var mode = cfg.visibility || "all";
    var ids = parseIds(cfg.pageIds);
    var current = parseInt(String(cfg.currentPostId || 0), 10) || 0;
    var frontId = parseInt(String(cfg.frontPageId || 0), 10) || 0;
    var isFront = Boolean(cfg.isFront);

    if (mode === "all") {
      return true;
    }

    if (mode === "exclude") {
      if (isFront && frontId > 0 && ids.indexOf(frontId) !== -1) {
        return false;
      }
      if (current > 0 && ids.indexOf(current) !== -1) {
        return false;
      }
      return true;
    }

    if (mode === "include_only") {
      if (isFront && frontId > 0 && ids.indexOf(frontId) !== -1) {
        return true;
      }
      if (current > 0 && ids.indexOf(current) !== -1) {
        return true;
      }
      return false;
    }

    return true;
  }

  if (!shouldShow()) {
    return;
  }

  var btn = document.createElement("button");
  btn.type = "button";
  btn.id = "veloria-scroll-top";
  btn.setAttribute("data-position", cfg.position || "bottom-right");
  btn.setAttribute("aria-label", "Scroll to top");
  btn.innerHTML =
    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 5.5 4 13.5l1.4 1.4L12 8.3l6.6 6.6L20 13.5z"/></svg>';

  btn.addEventListener("click", function () {
    var reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    if (reduce) {
      window.scrollTo(0, 0);
    } else {
      window.scrollTo({ top: 0, left: 0, behavior: "smooth" });
    }
  });

  function onScroll() {
    var y = window.scrollY || document.documentElement.scrollTop || 0;
    btn.style.opacity = y > 240 ? "1" : "0";
    btn.style.pointerEvents = y > 240 ? "auto" : "none";
  }

  function mount() {
    document.body.appendChild(btn);
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", mount);
  } else {
    mount();
  }
})();
