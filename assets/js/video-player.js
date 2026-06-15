(function () {
    document.addEventListener('click', function (e) {
        var playBtn = e.target.closest('.veloria-video-player__play');
        if (!playBtn) return;
        e.preventDefault();
        var inner = playBtn.closest('.veloria-video-player__inner');
        if (!inner) return;
        if (inner.classList.contains('is-playing')) return;

        var embedEl = inner.querySelector('.veloria-video-player__embed');
        if (!embedEl) return;

        var embedUrl = inner.getAttribute('data-embed');
        var hostedUrl = inner.getAttribute('data-hosted');
        var type = inner.getAttribute('data-type') || '';

        if (type === 'hosted' && hostedUrl) {
            var video = document.createElement('video');
            video.setAttribute('src', hostedUrl);
            video.setAttribute('controls', '');
            video.setAttribute('playsinline', '');
            video.setAttribute('autoplay', '');
            video.style.position = 'absolute';
            video.style.inset = '0';
            video.style.width = '100%';
            video.style.height = '100%';
            video.style.objectFit = 'cover';
            embedEl.appendChild(video);
        } else if (embedUrl) {
            var iframe = document.createElement('iframe');
            iframe.setAttribute('src', embedUrl);
            iframe.setAttribute('frameborder', '0');
            iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
            iframe.setAttribute('allowfullscreen', '');
            iframe.style.position = 'absolute';
            iframe.style.top = '0';
            iframe.style.left = '0';
            iframe.style.width = '100%';
            iframe.style.height = '100%';
            iframe.style.border = 'none';
            embedEl.appendChild(iframe);
        }

        inner.classList.add('is-playing');
    }, true);
})();
