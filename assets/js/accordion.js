'use strict';

function elementrifyAccordionInit() {
  document.addEventListener('click', function (e) {
    var header = e.target.closest('.elementrify-accordion__header');
    if (!header) return;
    e.preventDefault();
    e.stopPropagation();
    var item = header.closest('.elementrify-accordion__item');
    var accordion = header.closest('.elementrify-accordion');
    if (!accordion || !item) return;

    var allowMultiple = accordion.getAttribute('data-allow-multiple') === '1';
    var isOpen = item.classList.contains('is-open');

    if (!allowMultiple) {
      var others = accordion.querySelectorAll('.elementrify-accordion__item');
      others.forEach(function (el) {
        el.classList.remove('is-open');
      });
    }

    if (isOpen) {
      item.classList.remove('is-open');
    } else {
      item.classList.add('is-open');
    }
  });

  document.addEventListener('keydown', function (e) {
    var header = e.target.closest('.elementrify-accordion__header');
    if (!header) return;
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      header.click();
    }
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', elementrifyAccordionInit);
} else {
  elementrifyAccordionInit();
}
