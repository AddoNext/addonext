'use strict';

function addonextAccordionInit() {
  document.addEventListener('click', function (e) {
    var header = e.target.closest('.addonext-accordion__header');
    if (!header) return;
    e.preventDefault();
    e.stopPropagation();
    var item = header.closest('.addonext-accordion__item');
    var accordion = header.closest('.addonext-accordion');
    if (!accordion || !item) return;

    var allowMultiple = accordion.getAttribute('data-allow-multiple') === '1';
    var isOpen = item.classList.contains('is-open');

    if (!allowMultiple) {
      var others = accordion.querySelectorAll('.addonext-accordion__item');
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
    var header = e.target.closest('.addonext-accordion__header');
    if (!header) return;
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      header.click();
    }
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', addonextAccordionInit);
} else {
  addonextAccordionInit();
}
