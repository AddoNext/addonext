'use strict';

function addonextNoticeBoardInit() {
  document.addEventListener('click', function (e) {
    var trigger = e.target.closest('.addonext-notice-board__trigger');
    if (!trigger) {
      return;
    }
    var item = trigger.closest('.addonext-notice-board__item');
    if (!item) {
      return;
    }
    var board = item.closest('.addonext-notice-board');
    if (board && board.classList.contains('addonext-notice-board--expand-all')) {
      return;
    }
    e.preventDefault();
    var panel = item.querySelector('.addonext-notice-board__panel');
    var nextOpen = !item.classList.contains('is-open');
    item.classList.toggle('is-open', nextOpen);
    trigger.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
    if (panel) {
      if (nextOpen) {
        panel.removeAttribute('hidden');
      } else {
        panel.setAttribute('hidden', '');
      }
    }
  });

  document.addEventListener('keydown', function (e) {
    var trigger = e.target.closest('.addonext-notice-board__trigger');
    if (!trigger) {
      return;
    }
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      trigger.click();
    }
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', addonextNoticeBoardInit);
} else {
  addonextNoticeBoardInit();
}
