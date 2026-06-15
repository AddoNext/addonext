(function () {
    'use strict';

    var lightbox;
    var imageEl;
    var closeEl;
    var prevEl;
    var nextEl;
    var currentItems = [];
    var currentIndex = -1;

    function ensureLightbox() {
        if (lightbox) {
            return;
        }
        lightbox = document.createElement('div');
        lightbox.className = 'veloria-image-gallery-lightbox';
        lightbox.innerHTML =
            '<button type="button" class="veloria-image-gallery-lightbox__nav veloria-image-gallery-lightbox__nav--prev" aria-label="Previous image">&#8249;</button>' +
            '<button type="button" class="veloria-image-gallery-lightbox__nav veloria-image-gallery-lightbox__nav--next" aria-label="Next image">&#8250;</button>' +
            '<button type="button" class="veloria-image-gallery-lightbox__close" aria-label="Close preview">&times;</button>' +
            '<img class="veloria-image-gallery-lightbox__image" alt="">';
        document.body.appendChild(lightbox);
        imageEl = lightbox.querySelector('.veloria-image-gallery-lightbox__image');
        closeEl = lightbox.querySelector('.veloria-image-gallery-lightbox__close');
        prevEl = lightbox.querySelector('.veloria-image-gallery-lightbox__nav--prev');
        nextEl = lightbox.querySelector('.veloria-image-gallery-lightbox__nav--next');

        closeEl.addEventListener('click', closeLightbox);
        prevEl.addEventListener('click', function (event) {
            event.stopPropagation();
            showAt(currentIndex - 1);
        });
        nextEl.addEventListener('click', function (event) {
            event.stopPropagation();
            showAt(currentIndex + 1);
        });
        lightbox.addEventListener('click', function (event) {
            if (event.target === lightbox) {
                closeLightbox();
            }
        });
        document.addEventListener('keydown', function (event) {
            if (!lightbox.classList.contains('is-open')) {
                return;
            }
            if (event.key === 'Escape') {
                closeLightbox();
            } else if (event.key === 'ArrowLeft') {
                showAt(currentIndex - 1);
            } else if (event.key === 'ArrowRight') {
                showAt(currentIndex + 1);
            }
        });
    }

    function showAt(index) {
        if (!currentItems.length) {
            return;
        }
        if (index < 0) {
            index = currentItems.length - 1;
        } else if (index >= currentItems.length) {
            index = 0;
        }
        currentIndex = index;
        var item = currentItems[currentIndex];
        imageEl.src = item.url || '';
        imageEl.alt = item.alt || '';
    }

    function openLightbox(items, index) {
        if (!items || !items.length) {
            return;
        }
        ensureLightbox();
        currentItems = items;
        showAt(index);
        lightbox.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        if (!lightbox) {
            return;
        }
        lightbox.classList.remove('is-open');
        imageEl.src = '';
        imageEl.alt = '';
        currentItems = [];
        currentIndex = -1;
        document.body.style.overflow = '';
    }

    document.addEventListener('click', function (event) {
        var button = event.target.closest('.veloria-image-gallery__preview');
        if (!button) {
            return;
        }
        event.preventDefault();
        var gallery = button.closest('.veloria-image-gallery');
        if (!gallery) {
            return;
        }
        var buttons = Array.prototype.slice.call(gallery.querySelectorAll('.veloria-image-gallery__preview'));
        var items = buttons.map(function (btn) {
            return {
                url: btn.getAttribute('data-image-url') || '',
                alt: btn.getAttribute('data-image-alt') || ''
            };
        }).filter(function (item) {
            return item.url !== '';
        });
        var index = buttons.indexOf(button);
        if (index < 0) {
            index = 0;
        }
        openLightbox(items, index);
    });
})();

