(function () {
  "use strict";

  function getScrollProgress() {
    var doc = document.documentElement;
    var scrollTop = window.scrollY || doc.scrollTop || 0;
    var maxScroll = doc.scrollHeight - window.innerHeight;

    if (maxScroll <= 0) {
      return 0;
    }

    return Math.min(100, Math.max(0, (scrollTop / maxScroll) * 100));
  }

  function update(fill) {
    fill.style.width = getScrollProgress().toFixed(2) + "%";
  }

  function mount() {
    if (document.getElementById("elementrify-reading-progress")) {
      return;
    }

    var bar = document.createElement("div");
    bar.id = "elementrify-reading-progress";
    bar.setAttribute("aria-hidden", "true");

    var fill = document.createElement("div");
    fill.className = "elementrify-reading-progress__fill";
    bar.appendChild(fill);

    document.body.appendChild(bar);

    update(fill);
    window.addEventListener("scroll", function () { update(fill); }, { passive: true });
    window.addEventListener("resize", function () { update(fill); });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", mount);
  } else {
    mount();
  }
})();
