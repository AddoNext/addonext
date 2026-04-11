'use strict';

function elementrifyNoticeBoardInit() {
  document.addEventListener('click', function (e) {
    var trigger = e.target.closest('.elementrify-notice-board__trigger');
    if (!trigger) {
      return;
    }
    var item = trigger.closest('.elementrify-notice-board__item');
    if (!item) {
      return;
    }
    var board = item.closest('.elementrify-notice-board');
    if (board && board.classList.contains('elementrify-notice-board--expand-all')) {
      return;
    }
    e.preventDefault();
    var panel = item.querySelector('.elementrify-notice-board__panel');
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
    var trigger = e.target.closest('.elementrify-notice-board__trigger');
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
  document.addEventListener('DOMContentLoaded', elementrifyNoticeBoardInit);
} else {
  elementrifyNoticeBoardInit();
}
