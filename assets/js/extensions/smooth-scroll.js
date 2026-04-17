/**
 * Initializes Lenis for smooth wheel scrolling on the document.
 *
 * @see https://github.com/darkroomengineering/lenis
 */
(function () {
  "use strict";

  if (typeof window.Lenis === "undefined") {
    return;
  }

  if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    return;
  }

  var lenis = new window.Lenis({
    smoothWheel: true,
    syncTouch: false,
  });

  function raf(time) {
    lenis.raf(time);
    window.requestAnimationFrame(raf);
  }

  window.requestAnimationFrame(raf);
})();
